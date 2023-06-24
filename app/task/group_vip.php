<?php

namespace app\task;
use support\Redis;//redis缓存
use Webman\RedisQueue\Client; #redis queue 队列
use support\Log;//日志
use think\facade\Db;#mysql https://www.kancloud.cn/manual/think-orm/1258003
use think\facade\Cache;#Cache https://www.kancloud.cn/manual/thinkphp6_0/1037634
 

use GuzzleHttp\Pool;
use GuzzleHttp\Client as Guzz_Client;
use GuzzleHttp\Psr7\Request as Guzz_Request; 
use GuzzleHttp\Promise as Guzz_Promise;

class group_vip{
    public function  execute(): string{  
            $so =[];   
            array_push($so,"del");
            array_push($so,'=');
            array_push($so,0); 
            
            array_push($so,"vip");
            array_push($so,'=');
            array_push($so,0); 
            
            $so = array_chunk($so,3);//拆分 
            $list = Db::name('bot_group')->where([$so])->select();    
            $promises = [];
            $time = time(); 
            
            $client = new Guzz_Client(['timeout' => 8,'http_errors' => false]);  
            foreach ($list as $val) {  
               $bot = Db::name('bot_list')->where("plugin",$val['plugin'])->find();  
               if(empty($bot)){  
                   continue;
               }  
               if($val['time'] + 86400 > $time ){
                   $text="本群未被列入白名单\n机器人将在<b>24小时内</b>退群\n\n入群时间：".date("Y-m-d H:i:s",$val['time'])."\n\n<b>请联系管理员加入白名单</b>";  
               }else {
                   $text="本群未被列入白名单\n机器人将在<b>1分钟后退出</b>该群\n\n寒江孤影,江湖故人,相逢何必曾相识,我们有缘江湖再见 baby!"; 
                   $queueData['type'] = "url";
                   $queueData['url'] = "{$bot['API_URL']}{$bot['API_TOKEN']}/leaveChat?chat_id=".$val['groupid']; 
                   Client::send('TG_queue',$queueData,60);
               } 
              $promises["key".$val['groupid']] = $client->getAsync("{$bot['API_URL']}{$bot['API_TOKEN']}/sendMessage?chat_id={$val['groupid']}&text={$text}&parse_mode=HTML");   
            } 
            $results = Guzz_Promise\unwrap($promises);//并发异步请求 
            return "ok"; 

    }
    
}