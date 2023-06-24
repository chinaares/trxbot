<?php
namespace app\tcp;

use think\facade\Db;#mysql https://www.kancloud.cn/manual/think-orm/1258003
use GatewayWorker\Lib\Gateway;
use support\Log;//日志 
use Workerman\Worker;

class Events
{
    public static function onWorkerStart($worker){
       //Log::info("服务启动");  
    }
    
    public static function onWorkerStop($businessWorker){
        //Log::info("服务停止"); 
    }
    
    public static function onWebSocketConnect($client_id, $data){//此回调只有gateway为websocket协议才有效
        #    Gateway::bindUid($client_id, "1234567");
         #var_export($data);
        // if (!isset($data['get']['token'])) {
        //      Gateway::closeClient($client_id);
        // }
        //客户端连接代码类似: var ws = new WebSocket('ws://127.0.0.1:7272/?token=kjxdvjkasfh');//服务端终端打印能拿到token
    }
    
    
    public static function onConnect($client_id){//连接   
            echo "用户连接：{$client_id}\r\n";
        
         
        
        // 向当前client_id发送数据 
        #Gateway::sendToClient($client_id, "TCP连接成功：$client_id\r\n");
        // 向所有人发送
        //Gateway::sendToAll("$client_id Login Ok\r\n");
    } 

  

    public static function onMessage($client_id, $message){//收到消息 
           #Gateway::sendToClient($client_id, "正常\r\n"); 
           
     
        $data = json_decode($message, true);//转换json
        if(empty($data['type'])){
            #Gateway::sendToAll("Server_Time:".date("Y-m-d H:i:s")." Message:".$message."\r\n");
            echo "缺少type：{$message}\r\n";
            #Gateway::closeClient($client_id);//不是json消息体·主动断开·也可以进行一些sign消息签名之类的验证
            return; 
        }  
        
        switch ($data['type']) {
            default: 
                echo "未识别的type：{$message}\r\n";
                break;
                
                
            case 'login': 
                if(!Worker::$daemonize){
                    echo "登陆消息：{$message}\r\n";
                }
                  
                $user = Db::name('account')->where('username', $data['username'])->find(); #获取用户数据·暂未进行鉴权
                if(empty($user)){
                    echo "登陆失败 账号鉴权失败：{$message}\r\n";   
                    Gateway::closeClient($client_id);//断开tcp连接
                    break;
                } 
                Gateway::bindUid($client_id, $user['id']);//绑定id 
                break;
                
                
            case 'ping':  
                if(!Worker::$daemonize){
                    echo "心跳消息：{$message}\r\n";
                }
                 
                break;
             
        } 
        return;
         
    
   // Gateway::bindUid($client_id, 123456);
    
    // echo "\nvar_dump \n"; 
     
    
    // echo "\nvar_export \n"; 
    //var_dump($message);
 
 
    
    
   // var_dump($message);
        
       // Gateway::sendToAll("hi");
        
      //  Gateway::sendToAll($client_id."_".$message);// 向所有人发送 
        //Gateway::sendToClient($client_id,$message);
        
        
      //  Gateway::sendToClient($client_id, ApacheMinaProtocol::encode("aoligei")); //给自己发送
    }

    public static function onClose($client_id){//用户断开连接
        echo "用户退出：{$client_id}\r\n"; 
    }
    

}
