<?php
namespace plugin\SwapTRX8bot\app\controller;

use Webman\RedisQueue\Client; #redis queue 队列
use think\facade\Db;#mysql https://www.kancloud.cn/manual/think-orm/1258003
use think\facade\Cache;#Cache https://www.kancloud.cn/manual/thinkphp6_0/1037634
use support\Redis;//redis缓存
use TNTma\TronWeb\Address;
use TNTma\TronWeb\Account;
use TNTma\TronWeb\Tron; 

use GuzzleHttp\Pool;
use GuzzleHttp\Client as Guzz_Client;
use GuzzleHttp\Psr7\Request as Guzz_Request; 
use GuzzleHttp\Promise as Guzz_Promise;

use plugin\SwapTRX8bot\app\controller\Command;

class Message extends Base{
    
    public function index($message , $type=null){   
        $chatType = $message['chat']['type'];
        $chatId = $message['chat']['id'];
        $userName = $message['from']['username']??"未设定"; 
        $tgTime = $message['date']; 
        $time = time();  
        // $datem = date("Ym",$time);
        // $dated = date("Ymd",$time); 
        if($chatType == "group"){
            $chatType = "supergroup";  
        }
        $type = "";
        $value = ""; 
         
        if(count($message) < 7 && substr($message['text'], 0,1) !="/"){ 
             
            if(preg_match('/^([zZ+-]{1})\s*([.0-9]+)\s*$/i', $message['text'], $return)){ 
                $type = strtolower($return[1]);
                $value = $return[2];  
                
            }else if(preg_match('/^T[\w]{33}$/i', $message['text'], $return)){ 
                $type = "查询地址";
                $value = $return[0]; 
                $cach = Redis::GET("{$value}_addr");
                if(!empty($cach)){  
                    $this->send("/sendMessage?chat_id={$message['chat']['id']}&reply_to_message_id={$message['message_id']}&text=".unserialize($cach));  
                    return true;
                } 
                
            }else if(preg_match('/^[0-9a-z]{64}$/', $message['text'], $return)){ 
                $type = "查询哈希";
                $value = $return[0]; 
            }else if(preg_match('/^@(\w+)$/', $message['text'], $return)){ 
                $type = "查tg信息";
                $value = $return[1]; 
            }else if(preg_match('/[\x{4e00}-\x{9fa5}]+[a-zA-Z]*/u', $message['text'], $return) && strlen($message['text']) < 20 ){ 
                $type = $return[0];  
                
            }else{ 
                echo '其它内容阻断';
                return true;  
            }
             
            
            #同类型type更替
            if($type == "币价"){
                $type = "z"; 
            }
             
               
           
            
        }else if(array_key_exists('entities',$message) && substr ($message['text'], 0,1) =="/" ){// 转到命令 Command  
            $Command = new Command();
            $Command->index($message); 
            return true;   
            
            
        }else if(array_key_exists('reply_to_message',$message) ){// 
                if(strlen($message['text']) < 20  ){  
                    $type = mb_substr($message['text'],1); 
                }
            
        } 
               
        
        switch ($type) {
            default: 
                echo "暂时不支持的消息type：{$type}";
                break;
                
            case '查tg信息'://这个功能是一个未完善的功能 查询任意指定TG用户的ID 也可以查任意群组 频道ID 不需要进别人群哦，删除92-106行  就去掉这个功能了
                $client = new Guzz_Client(['timeout' => 8,'http_errors' => false,'verify' => false]);
                $res = json_decode($client->request('GET', "https://api.telegbot.org/api/users/test001/getPwrChat/?id={$value}")->getBody()->getContents(),true); 
                
                var_dump($res);
                
                
                $text ="【<b>TG信息查询</b>】 
                     ID：{$res['response']['id']}
                     昵称：{$res['response']['first_name']}
                     简介：{$res['response']['about']}
                    ";
                    
                $this->send("/sendMessage?chat_id={$message['chat']['id']}&text={$text}"); 
                break;  
                
                
                
            case '查询哈希':   
                $data['bot'] = $this->BOT['API_BOT'];
                $data['chat'] = $message['chat'];
                $data['form'] = $message['from'];
                $data['message_id'] = $message['message_id'];
                $data['txid'] = $value;  
                $this->send(null,"查询哈希",$data);
                break;  
                
            case '查询地址':
                $data['bot'] = $this->BOT['API_BOT'];
                $data['admin'] = $this->BOT['Admin'];
                $data['chat'] = $message['chat'];
                $data['form'] = $message['from'];
                $data['message_id'] = $message['message_id'];
                $data['address'] = $value; 
                $this->send(null,"查询地址",$data); 
                break;    
                
            case '兑换汇率':    
                $client = new Guzz_Client(['timeout' => 8,'http_errors' => false]);
                $res = json_decode($client->request('GET', "https://openapi.sun.io/v2/allpairs?page_size=1&page_num=0&token_address=TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t&orderBy=price")->getBody()->getContents(),true); 
                if(!empty($res['data']['0_TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t'])){
                    $price = round($res['data']['0_TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t']['price'],2); 
                    Redis::SETEX("TRXprice",600,$price);//trx实时价格 过期时间 redis
                    $dec =  round($price * $this->setup['Rate'] / 100,2);
                    $price = $price -$dec;
                    $text ="【<b>兑换汇率 - 实时币价</b>】
                    \n<code>1   USDT = ".round($price,2)." TRX</code>
                    \n<code>10  USDT = ".round($price*10,2)." TRX</code>
                    \n<code>100 USDT = ".round($price*100,2)." TRX</code>
                    \n\n钱包地址(trc20)：\n<code>{$this->addr}</code>\n点击上面地址自动复制
                    ";
                    $this->send("/sendMessage?chat_id={$message['chat']['id']}&photo=https://telegra.ph/file/caa1f5ee9a712397b3ad9.jpg&text={$text}"); 
                     
                }
                
                
                break; 
                
            case '绑定地址':  
                $this->send("/sendMessage?chat_id={$message['chat']['id']}&photo=https://telegra.ph/file/caa1f5ee9a712397b3ad9.jpg&text=请私聊本机器人进行绑定"); 
                break;
                
            
            case '兑换地址':  
                $text ="【<b>兑换地址 - trc20</b>】
                \n <u><code>{$this->addr}</code></u>
                \n点击上面地址自动复制\n<b>请注意：\n不要使用交易所转账,无法兑换！ </b>
                "; 
                $this->send("/sendMessage?chat_id={$message['chat']['id']}&photo=https://telegra.ph/file/caa1f5ee9a712397b3ad9.jpg&text={$text}"); 
                
                break; 
                
                
                
            case '预支TRX': 
                $this->send("/sendMessage?chat_id={$message['chat']['id']}&photo=https://telegra.ph/file/caa1f5ee9a712397b3ad9.jpg&text=管理员关闭了预支功能"); 
                
                break; 
                
            case 'z':  
                $Temppath = "\plugin\\{$this->plugin}\app\controller\Template";
                $Template  =  new $Temppath;
                if(empty($value)){
                    $text = $Template = $Template->bijia("all");  
                }else if($value == 1){
                    $text = $Template = $Template->bijia("bank");  
                }else if($value == 2){
                    $text = $Template = $Template->bijia("aliPay");  
                }else if($value == 3){
                    $text = $Template = $Template->bijia("wxPay");  
                } 
                $inline_keyboard['inline_keyboard'] = []; 
                array_push($inline_keyboard['inline_keyboard'],$text['a11']);  
                $inline_keyboard = json_encode($inline_keyboard); 
                
                $this->send("/sendMessage?chat_id={$chatId}&text={$text['text']}&reply_to_message_id={$message['message_id']}&reply_markup={$inline_keyboard}"); 
                break;
                
                
                
            case '计算公式': 
                $this->send("/sendMessage?chat_id={$chatId}&text=<b>计算结果 = {$value}</b>&reply_to_message_id={$message['message_id']} "); 
                break;    
             
        }//switch end
        
 
        return true; 
    }//index end
    
}