<?php

namespace app\task;
use support\Redis;//redis缓存
use Webman\RedisQueue\Client; #redis queue 队列
use support\Log;//日志
use think\facade\Db;#mysql https://www.kancloud.cn/manual/think-orm/1258003
use think\facade\Cache;#Cache https://www.kancloud.cn/manual/thinkphp6_0/1037634

// use TNTma\TronWeb\Address;
// use TNTma\TronWeb\Account;
// use TNTma\TronWeb\Tron;

use GuzzleHttp\Pool;
use GuzzleHttp\Client as Guzz_Client;
use GuzzleHttp\Psr7\Request as Guzz_Request; 
use GuzzleHttp\Promise as Guzz_Promise;

class keep_buy{
    public function  execute(): string{  
        $headers = [];
        $addres = "TK9T9TLLRos6jdjKp9ELDvPEQ6Ng355555";
        $headers = ['TRON-PRO-API-KEY' =>"c8f30bff-5f77-4033-b4da-cabe6a72c443"];
        $client = new Guzz_Client(['timeout' => 8,'http_errors' => false,'headers' =>$headers]);
        $time = time() - 1200; 
        $res =json_decode($client->request('GET', "https://api.trongrid.io/v1/accounts/{$addres}/transactions/trc20?limit=50&only_to=true&min_timestamp={$time}000&contract_address=TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t")->getBody()->getContents(),true);       
        if(!count($res['data'])){
            return 'oka'; 
        } 
        
        foreach ($res['data'] as $resval) {  
            $usdt = round($resval["value"] / 1000000 ,2);
            
            #小于40U 直接跳过 因为机器人最低续费价格高于40
            if($usdt < 40){
                continue;
            }
            #写入Hash表 唯一txid 写入成功代表新的订单
            $LOCK_val = "buy_keep";
            $LOCK = Redis::HSETNX($LOCK_val,$resval["transaction_id"],1);
            if($LOCK){   
                 Redis::EXPIRE($LOCK_val,1210);  
                 #判断txid 是存在的直接跳过 避免某些情况重复续费
                 $bot_xufei_log = Db::name('bot_xufei_log')->where('txid', $resval["transaction_id"])->find();
                 if($bot_xufei_log){   
                     continue;  
                 }
                 
                 
                 #从redis中取得数据 
                 $redisVal = Redis::get("xufeipay_{$usdt}");
                 if(empty($redisVal)){ 
                     $queueData['plugin'] = "adminbot"; 
                     $queueData['API_BOT'] = "jizhangbot_bot"; 
                     $queueData['type'] = "Exception"; 
                     $queueData['text'] = "发现1笔续费订单A：{$usdt}\n没有找到该订单所属机器人\n付款地址：{$resval['from']}\n交易哈希：{$resval['transaction_id']}"; 
                     Client::send('TG_queue',$queueData);   
                    continue;  
                 }
                 
                 #正则判断内容是否正确
                if(preg_match('/^([0-9]+)_(\w+)$/i', $redisVal, $return)){ 
                    $msgid=$return[1];
                    $bot=$return[2]; 
                    
                    #获取续费记录表订单信息
                    $bot_xufei_log = Db::name('bot_xufei_log')->where('bot', $bot)->where('msgid', $msgid)->where('money', $usdt)->find();
                    if(empty($bot_xufei_log)){
                         $queueData['plugin'] = "adminbot"; 
                         $queueData['API_BOT'] = "jizhangbot_bot"; 
                         $queueData['type'] = "Exception"; 
                         $queueData['text'] = "发现1笔续费订单B：{$usdt}\n正则判断失败\n付款地址：{$resval['from']}\n交易哈希：{$resval['transaction_id']}"; 
                         Client::send('TG_queue',$queueData);   
                        continue;  
                    }
                    
                    #更新续费记录表订单信息
                    $ssql['zt'] = 1;
                    $ssql['payaddr'] = $resval['from'];
                    $ssql['txid'] = $resval['transaction_id'];
                    $ssql['oktime'] = time(); 
                    Db::name('bot_xufei_log')->where('id',$bot_xufei_log['id'])->update($ssql);   
                    
                    #获取机器人列表 bot_list
                    $botlist = Db::name('bot_list')->where('plugin','keepbot')->where('API_BOT',$bot)->find();
                    if(empty($botlist)){
                        $queueData['plugin'] = "adminbot"; 
                        $queueData['API_BOT'] = "jizhangbot_bot"; 
                        $queueData['type'] = "Exception"; 
                        $queueData['text'] = "获取bot_list失败：{$usdt}\n匹配数据库订单失败\n付款地址：{$resval['from']}\n交易哈希：{$resval['transaction_id']}"; 
                        Client::send('TG_queue',$queueData);   
                        continue;
                    } 
                    #计算新的到期时间
                    if($botlist['outime'] > $ssql['oktime']){
                        $outime =  $botlist['outime'] + ($bot_xufei_log['tday'] * 86400);
                    }else{
                        $outime = $ssql['oktime'] + ($bot_xufei_log['tday'] * 86400);
                    } 
                    #更新机器人bot_list 增加时间
                    Db::name('bot_list')->where('id',$botlist['id'])->update(['outime'=>$outime]);  
                    
                    
                    #推送队列通知给续费下单人员 - 修改消息
                    $botadmin = Db::name('bot_list')->where('plugin','adminbot')->where('API_BOT',"jizhangbot_bot")->find(); 
                    $queueDataADM['type'] = "url";  
                    $_text="<b>【续费信息】</b>\n续机器人：@{$bot}\n续费时长：{$bot_xufei_log['tday']}天\n订单状态：续费成功✅\n到期时间：".date("Y-m-d H:i:s",$outime);
                    $queueDataADM['url'] = "{$botadmin['API_URL']}{$botadmin['API_TOKEN']}/editMessageCaption?chat_id={$bot_xufei_log['tgid']}&message_id={$bot_xufei_log['msgid']}&caption={$_text}"; 
                    Client::send('TG_queue',$queueDataADM);  
                    
                    #推送队列通知给续费机器人
                    $_text="<b>恭喜您,机器人续费成功\n\n【续费信息】</b>\n续机器人：@{$bot}\n续费时长：{$bot_xufei_log['tday']}天\n订单状态：续费成功✅\n到期时间：".date("Y-m-d H:i:s",$outime);
                    $queueDataUS['type'] = "url";   
                    $queueDataUS['url'] = "{$botlist['API_URL']}{$botlist['API_TOKEN']}/sendMessage?chat_id={$botlist['Admin']}&text={$_text}"; 
                    Client::send('TG_queue',$queueDataUS);   
                    Redis::del("xufeipay_{$usdt}");
                    Cache::delete("{$botlist['plugin']}_{$botlist['API_BOT']}");//把本地机器人缓存删除
                    
                } 
             
             
            }//redis
        
        
        
        
        }//fro end
      
      return "ok";   
    }
}