<?php
namespace app\queue\redis;
 
use Webman\RedisQueue\Consumer;
use Exception;
use think\facade\Db;
use support\Redis;//redis缓存
use Webman\Push\Api; //push推送 

use GatewayWorker\Lib\Gateway;

#前端顶部消息推送
 
    
class notice implements Consumer{ 
    public $queue = 'send_notice';// 要消费的队列名 
    public $connection = 'default';// 连接名，对应 config/redis_queue.php 里的连接`

    #消费
    public function consume($data){ 
        $push = new Api('http://127.0.0.1:3012',config('plugin.webman.push.app.app_key'),config('plugin.webman.push.app.app_secret'));
        $Msga = ['class' => '','icon'  => 'el-icon-star-on','title'  => $data['msg'],'time'  => date("m-d H:i:s")];
        $push->trigger("user-{$data['uid']}",'notice',$Msga); 
    }
    
}