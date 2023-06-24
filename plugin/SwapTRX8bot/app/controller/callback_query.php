<?php
namespace plugin\SwapTRX8bot\app\controller;

use Webman\RedisQueue\Client; #redis queue 队列
use think\facade\Db;#mysql https://www.kancloud.cn/manual/think-orm/1258003
use think\facade\Cache;#Cache https://www.kancloud.cn/manual/thinkphp6_0/1037634
use support\Redis;//redis缓存
use Hashids\Hashids; //数字加密 
// use TNTma\TronWeb\Address;
// use TNTma\TronWeb\Account;
// use TNTma\TronWeb\Tron;

use GuzzleHttp\Pool;
use GuzzleHttp\Client as Guzz_Client;
use GuzzleHttp\Psr7\Request as Guzz_Request; 
use GuzzleHttp\Promise as Guzz_Promise;

class callback_query extends Base{
    
    public function index($message){   
        $type = $message['data'];
         
        
        
        switch ($type) {
            default: 
                break;
                
                
                
                
            case 'NotifyMsg':   
                
                $res =$this->get("/getChatMember?chat_id={$message['message']['chat']['id']}&user_id={$message['from']['id']}");   
                if(!empty($res['status'])){ 
                    
                    if($res['status'] == "administrator" || $res['status'] == "creator"){  
                        $reply_markup = json_encode([
                            "inline_keyboard"=>[   
                                [["text"=>'❌关闭兑换消息通知',"callback_data"=>"CloseNotifyMsg"]],
                                [["text"=>'🔍试一试查询钱包',"switch_inline_query_current_chat"=>""]] 
                                ]
                        ]); 
                        echo '发消息1';
                         Db::name('bot_group')->where("groupid",$message['message']['chat']['id'])->where("bot",$this->BOT['API_BOT'])->update(['del'=>0,'send'=>1]);
                         echo '发消息2';
                         $this->send("/editMessageText?chat_id={$message['message']['chat']['id']}&message_id={$message['message']['message_id']}&text={$message['message']['text']}&reply_markup={$reply_markup}"); 
                         $this->send("/answerCallbackQuery?callback_query_id={$message['id']}&text=已允许兑换消息通知&show_alert=1");
                         echo "/answerCallbackQuery?callback_query_id={$message['id']}&text=已允许兑换消息通知&show_alert=1";
                    }else{
                        $this->send("/answerCallbackQuery?callback_query_id={$message['id']}&text=您无权操作&show_alert=1");
                    }
                }
                break;
                
                
            case 'CloseNotifyMsg':  
                $res =$this->get("/getChatMember?chat_id={$message['message']['chat']['id']}&user_id={$message['from']['id']}");
                if(!empty($res['status'])){
                    if($res['status'] == "administrator" || $res['status'] == "creator"){ 
                        $reply_markup = json_encode([
                            "inline_keyboard"=>[   
                                [["text"=>'✅接收兑换消息通知',"callback_data"=>"NotifyMsg"]],
                                [["text"=>'🔍试一试查询钱包',"switch_inline_query_current_chat"=>""]] 
                                ]
                        ]);
                        Db::name('bot_group')->where("groupid",$message['message']['chat']['id'])->where("bot",$this->BOT['API_BOT'])->update(['del'=>0,'send'=>0]);
                        $this->send("/editMessageText?chat_id={$message['message']['chat']['id']}&message_id={$message['message']['message_id']}&text={$message['message']['text']}&reply_markup={$reply_markup}");  
                        $this->send("/answerCallbackQuery?callback_query_id={$message['id']}&text=已禁止兑换消息通知&show_alert=1");
                    }else{
                        $this->send("/answerCallbackQuery?callback_query_id={$message['id']}&text=您无权操作&show_alert=1");
                    }
                }
                break;
                
            
            case '推广链接':
                $hashid = new Hashids();
                $user = Db::name('account_tg')->where('del', 0)->where('bot', $this->BOT['API_BOT'])->where('tgid', $message['from']['id'])->find();
                if(empty($user)){
                    $this->send("/answerCallbackQuery?callback_query_id={$message['id']}&text=请先关注启用机器人&show_alert=1"); 
                    break;  
                } 
                $hid = $hashid->encode($user['id']); 
                $text = " 
                你的邀请链接: \n<code>https://t.me/{$this->BOT['API_BOT']}?start={$hid}</code>
                \n<b>点击以上地址自动复制</b>\n邀请他人使用本机器人兑换TRX,你将获得分成（当然您会收到详细的分成数量通知!）";
                $this->send("/sendMessage?chat_id={$message['message']['chat']['id']}&text={$text}&disable_web_page_preview=true&reply_to_message_id={$message['message']['message_id']}");  
                $this->send("/answerCallbackQuery?callback_query_id={$message['id']}&text=获取推广链接成功&show_alert=0");
                
            
             
        }
        
        
        
    }
    
    
}
