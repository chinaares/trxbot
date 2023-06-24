<?php

namespace app\task;
use support\Redis;//redis缓存
use Webman\RedisQueue\Client; #redis queue 队列
use support\Log;//日志
use think\facade\Db;#mysql https://www.kancloud.cn/manual/think-orm/1258003
use think\facade\Cache;#Cache https://www.kancloud.cn/manual/thinkphp6_0/1037634

use TNTma\TronWeb\Address;
use TNTma\TronWeb\Account;
use TNTma\TronWeb\Tron;

use GuzzleHttp\Pool;
use GuzzleHttp\Client as Guzz_Client;
use GuzzleHttp\Psr7\Request as Guzz_Request; 
use GuzzleHttp\Promise as Guzz_Promise;

class trx_jt{
    public function  execute(): string{ 
        #固定应用
        $BOT = Db::name('bot_list')->where("plugin","SwapTRX8bot")->cache("SwapTRX8bot")->find();  
        if(empty($BOT)){ 
            echo "\n监听TRX出账·请配置机器人信息";
            return 'No Bot'; 
        }
        $setup = Db::name('trx_setup')->where("plugin",$BOT['plugin'])->where("bot",$BOT['API_BOT'])->cache("trx_{$BOT['API_BOT']}_setup")->find();
        if(empty($setup)){ 
            echo "\n监听TRX出账·机器人{$BOT['API_BOT']}：未设置钱包数据";
            return 'No addr'; 
        }
        
        $headers = []; 
        $Account = Account::SetPrivateKey($setup["PrivateKey"]); 
        $addres = $Account->address()->__toString();
        if(!empty($setup['TRON_API_KEY'])){
            $headers = ['TRON-PRO-API-KEY' => $setup['TRON_API_KEY'] ];   
        }
        $client = new Guzz_Client(['timeout' => 8,'http_errors' => false,'headers' =>$headers]);
        $time = time() - $setup['Ttime'];  
        $res =json_decode($client->request('GET', "https://api.trongrid.io/v1/accounts/{$addres}/transactions?limit=50&only_from=true&min_timestamp={$time}000")->getBody()->getContents(),true); 
        if(!count($res['data'])){
            return 'oka'; 
        } 
 
       
        foreach ($res['data'] as $resval) {
            $TXID =   Redis::HEXISTS("OK_{$BOT["API_BOT"]}",$resval["txID"]);  
            if(!$TXID){ 
                continue; 
            } 
            $usdt_list = Db::name('bot_usdt_list')->where('oktxid', $resval["txID"])->find();
            if(empty($usdt_list)){ 
                echo "\n\033[31m警告：可能出现数据丢失,掉单了交易哈希：{$resval['txID']}\033[0m\n";
                Redis::HDEL("OK_{$BOT["API_BOT"]}",$resval["txID"]);
                continue;
            }
        
            #交易成功net_usage 消耗资源-带宽 
            $oktime = substr($resval["block_timestamp"],0,10);
            Db::name('bot_usdt_list')->where("id",$usdt_list['id'])->update(['msg'=>"交易成功",'okzt'=>3,"oktime"=>$oktime]);
            $usdt_list['msg']="交易成功";
            $usdt_list['okzt']=3;
            $usdt_list['oktime']=$oktime;
            #丢入队列通知
            $queueData['API_BOT'] = $BOT["API_BOT"];  
            $queueData['type'] = "SwapOk";
            $queueData['plugin'] = $BOT["plugin"];
            $queueData['data'] = $usdt_list; 
            Client::send('TG_queue',$queueData);     
            #删除
            Redis::HDEL("OK_{$BOT["API_BOT"]}",$resval["txID"]);
        
            //  ---------多表记录更新-------
            $oktrx = $usdt_list['oktrx'] * 1000000;
            $dated = date("Ymd",$oktime);
            $dateh = date("YmdH",$oktime);
            $totalh = Db::name('bot_total_h')->where('bot',$BOT["API_BOT"])->where('dateh',$dateh)->find();
            if(empty($totalh)){ 
                $total_h = ["bot"=>$BOT["API_BOT"],"dateh"=>$dateh,"numt"=>1,"trx"=>$oktrx,"time"=>$oktime];
                Db::name('bot_total_h')->insert($total_h); //时
                
                //没有小时数据时 检查有没有天数据
                $totald = Db::name('bot_total_d')->where('bot',$BOT["API_BOT"])->where('dated',$dated)->find();
                if(empty($totald)){
                    $total_d = ["bot"=>$BOT["API_BOT"],"dated"=>$dated,"numt"=>1,"trx"=>$oktrx,"time"=>$oktime];
                    Db::name('bot_total_d')->insert($total_d); 
                }
                
            }else{ 
                //有小时数据 就一定就有天数据
                Db::name('bot_total_h')->where('id',$totalh["id"])->inc("numt",1)->inc("trx",$oktrx)->update();
                Db::name('bot_total_d')->where('bot',$BOT["API_BOT"])->where('dated',$dated)->inc("numt",1)->inc("trx",$oktrx)->update();
                
            }
        
            $total_trc20 = Db::name('bot_total_trc20')->where('bot',$BOT["API_BOT"])->where('trc20',$usdt_list["ufrom"])->find();
            if(empty($total_trc20)){
                Db::name('bot_total_trc20')->insert(["trc20"=>$resval["from"],"numt"=>1,"trx"=>$oktrx,"time"=>$oktime,"bot"=>$BOT["API_BOT"]]); 
            }else{ 
                Db::name('bot_total_trc20')->where('bot',$BOT["API_BOT"])->where('id',$total_trc20["id"])->inc("numt",1)->inc("trx",$oktrx)->update(["time"=>$oktime]);
                 
            } 
            if(!empty($total_trc20['tgid'])){
                $queueData['tgid'] = $total_trc20['tgid']; 
                Db::name('account_tg')->where('bot',$BOT["API_BOT"])->where('tgid',$total_trc20['tgid'])->inc("dhtrx",$oktrx)->update();    
            }
        
             //  ---------多表记录更新end------- 
            #计算返利 数据
            if($this->BOT['fanli']> 0 && !empty($total_trc20['tgid'])){
                $dh_account = Db::name('account_tg')->where('bot',$BOT["API_BOT"])->where('tgid',$total_trc20['tgid'])->find();
                if($dh_account['up']){   
                    $price =  Redis::GET("TRXprice");
                    $trx = $usdt_list['value'] / 1000000 * $price;
                    $fan = round($trx * $setup['fanli'] / 100 ,2);  
                    Db::name('account_tg')->where('bot',$BOT["API_BOT"])->where('tgid',$dh_account['up'])->inc("tgtrx",$fan)->inc("tgyue",$fan)->update();  
                }
                
                
            }//返利
        
        
        
          
        }//for
        
       return "ok";  
    }
}