<?php 
namespace app\bootstrap;

use Webman\Bootstrap;
use think\facade\Db;
use support\Redis;//redis缓存
use think\facade\Cache;
use Webman\RedisQueue\Client;
use GuzzleHttp\Client as Guzz_Client;

class Loading implements Bootstrap{
    public static function start($worker){
        // Is it console environment ?
        $is_console = !$worker;
        if ($is_console) {
            // 其它命令行不执行该函数. 
            return;
        } 
        
        switch ($worker->name) { 
            default:
                // code...
                break;
            case 'monitor':    
                #CURL证书检查
                $crt = "/etc/ssl/certs/ca-certificates.crt";
                if(!is_file($crt)){
                    if(is_file("/etc/ssl/certs/ca-bundle.crt")){
                        shell_exec("cp /etc/ssl/certs/ca-bundle.crt /etc/ssl/certs/ca-certificates.crt"); 
                    }else if(is_file("/etc/ssl/certs/ca-bundle.trust.crt")){
                        shell_exec("cp /etc/ssl/certs/ca-bundle.trust.crt /etc/ssl/certs/ca-certificates.crt"); 
                    }
                    // $client = new Guzz_Client(['timeout' => 5,'http_errors' => false,'verify' => false]); 
                    // $res =$client->request('GET', "https://raw.githubusercontent.com/bagder/ca-bundle/master/ca-bundle.crt")->getBody(); 
                    // file_put_contents ($crt,$res);   
                }  
                
                if(strlen(getenv('TRONSCAN_APIKEY')) <30){
                    echo "\033[0;31m请在.env配置文件中配置正确的：\033[0m\033[0;32m[TRONSCAN_APIKEY]\033[0m\n";
                } 
            
            
                $lock = run_path() . DIRECTORY_SEPARATOR . 'runtime/ins_trxbot.lock'; 
                if(!is_file($lock)){
                    echo "\033[33;41m提示：TRX兑换机器人【未配置】点击下方地址进行配置 \033[0m\n";  
                    echo "\033[0;32mhttp://".localIP().":8686/app/install/trxbot\033[0m\n\n"; 
                } 
                
                
                #检查监听usdt任务是否启动
                $plugin = json_encode(array_keys(config('plugin'))); 
                if(preg_match('/SwapTRX8bot/', $plugin, $return)){
                    $task =  Db::table('sys_crontab')->whereIn('id',"2,3,4")->where('status',1)->count();
                    if($task != 3 ){ 
                        Db::table('sys_crontab')->whereIn('id',"2,3,4")->update(['status' => 1]);   
                        echo "\033[0;31m系统任务检查异常：\033[0m\033[0;32m[已修复]\033[0m\n";
                        echo "\033[33;41m提示：看到此消息·请重新启动项目 \033[0m\n\n"; 
                        //exit();
                        //shell_exec("docker-compose stop telegbot"); 
                        //shell_exec("kill `lsof -i tcp:8686 | awk '{print $2}'`"); 
                    }
                }
                
                
                
                #获取TRX价格 
                $client = new Guzz_Client(['timeout' => 5,'http_errors' => false,'verify' => false]);
                try {
                    $res = json_decode($client->request('GET', "https://openapi.sun.io/v2/allpairs?page_size=1&page_num=0&token_address=TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t&orderBy=price")->getBody()->getContents(),true); 
                    if(!empty($res['data']['0_TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t'])){
                        $price = round($res['data']['0_TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t']['price'],2); 
                        Redis::SETEX("TRXprice",600,$price);//trx实时价格 过期时间 redis
                        echo "TRX实时价格获取成功：\033[1;32m{$price}\033[0m\n";  
                    }
                } catch (\Throwable $e) {   
                    echo "\033[33;41m异常：获取TRX实时价格失败 \033[0m\n"; 
                    echo "\033[0;31m请确认服务器能访问：\033[0m";
                    echo "\033[0;32mhttps://openapi.sun.io/v2/allpairs?page_size=1&page_num=0&token_address=TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t&orderBy=price\033[0m\n\n";
                }
                
                
                
 
                
                
                
                break; 
        } 


    }

}
