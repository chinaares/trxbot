<?php

namespace app\queue\redis;

 
use Webman\RedisQueue\Consumer;
use Exception;
use think\facade\Db;
use support\Redis;//redis缓存
use Webman\Push\Api; //push推送 
use Hashids\Hashids; #ID加解密

use GatewayWorker\Lib\Gateway;


#订单补发
    
class ddbf implements Consumer{ 
    public $queue = 'send_ddbf';// 要消费的队列名 
    public $connection = 'default';// 连接名，对应 config/redis_queue.php 里的连接`

    #消费
    public function consume($data){
        $notifytext = curl_post_https($data['notifyurl'],$data['form']); 
        if(empty($data['ddid'])){
            return 'url请求完成 ok';    
        }
        if(strpos($notifytext,"ucce") > 0){
            Db::name('pay_log')->where('id',$data['ddid'])->update(['notifyzt' => "1",'notifymsg'=>"成功"]);  
            echo $data['ddid'].":补发成功\r\n";
        }else{
            Db::name('pay_log')->where('id',$data['ddid'])->update(['notifymsg'=>"2次通知失败,稍后继续重试.."]);
            echo $data['ddid'].":补发失败,稍后重试\r\n";
            throw new \Exception(); //故意抛出异常
        }
        
        
    }
    
}