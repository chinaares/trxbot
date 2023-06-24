<?php
namespace plugin\SwapTRX8bot\app\controller;


use support\Redis;//redis缓存
use Webman\RedisQueue\Client; #redis queue 队列 
use think\facade\Db;#mysql https://www.kancloud.cn/manual/think-orm/1258003
use support\Request;

class group_exit extends Base{
    
    public function index($message){  
        $bot = $this->BOT['API_BOT'];
        $by = $this->BOT['Admin'];
        //      echo "退群\n\n";
        //  var_dump($message);
        //  echo "\n\n";
        if($message['left_chat_participant']['is_bot'] == true && strtolower($message['left_chat_participant']['username']) == strtolower($bot)){  
            Db::name('bot_group')->where("groupid",$message['chat']['id'])->update(['del'=>1,'send'=>0,'admin'=>0]);    
        }
        
        
    }
    
}