<?php

namespace app\queue\redis;

 
use Webman\RedisQueue\Consumer;
use Exception;
use think\facade\Db;
use support\Redis;//redis缓存
use Webman\Push\Api; //push推送 
use Hashids\Hashids; #ID加解密

use GatewayWorker\Lib\Gateway;


#代付手机APP 订单推送
    
class daifu implements Consumer
{
    // 要消费的队列名
    public $queue = 'send_daifu';

    // 连接名，对应 config/redis_queue.php 里的连接`
    public $connection = 'default';

    // 消费
    public function consume($data){   
        Gateway::$registerAddress = '127.0.0.1:8236';
        if(Gateway::isUidOnline($data['upid'])){
            Gateway::sendToUid($data['upid'], $data['tmsg']."\r\n");  
        }else{
            echo $data['upid']."不在线,稍后再次推送通知\r\n";
            throw new \Exception(); //故意抛出异常
        } 
        //$push = new Api('http://127.0.0.1:3012',config('plugin.webman.push.app.app_key'),config('plugin.webman.push.app.app_secret'));
        //$push->trigger('user-'.$data['myid'], 'newdd', $data);
        // if(!empty($data['upid'])){
        //     $push->trigger('private-user-'.$data['upid'], 'newdd', $data); 
        // }
    }
}