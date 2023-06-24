<?php
namespace process;

use Workerman\Worker;
use Workerman\Timer;
use Workerman\Connection\AsyncTcpConnection;
use support\Db;

class TaskTest{

    public function onWorkerStart(){
        $con = new AsyncTcpConnection('ws://127.0.0.1:9503/events');
        $con->websocketPingInterval = 10;
        $con->num = 1;
        $con->onConnect = function(AsyncTcpConnection $con) {
            // if (empty($con->timer)) {
            //     //定时发送消息
            //     $con->num += 1; 
            //     $con->timer = Timer::add(10, function () use ($con) {
            //         $con->send($con->num);
            //     });
            // }
        }; 
        
        $con->onMessage = function(AsyncTcpConnection $con, $data) {
            $jsonData = json_decode($data,true);
            if(!empty($jsonData['result']['update']['_'])){
                if($jsonData['result']['update']['_'] == 'updateNewChannelMessage' && empty($jsonData['result']['update']['message']['from_id'])){
                    //echo "\n--------------------------------------\n频道ID：".$jsonData['result']['update']['message']['peer_id']['channel_id'];
                    $message = $jsonData['result']['update']['message']['message'];
                    $time = $jsonData['result']['update']['message']['date'];
                    echo "\n".$message;
                    echo "\n发布时间：".date("Y-m-d H:i:s",$time);  
                    
                    if(empty($message)){
                        return;
                    }
                     
                    
                    $del = '@aaaa @bbbb @cccc @dddd @eeee @gggg @hhhh @hwdb';//过滤用户名
                    $ok="";
                        if(preg_match_all("/@\w+/i", $message,$ret)){
                             foreach ($ret[0] as $val) { 
                                 if(!preg_match("/{$val}/i", $del)){
                                     $ok .= $val." ";   
                                 } 
                                 
                             }
                            echo "\n目标客户：{$ok}"; 
                            $pathTxt = run_path() . DIRECTORY_SEPARATOR . 'runtime/caiji.txt';
                            file_put_contents($pathTxt, "\n{$ok}", FILE_APPEND);   
                        }   
                        
                    echo "\n--------------------------------------\n";    
                }
            }
            
            //var_dump($data);
            //file_put_contents(__DIR__.DIRECTORY_SEPARATOR."work.txt", "\n{$data}", FILE_APPEND); 
        };
        
        $con->onClose = function(AsyncTcpConnection $con) { 
            echo "\n服务断开链接5s后重连";
            $con->reConnect(5);
        };
    
        $con->connect();
 
 
    }

}