<?php

namespace app\queue\redis;

 
use Webman\RedisQueue\Consumer;
use Exception;
use think\facade\Db;
use support\Redis;//redis缓存
use Webman\Push\Api; //push推送 
use Hashids\Hashids; #ID加解密


#支付 新订单时推送 
    
class newdd implements Consumer
{
    // 要消费的队列名
    public $queue = 'send_newdd';

    // 连接名，对应 config/redis_queue.php 里的连接`
    public $connection = 'default';

    // 消费
    public function consume($data){ 
        $push = new Api('http://127.0.0.1:3012',config('plugin.webman.push.app.app_key'),config('plugin.webman.push.app.app_secret'));
        $push->trigger('user-'.$data['myid'], 'newdd', $data);
        if(!empty($data['upid'])){
            $push->trigger('user-'.$data['upid'], 'newdd', $data); 
        }
    }
}