<?php
namespace app\queue\redis;

 
use Webman\RedisQueue\Consumer;
use Exception;
use think\facade\Db;
use think\facade\Cache;#Cache https://www.kancloud.cn/manual/thinkphp6_0/1037634
use support\Redis;//redis缓存
use Webman\Push\Api; //push推送 
use GatewayWorker\Lib\Gateway;
use Webman\RedisQueue\Client; #redis queue 队列

#不确定数量的请求
use GuzzleHttp\Pool;
use GuzzleHttp\Client as Guzz_Client;
use GuzzleHttp\Psr7\Request as Guzz_Request; 
use GuzzleHttp\Promise as Guzz_Promise;

use TNTma\TronWeb\Address;
use TNTma\TronWeb\Account;
use TNTma\TronWeb\Tron; 
    
class UsdtTrx implements Consumer{ 
    public $queue = 'UsdtTrx';// 要消费的队列名 
    public $connection = 'usdttrx'; 

    #消费
    public function consume($data){   
         
        if(empty($data['bot'])){
            Db::name('bot_usdt_list')->where("txid",$data['txid'])->update(['msg'=>"缺少bot参数"]);
            echo "\033[1;31m回TRX失败,缺少bot参数\033[0m\n"; 
            return;
        } 
        #固定应用
        $BOT = Db::name('bot_list')->where("plugin","SwapTRX8bot")->cache("SwapTRX8bot")->find();  
        if(empty($BOT)){ 
            echo "\n回TRX·请配置机器人信息";
            return 'No Bot'; 
        }
        $setup = Db::name('trx_setup')->where("plugin",$BOT['plugin'])->where("bot",$BOT['API_BOT'])->cache("trx_{$BOT['API_BOT']}_setup")->find();
        if(empty($setup)){ 
            echo "\回TRX·机器人{$BOT['API_BOT']}：未设置钱包数据";
            return 'No addr'; 
        }
        
        echo "\n消费u换t\n"; 
        var_export($data);
        
        #判断兑换模式
        if($setup['type'] == 1){
            #判断redis是否存在价格
            $price = Redis::GET("TRXprice");
            if(empty($price)){
                $client = new Guzz_Client(['timeout' => 8,'http_errors' => false]);  
                $res = json_decode($client->request('GET', "https://openapi.sun.io/v2/allpairs?page_size=1&page_num=0&token_address=TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t&orderBy=price")->getBody()->getContents(),true);   
                if(!empty($res['data']['0_TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t'])){
                    $price = round($res['data']['0_TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t']['price'],2); 
                    Redis::SETEX("TRXprice",600,$price);//trx实时价格 过期时间 redis
                }   
            }
            $dec =  round($price * $setup['Rate'] / 100,2);
            $price = $price -$dec;
            
            
        }else if($setup['type'] == 2){
            $price = $setup['Price'];
        }
        
        $trx = $data['value'] / 1000000 * $price;
         
 
        
        $PrivateKey = Account::SetPrivateKey($setup['PrivateKey']);
        $address = $PrivateKey->address()->__toString();
        $tron = new Tron(1,$PrivateKey,$setup['TRON_API_KEY']); 
        $TRXbalance = $tron->getTrxBalance($address) / 1000000; 
         
        
        if($TRXbalance - 10 < $trx){ 
            #余额不足 自动在链上 sunwap 进行兑换  
            $Tipstext = " 
            \n应回trx：{$trx}\ntrx余额：{$TRXbalance}\nTRX余额不足,请求链上闪兑\n\n对方地址：<code>{$data['from']}</code>"; 
            echo $Tipstext;
            
            $queueData['type'] = "url"; 
            $queueData['url']= $BOT['API_URL'].$BOT['API_TOKEN']."/sendMessage?chat_id={$BOT['Admin']}&text=未完成的订单提示：{$Tipstext}";
            Client::send('TG_queue',$queueData);  
            
            
            if($TRXbalance < 25 ){
                Db::name('bot_usdt_list')->where("txid",$data['txid'])->update(['msg'=>"闪兑终止,trx不足"]); 
                $Tipstext = "闪兑任务终止\ntrx余额低于：25\n无法保证链上闪兑成功\n请核查钱包手动回该笔订单TRX
                \n应回TRX数量：{$trx}
                \n对方地址：<code>{$data['from']}</code>"; 
                $queueData['type'] = "url"; 
                $queueData['url']= $BOT['API_URL'].$BOT['API_TOKEN']."/sendMessage?chat_id={$BOT['Admin']}&text={$Tipstext}";
                Client::send('TG_queue',$queueData);  
                //throw new \Exception("\ntrx余额太少·无法保证链上闪兑成功·终止操作"); 
                return;    
            } 
             
            $TRC20 = $tron->Trc20('TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t');//合约地址 - USDT合约
            $USDTbalance = $TRC20->balanceOf($address)->__toString() / 1000000; 
            echo "\nUSDT余额：" . $USDTbalance;
         
            if($data['value'] / 1000000 + $setup['maxusdt'] > $USDTbalance){//用户转账usdt + 设置U 大于钱包余额 ，则余额全部兑换为trx
               $USDTbalance = floor($USDTbalance); 
            }else{
               $USDTbalance = floor($data['value'] / 1000000  + $setup['maxusdt']);//否则闪兑usdt额度为：用户转账usdt + 最大设置U
            }
            
            
            $Sunwap = $tron->Trc20('TQn9Y2khEsLJW1ChVWFMSMeRDow5KcbLSE');//合约地址
            $amount = $USDTbalance*1000000;
            $ret = $Sunwap->SwapUsdtTrx($amount,$amount*$price,time() + 60); 
            if(empty($ret->result)){ 
                Db::name('bot_usdt_list')->where("txid",$data['txid'])->update(['oktxid'=>$ret->tx->txID,'msg'=>"链上闪兑失败"]);
                echo  "\n\nresult => 链上闪兑trx失败,请检查hahs详情交易哈希：".$ret->tx->txID."\n\n"; 
                $Tipstext = "警报 警报 急速处理..\n链上闪兑trx失败\n请检查交易哈希：\n<code>".$ret->tx->txID."</code>"; 
                $queueData['type'] = "url"; 
                $queueData['url']= $BOT['API_URL'].$BOT['API_TOKEN']."/sendMessage?chat_id={$BOT['Admin']}&text={$Tipstext}";
                Client::send('TG_queue',$queueData);  
                return;  
            } 
            throw new \Exception("链上闪兑完成,稍后将重试该笔订单");   
        }
        
        
        #进行转账 回TRX  
		 $zret = $tron->sendTrx($data['from'],$trx * 1000000);   
		 if(empty($zret->result) || empty($zret->txid)){
		     $txid = $zret->txid ?? "no";
		     Db::name('bot_usdt_list')->where("txid",$data['txid'])->update(['msg'=>"回trx失败",'okzt'=>2,"oktxid"=>$txid]);//0收到 1已转TRX 2失败 3成功
		     throw new \Exception('回币失败·请检查txid数据·稍后重试');
         }else{
             Db::name('bot_usdt_list')->where("txid",$data['txid'])->update(['msg'=>"回币中..",'oktrx'=>$trx,'okzt'=>1,"oktxid"=>$zret->txid]);
         } 
         
         echo "\n\033[1;33m向{$data['from']}转账TRX：{$trx} 完成,监听结果中..\033[0m\n";  
         
         Redis::hset("OK_".$data['bot'],$zret->txid,time());
         Redis::EXPIRE("OK_".$data['bot'],$setup['Ttime']); 
          
 
         return true;      
    }
}