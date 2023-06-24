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

class usdt_jt{
    public function  execute(): string{  
        #固定应用
        $BOT = Db::name('bot_list')->where("plugin","SwapTRX8bot")->cache("SwapTRX8bot")->find();  
        if(empty($BOT)){ 
            echo "\n监听USDT到账·请配置机器人信息";
            return 'No Bot'; 
        }
        $setup = Db::name('trx_setup')->where("plugin",$BOT['plugin'])->where("bot",$BOT['API_BOT'])->cache("trx_{$BOT['API_BOT']}_setup")->find();
        if(empty($setup)){ 
            echo "\n监听USDT到账·机器人{$BOT['API_BOT']}：未设置钱包数据";
            return 'No addr'; 
        }   
          
        
        $headers = []; 
        $Account = Account::SetPrivateKey($setup["PrivateKey"]); 
        $addres = $Account->address()->__toString();
        if(!empty($setup['TRON_API_KEY'])){
            $headers = ['TRON-PRO-API-KEY' => $setup['TRON_API_KEY'] ];   
        }
        $client = new Guzz_Client(['timeout' => 8,'http_errors' => false,'headers' =>$headers]);
        $time = time() - $setup['Ttime'] - 3600; 
        $res =json_decode($client->request('GET', "https://api.trongrid.io/v1/accounts/{$addres}/transactions/trc20?limit=50&only_to=true&min_timestamp={$time}000&contract_address=TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t")->getBody()->getContents(),true);  
        if(!count($res['data'])){
            return 'oka'; 
        } 
        
             
        foreach ($res['data'] as $resval) { 
            $LOCK_val = "PAY_".$BOT["API_BOT"];
            $LOCK = Redis::HSETNX($LOCK_val,$resval["transaction_id"],1);
            if($LOCK){  
                 Redis::EXPIRE($LOCK_val,$setup["Ttime"]+10);   
                 $usdt_list = Db::name('bot_usdt_list')->where('txid', $resval["transaction_id"])->find();
                 if($usdt_list){  
                     continue;  
                 }
             
             
             
                $sql['bot'] = $BOT["API_BOT"]; 
                $sql['txid'] = $resval["transaction_id"];  
                $sql['ufrom'] = $resval["from"];  
                $sql['uto'] = $resval["to"];  
                $sql['value'] = $resval["value"];  
                $sql['time'] = substr($resval["block_timestamp"],0,10);    
                $price = Redis::GET("TRXprice");
                if($price){
                    $dec =  round($price * $setup['Rate'] / 100,2);
                    $price = $price -$dec;
                    $sql['oktrx'] = $sql['value'] / 1000000 * $price;  
                }
        
        
                //  ---------多表记录更新-------
                $dated = date("Ymd",$sql['time']);
                $dateh = date("YmdH",$sql['time']);
                $totalh = Db::name('bot_total_h')->where('bot',$BOT["API_BOT"])->where('dateh',$dateh)->find();
                if(empty($totalh)){ 
                    $total_h = ["bot"=>$BOT["API_BOT"],"dateh"=>$dateh,"numu"=>1,"usdt"=>$resval["value"],"time"=>$sql["time"]];
                    Db::name('bot_total_h')->insert($total_h); //时
                    
                    //没有小时数据时 检查有没有天数据
                    $totald = Db::name('bot_total_d')->where('bot',$BOT["API_BOT"])->where('dated',$dated)->find();
                    if(empty($totald)){
                        $total_d = ["bot"=>$BOT["API_BOT"],"dated"=>$dated,"numu"=>1,"usdt"=>$resval["value"],"time"=>$sql["time"]];
                        Db::name('bot_total_d')->insert($total_d); 
                    }
                    
                }else{ 
                    //有小时数据 就一定就有天数据
                    Db::name('bot_total_h')->where('id',$totalh["id"])->inc("numu",1)->inc("usdt",$resval["value"])->update();
                    Db::name('bot_total_d')->where('bot',$BOT["API_BOT"])->where('dated',$dated)->inc("numu",1)->inc("usdt",$resval["value"])->update();
                    
                }
                
                $total_trc20 = Db::name('bot_total_trc20')->where('bot',$BOT["API_BOT"])->where('trc20',$resval["from"])->find();
                if($total_trc20){
                    Db::name('bot_total_trc20')->where('bot',$BOT["API_BOT"])->where('id',$total_trc20["id"])->inc("numu",1)->inc("usdt",$resval["value"])->update(["time"=>$sql['time']]);    
                }else{ 
                    Db::name('bot_total_trc20')->insert(["trc20"=>$resval["from"],"numu"=>1,"usdt"=>$resval["value"],"time"=>$sql["time"],"bot"=>$BOT["API_BOT"]]); 
                } 
                  
                if(!empty($total_trc20['tgid'])){
                    $queueData['tgid'] = $total_trc20['tgid']; 
                    Db::name('account_tg')->where('tgid',$total_trc20['tgid'])->inc("dhnumu",1)->inc("dhusdt",$resval["value"])->update();    
                } 
                //  ---------多表记录更新end-------
                 
                
                
                
                if($resval["value"] < ($setup['Minusdt']*1000000)){
                    $sql['msg'] = "低于设定的{$setup['Minusdt']}u";  
                    $sql['okzt'] = 2;//0收到 1确认中 2失败 3成功
                    Db::name('bot_usdt_list')->save($sql);  
                    continue;
                }else{ 
                    if(empty($total_trc20['disable'])){
                        $sql['msg'] ="处理中..";
                        $sql['okzt'] = 0;//0收到 1确认中 2失败 3成功
                        $dbbb = Db::name('bot_usdt_list')->save($sql);
                        
                    }else{
                        $sql['msg'] ="黑名单";
                        $sql['okzt'] = 2;//0收到 1确认中 2失败 3成功 
                        $dbbb = Db::name('bot_usdt_list')->save($sql);
                        continue;
                    }    
                }
                 
                 
                 #最终丢入回trx队列
                 $queueData['bot'] = $BOT["API_BOT"]; 
                 $queueData['txid'] = $resval["transaction_id"]; 
                 $queueData['from'] = $resval["from"]; 
                 $queueData['to'] = $resval["to"]; 
                 $queueData['value'] = $resval["value"]; 
                 $queueData['time'] = $resval["block_timestamp"]; 
                 Client::send('UsdtTrx',$queueData);     
             
             
            }//redis
        
        
        
        
        }//fro end
         
          
        return "ok"; 
    }
    
}