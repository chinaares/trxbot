<?php
namespace plugin\SwapTRX8bot\app\controller;


use support\Redis;//redis缓存
use Webman\RedisQueue\Client; #redis queue 队列 
use think\facade\Db;#mysql https://www.kancloud.cn/manual/think-orm/1258003
use support\Request;

class group_power extends Base{
    
    public function index($message){  
        // var_dump($message);
        $chatType = $message['chat']['type'];
        $chatId = $message['chat']['id'];
        $type = $message['new_chat_member']['status']; 
        if($chatType == "group"){
            $chatType = "supergroup";  
        } 
        
        // if(){}
        
        if(empty($type)){
            echo "power阻断 没有type";   
            return; 
        } 
        
         if($chatType == "private"){//私人
            #停用屏蔽
            if($type == 'kicked'){
                 Db::name('account_tg')->where("bot",$this->BOT['API_BOT'])->where('tgid', $chatId)->update(['del'=>1]);  
                 return;
            }
             
            
        }else if($chatType == "supergroup"){//群组  
            $bot = $message['new_chat_member']['user']['username'];
            if($bot != $this->BOT["API_BOT"]){
                return;
            }
             
        
        
            #退出群 被踢出群消息
            if($type == 'left' || $type == 'kicked'){ 
                 Db::name('bot_group')->where("bot",$this->BOT["API_BOT"])->where("groupid",$chatId)->update(['del'=>1,'admin'=>0,'send'=>0]); 
                 return true;
            } 
            
            // $model  =  new \plugin\keepbot\app\controller\Template;
            // $model = $model->qunadmin($chatId); 
            
            #通用的群列表 数据增加 
            $bot_group = Db::name('bot_group')->where("bot",$this->BOT["API_BOT"])->where('groupid', $chatId)->find();
            if(empty($bot_group)){
                $sql['bot'] = $this->BOT["API_BOT"];  
                $sql['plugin'] = $this->plugin; 
                $sql['groupid'] = $chatId;
                $sql['grouptitle'] = $message['chat']['title'];
                $sql['groupname'] = $message['chat']['username'] ?? "私密"; 
                $sql['time'] = time();  
                Db::name('bot_group')->save($sql);    
            } 
            
            
            if(empty($bot_group) || !empty($bot_group['del'])){//为空 或 del=1 都发送该消息    
                $text = ""; //固定默认消息
                
                $Temppath = "\plugin\\{$this->plugin}\app\controller\Template";
                $Template  =  new $Temppath;
                $Template = $Template->reply_markup("机器人进群",$chatType); 
                if(empty($Template['reply_markup'])){   
                    $Template['reply_markup'] = json_encode([
                    "inline_keyboard"=>[   
                        [["text"=>'☎️私聊机器人',"url"=>"https://t.me/{$this->BOT['API_BOT']}"],["text"=>'📜查看说明',"url"=>"https://t.me/{$this->BOT['API_BOT']}?start=help"]]
                        ]
                    ]); 
                }   
                if(empty($Template['text'])){
                     $Template['text'] ="
                     加入群组:<b>{$message['chat']['title']}</b>
                     1.发送钱包地址可以查询钱包余额情况
                     2.发送交易哈希可以查看交易状态详情
                     3.给本机器人转USDT可以自动回TRX
                     ";
                } 
                $this->send("/sendMessage?chat_id={$chatId}&text={$Template['text']}&reply_markup={$Template['reply_markup']}","url",null,2);      
                
            }
            
                #发送回复按钮
                // $text = "
                // \n智能服务 
                // ";
                // $reply_markupH = json_encode([ 
                // "resize_keyboard"=>true, 
                // "one_time_keyboard"=>false, 
                // "keyboard"=>[  
                //     [ 
                //      ["text"=>'💹实时汇率'],
                //      ["text"=>'🔰兑换地址'],
                //     ],
                //     [
                //         ["text"=>'🌐绑定地址'],
                //         ["text"=>'🆘预支TRX']   
                //     ] 
                    
                //     ] 
                // ]); 
                // $this->send("/sendMessage?chat_id={$message['chat']['id']}&text={$text}&reply_markup={$reply_markupH}&disable_notification=true&protect_content=true");
            
             
     
             
            switch ($type) {
                default: 
                    break; 
                    
                case 'member':
                    Db::name('bot_group')->where("bot",$this->BOT["API_BOT"])->where("groupid",$chatId)->update(['del'=>0,'admin'=>0]);              
                    break;
                    
                case 'administrator':
                    $Temppath = "\plugin\\{$this->plugin}\app\controller\Template";
                    $Template  =  new $Temppath;
                    $Template = $Template->reply_markup("成为管理员",$chatType); 
                    if($Template['code']){
                         $this->send("/sendMessage?chat_id={$message['chat']['id']}&text={$Template['text']}&reply_markup={$Template['reply_markup']}"); 
                    }
                    
                    Db::name('bot_group')->where("bot",$this->BOT["API_BOT"])->where("groupid",$chatId)->update(['del'=>0,'admin'=>1]);   
                    break;
            }
            
        } 
        
    }
    
}