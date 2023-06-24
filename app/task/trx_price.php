<?php

namespace app\task;
use support\Redis;//redis缓存
use Webman\RedisQueue\Client; #redis queue 队列
use support\Log;//日志
use think\facade\Db;#mysql https://www.kancloud.cn/manual/think-orm/1258003
use think\facade\Cache;#Cache https://www.kancloud.cn/manual/thinkphp6_0/1037634

use TNTma\TronWeb\Address;
use TNTma\TronWeb\Account;
use TNTma\TronWeb\Tron;

use GuzzleHttp\Pool;
use GuzzleHttp\Client as Guzz_Client;
use GuzzleHttp\Psr7\Request as Guzz_Request; 
use GuzzleHttp\Promise as Guzz_Promise;

class trx_price{
    public function  execute(): string{ 
        $client = new Guzz_Client(['timeout' => 8,'http_errors' => false]);  
        $res = json_decode($client->request('GET', "https://openapi.sun.io/v2/allpairs?page_size=1&page_num=0&token_address=TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t&orderBy=price")->getBody()->getContents(),true);  
        if(!empty($res['data']['0_TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t'])){
            $price = round($res['data']['0_TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t']['price'],2);   
            Redis::SETEX("TRXprice",600,$price);//trx实时价格 过期时间 redis
            return "ok";
        }else{
            return "No";
        }     
        
        return "?";   
    }
}