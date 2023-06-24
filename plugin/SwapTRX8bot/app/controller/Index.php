<?php

namespace plugin\SwapTRX8bot\app\controller;

use support\Request;
use support\Response;
use Webman\RedisQueue\Client; #redis queue 队列
use TNTma\TronWeb\Address;
use TNTma\TronWeb\Account;
use TNTma\TronWeb\Tron;

use GuzzleHttp\Pool;
use GuzzleHttp\Client as Guzz_Client;
use GuzzleHttp\Psr7\Request as Guzz_Request; 

use think\facade\Db;#mysql https://www.kancloud.cn/manual/think-orm/1258003
use think\facade\Cache;#Cache https://www.kancloud.cn/manual/thinkphp6_0/1037634
use support\Log;//日志
use app\model;

class Index {
    
    public $long = 0; //0远程 1本地
    

    public function index(Request $request){ 
        $path = $request->path();
        if(substr($path, -1) != '/'){
            return redirect($path.'/');   
        } 
        return response()->file(base_path() . '/plugin/'.$request->plugin.'/public/index.html');
        
    } 
    
    public function hook(Request $request){  
        return json(['code' => 1,  'msg' => "ok"]);
    }
    
    public function getMe(Request $request){  
        $data = $request->post(); //token
        if(empty($data['token']) ){
            return json(['code' => 0,  'msg' => "token不能为空"]); 
        }
        if(is_numeric(substr($data['token'],0,2)) ==false ){
            return json(['code' => 0,  'msg' => "请输入正确的机器人API TOKEN"]); 
        }
        if(strlen($data['token']) < 32){
            return json(['code' => 0,  'msg' => "机器人API TOKEN 错误"]); 
        } 
        $client = new Guzz_Client(['timeout' => 5,'http_errors' => false,'verify' => false]);    
        try { 
            if($this->long === 1){//本地
                $res =json_decode($client->request('GET', "https://api.telegram.org/bot{$data['token']}/logOut")->getBody()->getContents(),true);  
                if($res['ok'] == false && $res['description'] != "Logged out"){
                    return json(['code' => 0,  'msg' => "请确保服务器能访问电报官网!"]);
                }   
                $res =json_decode($client->request('GET', "http://127.0.0.1:3021/bot{$data['token']}/getMe")->getBody()->getContents(),true);  
                if(empty($res['result']['username']) || $res['ok'] != true){
                    return json(['code' => 0,  'msg' => "获取机器人用户名失败"]);
                }
                
                return json(['code' => 1,  'msg' => "获取成功" ,"data"=>$res['result']['username']]);  
                
                
                
            }else{//远程
                
                $res =json_decode($client->request('GET', "https://api.telegram.org/bot{$data['token']}/getMe")->getBody()->getContents(),true);  
                if(empty($res['result']['username']) || $res['ok'] != true){
                    return json(['code' => 0,  'msg' => "获取机器人用户名失败,请确保服务器能访问电报官网"]);
                } 
                $bot_list = Db::name('bot_list')->where("API_BOT",$res['result']['username'])->find();
                if(!empty($bot_list)){
                    if($bot_list['API_URL'] == 'http://127.0.0.1:3021/bot'){
                        $client->request('GET', "http://127.0.0.1:3021/bot{$data['token']}/deleteWebhook?drop_pending_updates=true")->getBody()->getContents(); 
                        $client->request('GET', "http://127.0.0.1:3021/bot{$data['token']}/close")->getBody()->getContents();  
                    }     
                }
                return json(['code' => 1,  'msg' => "获取成功" ,"data"=>$res['result']['username']]);     
                
            } 
             
             
             
        } catch (\Throwable $e) { 
             return json(['code' => 0,  'msg' => $e->getMessage()]);   
        } 
    }
    
    
    
    
    public function installtg(Request $request){  
        $data = $request->post();  
        $plugin = $request->plugin; 
        $info['tgname'] = "未设置姓名";
        if(empty($data['Admin'])){
            return json(['code' => 0,  'msg' => "缺少管理员ID"]); 
        } 
        
        if(is_numeric($data['Admin']) == false){
            return json(['code' => 0,  'msg' => "管理员飞机ID：必须为数字"]); 
        }
        
        if(empty($this->long)){ 
            $apitg = "https://api.telegram.org/bot";
            
            if(empty($data['WEB_URL'])){
                return json(['code' => 0,  'msg' => "缺少域名"]); 
            }
            if(substr($data['WEB_URL'],0,5) != "https" ){
                return json(['code' => 0,  'msg' => "域名必须为:https:// (也就是开启SSL)"]); 
            }
            if(substr($data['WEB_URL'],-1) == "/" ){
                return json(['code' => 0,  'msg' => "域名结尾不用加 / "]); 
            } 
            try {
                $client = new Guzz_Client(['timeout' => 5,'http_errors' => false]);
                $res =json_decode($client->request('GET', $data['WEB_URL']."/app/{$plugin}/index/hook")->getBody()->getContents(),true); 
                
                if(empty($res['code'])){
                    return json(['code' => 0,  'msg' => "域名验证失败2!"]);
                } 
                
            } catch (\Throwable $e) {  
                 return json(['code' => 0,  'msg' => "域名验证失败2!<br>".$e->getMessage()]);
            }
        }else{
            $apitg = "http://127.0.0.1:3021/bot";
            
        }
        
        
        
        try {
            $client = new Guzz_Client(['timeout' => 10,'http_errors' => false]);
            $res =json_decode($client->request('GET', "{$apitg}{$data['API_TOKEN']}/getChat?chat_id={$data['Admin']}")->getBody()->getContents(),true); 
            
            if(empty($res['result']['type']) || $res['ok'] != true){
                return json(['code' => 0,  'msg' => "管理员飞机ID验证失败,请先关注机器人"]);
            }
            #ok
            if($res['result']['type'] != "private"){
                return json(['code' => 0,  'msg' => "管理员飞机ID验证失败,不是电报用户ID?"]); 
            }else{
                $namea = $res['result']['last_name'] ?? "";
                $nameb = $res['result']['first_name'] ?? "";
                $info['tgname'] = $namea.'-'.$nameb;
            } 
 
            #全部通过  初始化数据================================ 
            Cache::delete("{$plugin}_{$data['API_BOT']}"); //删除缓存
             
            
            #其它一些关于数据库的初始化信息
            $install = []; 
            $bot_list = Db::name('bot_list')->where("plugin",$plugin)->find();
            if(empty($bot_list)){
                $bot_list['plugin'] = $plugin;
                $bot_list['WEB_IP'] = localIP();
                $bot_list['API_BOT'] = $data['API_BOT'];
                $bot_list['WEB_URL'] = $data['WEB_URL'];
                $bot_list['API_URL'] = $apitg;
                $bot_list['API_TOKEN'] = $data['API_TOKEN'];
                $bot_list['Admin'] = $data['Admin'];     
                $bot_list = model\bot_list::create($bot_list);  
                
            }else{ 
                $data['plugin'] = $plugin;
                $bot_list['WEB_URL'] = $data['WEB_URL'];
                $data['WEB_IP'] = localIP(); 
                $data['API_URL'] = $apitg; 
                $bot_list = model\bot_list::update($data, ['id' => $bot_list['id']]);       
            } 
            
            $bot_commands = Db::name('bot_commands')->where('bot', $data['API_BOT'])->where('command', "菜单命令")->where('parentId',0)->where('type',0)->find();
            if(empty($bot_commands)){
                $bot_commands['bot'] = $data['API_BOT'];  
                $bot_commands['command'] = "菜单命令";  
                $bot_commands['parentId'] =0; 
                $bot_commands['type'] = 0; 
                $bot_commands["id"] = Db::name('bot_commands')->insertGetId($bot_commands);    
                #插入批量添加
                $commands['bot'] = $data['API_BOT'];  
                $commands['command'] = "消息事件";  
                $commands['parentId'] =0; 
                $commands['type'] = 0;  
                Db::name('bot_commands')->insert($commands);    
            }  
            
             
            
            $bot_commands2 = Db::name('bot_commands')->where('bot', $data['API_BOT'])->where('parentId', $bot_commands['id'])->where('command', "start")->find();   
            if(empty($bot_commands2)){//构建初始化命令  
                $start['bot'] = $data['API_BOT'];  
                $start['parentId'] =$bot_commands['id']; 
                $start['command'] = "start";   
                $start['description'] = "开始(私人聊天)";   
                $start['text'] = "";   
                $start['type'] = 1; 
                $start['chatType'] = "private"; 
                array_push($install,$start);
                
                $Temppath = "\plugin\\{$plugin}\app\controller\Template";
                $Template  =  new $Temppath;
                $Template = $Template->help();  
                $start['bot'] = $data['API_BOT'];  
                $start['parentId'] =$bot_commands['id']; 
                $start['command'] = "help";   
                $start['description'] = "使用说明";   
                $start['text'] = urldecode($Template['text']);   
                $start['type'] = 1; 
                $start['chatType'] = "private"; 
                array_push($install,$start);
                
                
                $start['bot'] = $data['API_BOT'];  
                $start['parentId'] =$bot_commands['id']; 
                $start['command'] = "start";   
                $start['description'] = "开始(群组聊天)";   
                $start['text'] = "";   
                $start['type'] = 1; 
                $start['chatType'] = "supergroup"; 
                array_push($install,$start);   
            }  
            
            
            if(count($install)>0){ 
                Db::name('bot_commands')->insertAll($install); 
            }
             
            #队列进行 start 私人
            $queueData['plugin'] = $plugin;
            $queueData['API_BOT'] = $data['API_BOT'];
            $queueData['type'] = "部署用户小程序";  
            Client::send('TG_queue',$queueData,3);
            
            
            #队列进行 webhook
            $queueData['plugin'] = $plugin;
            $queueData['API_BOT'] = $data['API_BOT'];
            $queueData['type'] = "webhook"; 
            $queueData['long'] = $this->long;
            Client::send('TG_queue',$queueData,1);  
            
            #队列进行 start 私人
            $queueData['plugin'] = $plugin;
            $queueData['API_BOT'] = $data['API_BOT'];
            $queueData['type'] = "commands"; 
            $queueData['data'] = "private"; 
            Client::send('TG_queue',$queueData,3);
            
            #队列进行 start 群组
            $queueData['plugin'] = $plugin;
            $queueData['API_BOT'] = $data['API_BOT'];
            $queueData['type'] = "commands"; 
            $queueData['data'] = "supergroup"; 
            Client::send('TG_queue',$queueData,5);
            
            
            
            return json(['code' => 1,  'msg' => "初始化完成","info"=>$info]);
            
            
        } catch (\Throwable $e) { 
             #return json(['code' => 0,  'msg' => $e->getMessage()]);  
             return json(['code' => 0,  'msg' => "操作失败!<br>".$e->getMessage()]);
        }
        
        
        
        return json(['code' =>0,  'msg' => "未知错误"]);
    }
    
    
    
    
    public function installtrc(Request $request){
        $data = $request->post();     
        $plugin = $request->plugin;
            
        if(strlen($data['PrivateKey']) != 64){
            return json(['code' => 0,  'msg' => "PrivateKey 请正确填写钱包密钥","find"=>"PrivateKey"]);  
        }
        
        if($data['Ttime'] < 600 || $data['Ttime'] > 3600   ){
            return json(['code' => 0,  'msg' => "监听阈值 只能为600 - 3600区间","find"=>"Ttime"]);  
        }
        
        if($data['maxusdt'] < 10){
            return json(['code' => 0,  'msg' => "买TRX上限U需要大于10","find"=>"maxusdt"]);  
          $isno += 1;   
        }
        
        if($data['Rate'] < 5 || $data['Rate'] > 30   ){
            return json(['code' => 0,  'msg' => "抽成比例建议:5 - 30%","find"=>"Rate"]);  
        }
        
        if($data['Minusdt'] < 1  ){
            return json(['code' => 0,  'msg' => "最低兑换U不能小于1","find"=>"Rate"]);  
        }
        
        $bot = Db::name('bot_list')->where("plugin",$plugin)->find();
        if(empty($bot)){
            return json(['code' => 0,  'msg' => "获取机器人数据失败,请返回上一步重新设置"]);      
        } 
        
        $setup = Db::name('trx_setup')->where("plugin",$plugin)->where("bot",$bot['API_BOT'])->find();
        if(empty($setup)){
            $setup['plugin'] = $plugin; 
            $setup['bot'] = $bot['API_BOT'];
            $setup['PrivateKey'] = $data['PrivateKey'];
            $setup['TRON_API_KEY'] = $data['TRON_API_KEY'];
            $setup['Ttime'] = $data['Ttime'];
            $setup['maxusdt'] = $data['maxusdt'];         
            $setup['Rate'] = $data['Rate'];       
            $setup['Minusdt'] = $data['Minusdt'];     
            $setup['fanli'] = 5;     
            $setup['type'] = 1;   //实时币价  
            Db::name('trx_setup')->insert($setup); 
            
        }else{  
            $setup = model\trx_setup::update($data, ['id' => $setup['id']]);       
        }  
        Cache::delete("trx_{$bot['API_BOT']}_setup"); //删除缓存
        $lock = run_path() . DIRECTORY_SEPARATOR . 'runtime/ins_trxbot.lock';
        file_put_contents ($lock,"机器人安装完成,删除可以重新部署安装");
        return json(['code' =>1,  'msg' => "ok"]);
    }  
     

}
