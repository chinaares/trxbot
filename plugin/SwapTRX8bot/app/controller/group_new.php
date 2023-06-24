<?php
namespace plugin\SwapTRX8bot\app\controller;

use support\Redis;//redis缓存
use Webman\RedisQueue\Client; #redis queue 队列 
use think\facade\Db;#mysql https://www.kancloud.cn/manual/think-orm/1258003
use support\Request;

class group_new extends Base{
    
    public function index($message){ 
        //  echo "新入群\n\n";
        //  var_dump($message);
        //  echo " \n\n";
        
        // if($message['new_chat_participant']['is_bot'] == true && strtolower($message['new_chat_participant']['username']) == strtolower($bot)){
        //     $bot_group = Db::name('bot_group')->where('groupid', $message['chat']['id'])->find();
        //     if(empty($bot_group)){
        //         $sql['bot'] = $message['new_chat_participant']['username'];
        //         $sql['botid'] = $message['new_chat_participant']['id'];
        //         $sql['botname'] = $message['new_chat_participant']['first_name'];
        //         $sql['groupid'] = $message['chat']['id'];
        //         $sql['grouptitle'] = $message['chat']['title'];
        //         $sql['groupname'] = $message['chat']['username'] ?? "私密"; 
        //         $sql['time'] = time();  
        //         Db::name('bot_group')->save($sql);    
        //     }else{
        //         Db::name('bot_group')->where("id",$bot_group['id'])->update(['del'=>0]); 
        //     }
        //     #发送设置消息按钮,比如允许机器人发送群兑换消息
            
        //         $text = "
        //         加入群组:{$message['chat']['title']}
        //         \n大家好·我是:{$message['new_chat_participant']['first_name']}
        //         \n1.你们可以@我查询钱包余额\n2.也可以直接在群里发送钱包地址,哈希交易订单,小机机都可以帮您查询详细情况 
        //         ";
        //         $reply_markup = json_encode([
        //             "inline_keyboard"=>[   
        //                 [["text"=>'✅接收兑换消息通知',"callback_data"=>"NotifyMsg"]],
        //                 [["text"=>'🔍试一试查询钱包',"switch_inline_query_current_chat"=>""]] 
        //                 ]
        //         ]); 
        //         $this->send("/sendMessage?chat_id={$message['chat']['id']}&text={$text}&reply_markup={$reply_markup}"); 
             
        // }
        
    }
    
}