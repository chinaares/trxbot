<?php
namespace plugin\SwapTRX8bot\app\controller;

use support\Request;
use Webman\RedisQueue\Client; #redis queue 队列
use think\facade\Db;#mysql https://www.kancloud.cn/manual/think-orm/1258003
use think\facade\Cache;#Cache https://www.kancloud.cn/manual/thinkphp6_0/1037634
use support\Redis;//redis缓存
use TNTma\TronWeb\Address;
use TNTma\TronWeb\Account;
use TNTma\TronWeb\Tron; 
use Tntma\Tntjwt\Auth;
use Vectorface\GoogleAuthenticator;#谷歌验证

class Admin_api extends Base{ 
    
    public function host(Request $request){  
        return json(['code' =>1,  'msg' => "ok",'hosts'=>"http://".localIP().":8686/app/user"]);
    }
    
    
     public function paylist(Request $request){    
        $data = $request->get();
        $page = ($data['page']-1)*$data['limit'];  
        $so =[]; 
        array_push($so,"del");
        array_push($so,'=');
        array_push($so,0);  
        $so = array_chunk($so,3);//拆分   
        $count = Db::name('bot_usdt_list')->where([$so])->count(); 
        $list = Db::name('bot_usdt_list')->where([$so])->limit($page,$data['limit'])->order('id desc')->select();  
        
        return json(['code' => 1,'msg'=>"获取成功",'count'=>$count,'num'=>count($list),'data' => $list ]);  
         
     }
     
     public function setup(Request $request){      
        $data = $request->post(); 
        $bot  = Cache::get("@PonyYun"); 
        if(empty($bot)){  
          $bot =  Db::name('bot_list')->where("id",1)->find();  
          if($bot){  
              Cache::set("@PonyYun", $bot);
          } else{
              return json(['code' => 0,'msg'=>"未配置" ]);   
          } 
        }
        
        return json(['code' => 1,'msg'=>"获取成功",'data'=>$bot]);   
     }
     
     public function setupUpdate(Request $request){    
        $data = $request->post(); 
        if(empty($data['id'])){
            return json(['code' => 0,'msg'=>"缺少核心参数"]);   
        }  
        
        $bot_list = Db::name('bot_list')->where("id",1)->find(); 
        if(empty($bot_list)){
            return json(['code' => 0,'msg'=>"机器人数据不存在"]);  
        }
        unset($data['id']);  
        Db::name('bot_list')->where('id' ,$bot_list['id'])->update($data); 
        
        #更新管理员roleId - 同时为了兼容PC管理端，应在account表添加对应管理员数据 - 注意角色Id 租户ID是不一样的参考角色表
        Db::name('account_tg')->where('tgid', $data['Admin'])->update(["roleId"=>88]);
        
        
        #更新缓存
        $data['id'] = $bot_list['id'];
        Cache::set("@PonyYun", $data); 
        $BOT = Db::name('bot_list')->where("id",1)->select(); 
        Cache::set("TG_@PonyYun", $BOT); 
        $lock = run_path() . DIRECTORY_SEPARATOR . 'runtime/ins_bot.lock';
        file_put_contents ($lock,"机器人安装完成,删除可以重新部署安装");
        return json(['code' => 1,'msg'=>"修改成功"]);   
     }
     
     
     public function total_d_h(Request $request){   
        $so =[]; 
        array_push($so,"del");
        array_push($so,'=');
        array_push($so,0);  
        array_push($so,"bot");
        array_push($so,'=');
        array_push($so,$this->BOT['API_BOT']);  
        $so = array_chunk($so,3);//拆分   
        
        $time = time();
        $date = date("Ymd"); 
        $dateh =  date("YmdH",$time - 3600 * 6); 
        $total_d = Db::name('bot_total_d')->where([$so,['dated','=',$date]])->order('id desc')->find();  
        $list = Db::name('bot_total_h')->where([$so,['dateh','>',$dateh]])->order('id desc')->select();  //->limit(0,6)
        
        $list2= array_column($list->toArray(), null, 'dateh');//把dateh提为key
        
        $list3 = [];
        
        for ($i = 0; $i < 6; $i++) {
            $dateh =  date("YmdH",$time - 3600 * $i);
            if(empty($list2[$dateh])){
                 array_push($list3,['date'=>substr($dateh,-2).":00"]); 
            }else{
                $list2[$dateh]['date'] = substr($dateh,-2).":00";
                 array_push($list3,$list2[$dateh]); 
            }
            
        }
        
        return json(['code' => 1,'msg'=>"获取成功",'count'=>null,'num'=>count($list),'d'=>$total_d,'data2'=>$list3 ]);    
     }
     
     
     
     public function trc20_yue(Request $request){ 
         $PrivateKey = Account::SetPrivateKey($this->BOT['PrivateKey']);
         $address = $PrivateKey->address()->__toString();
         $tron = new Tron(1,$PrivateKey,$this->BOT['TRON_API_KEY']); 
         $TRXbalance = $tron->getTrxBalance($address) / 1000000; 
         
         $TRC20 = $tron->Trc20('TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t');//合约地址 - USDT合约
         $USDTbalance = $TRC20->balanceOf($address)->__toString() / 1000000; 
         
         return json(['code' => 1,'msg'=>"获取成功",'usdt'=>$USDTbalance,'trx'=>$TRXbalance]);     
     }
     
     
     
     public function trc20_disable(Request $request){   
        $data = $request->get();
        $page = ($data['page']-1)*$data['limit'];   
        $so =[]; 
        array_push($so,"del");
        array_push($so,'=');
        array_push($so,0);  
        array_push($so,"disable");
        array_push($so,'=');
        array_push($so,1);
        array_push($so,"bot");
        array_push($so,'=');
        array_push($so,$this->BOT['API_BOT']);   
        
        if(!empty($data["keyword"])){
            array_push($so,"trc20");
            array_push($so,'like');
            array_push($so,"%{$data["keyword"]}");   
        }
       
        $so = array_chunk($so,3);//拆分   
        $count = Db::name('bot_total_trc20')->where([$so])->count(); 
        $list = Db::name('bot_total_trc20')->where([$so])->limit($page,$data['limit'])->order('id desc')->select();  
        
        return json(['code' => 1,'msg'=>"获取成功",'count'=>$count,'num'=>count($list),'data' => $list ]); 
     }
     
     public function trc20_disable_add(Request $request){   
         $data = $request->post(); 
         if(empty($data['trc20']) || strlen($data['trc20']) != 34){
             return json(['code' => 0,'msg'=>"请输正确地址"]);  
         }else{
             
             
            $trc20 = Db::name('bot_total_trc20')->where("bot",$this->BOT['API_BOT'])->where("trc20",$data['trc20'])->find();  
            if(empty($trc20)){
               $gid =  Db::name('bot_total_trc20')->insertGetId(["trc20" => $data['trc20'],'disable'=>1,'time'=>time(),'bot'=>$this->BOT['API_BOT']]); 
            }else{
                Db::name('bot_total_trc20')->where("bot",$this->BOT['API_BOT'])->where('trc20' ,$data['trc20'])->update(['del'=>0,'disable'=>1]);  
               $gid = $trc20['id'];
            }   
         } 
         return json(['code' => 1,'msg'=>"添加黑名单成功",'gid'=>$gid]);
         
     }
     
     public function trc20_disable_del(Request $request){   
         $data = $request->post(); 
         if(empty($data['id'])){
             return json(['code' => 0,'msg'=>"地址不能为空"]);  
         }else{
             Db::name('bot_total_trc20')->where('id' ,$data['id'])->update(['del'=>0,'disable'=>0]);  
         } 
         return json(['code' => 1,'msg'=>"删除黑名单成功"]);  
          
     }
     
     
     
     public function trc20_disable_update(Request $request){   
         $data = $request->post(); 
         if(empty($data['id']) || strlen($data['trc20']) != 34){
             return json(['code' => 0,'msg'=>"请输正确地址"]);  
         }else{
             Db::name('bot_total_trc20')->where('id' ,$data['id'])->update(['trc20'=>$data['trc20']]);  
         } 
         return json(['code' => 1,'msg'=>"修改地址成功"]);  
          
     }
     
     
     
     
     
     
     
     
     public function group_list(Request $request){   
        $data = $request->get();
        $page = ($data['page']-1)*$data['limit'];   
        $so =[]; 
        array_push($so,"del");
        array_push($so,'=');
        array_push($so,0);  
        array_push($so,"bot");
        array_push($so,'=');
        array_push($so,$this->BOT['API_BOT']);
        
        // if(!empty($data["keyword"])){
        //     array_push($so,"trc20");
        //     array_push($so,'like');
        //     array_push($so,"%{$data["keyword"]}");   
        // }
       
        $so = array_chunk($so,3);//拆分   
        $count = Db::name('bot_group')->where([$so])->count(); 
        $list = Db::name('bot_group')->where([$so])->limit($page,$data['limit'])->order('id desc')->select();  
        
        return json(['code' => 1,'msg'=>"获取成功",'count'=>$count,'num'=>count($list),'data' => $list ]); 
     }
     
     
     public function group_off_on(Request $request){  
         $data['zt'] = 0;
         $data = $request->post(); 
         if(empty($data['id'])){
             return json(['code' => 0,'msg'=>"异常"]);  
         }else{
             Db::name('bot_group')->where('id' ,$data['id'])->update(['send'=>$data['zt']]);  
         } 
         return json(['code' => 1,'msg'=>$data['zt']?"开启通知":"关闭通知"]);  
          
     } 
     
     
     
     public function command_list(Request $request){   
        $data = $request->get();
        $page = ($data['page']-1)*$data['limit'];   
        $so =[]; 
        array_push($so,"del");
        array_push($so,'=');
        array_push($so,0);  
        array_push($so,"bot");
        array_push($so,'=');
        array_push($so,$this->BOT['API_BOT']);
        
        // if(!empty($data["keyword"])){
        //     array_push($so,"trc20");
        //     array_push($so,'like');
        //     array_push($so,"%{$data["keyword"]}");   
        // }
       
        $so = array_chunk($so,3);//拆分   
        $count = Db::name('bot_commands')->where([$so])->count(); 
        $list = Db::name('bot_commands')->where([$so])->limit($page,$data['limit'])->order('id desc')->select();  
        
        return json(['code' => 1,'msg'=>"获取成功",'count'=>$count,'num'=>count($list),'data' => $list ]); 
     }
     
     
     public function command_del(Request $request){   
         $data = $request->post(); 
         if(empty($data['id'])){
             return json(['code' => 0,'msg'=>"异常"]);  
         }else{
             Db::name('bot_commands')->where('id' ,$data['id'])->update(['del'=>1]);  
         } 
        $queueData['type'] = "commands";
        $queueData['bot'] = $this->BOT["API_BOT"];
        $queueData['url']=$this->BOT['API_URL'].$this->BOT['API_TOKEN']; 
        Client::send('BOTsend',$queueData); 
         return json(['code' => 1,'msg'=>"删除成功"]);  
          
     } 
     
     
     public function command_addupdate(Request $request){   
         $data = $request->post();  
         $dataID = null;
         $id=null;
         if(empty($data['command'])){
             return json(['code' => 0,'msg'=>"参数不正确"]);  
         }else{
             if(empty($data['id'])){ 
                 $data['bot'] = $this->BOT["API_BOT"]; 
                 $id = Db::name('bot_commands')->insertGetId($data);    
             }else{
                 $dataID = $data['id'];
                 unset($data['id']);  
                 Db::name('bot_commands')->where('id' ,$dataID)->update($data);           
             }  
             
            $queueData['type'] = "commands";
            $queueData['bot'] = $this->BOT["API_BOT"];
            $queueData['url']=$this->BOT['API_URL'].$this->BOT['API_TOKEN']; 
            Client::send('BOTsend',$queueData);  
            
             return json(['code' => 1,'msg'=>$dataID?"修改成功":"新增成功","getid"=>$id]); 
             
         } 
         
         
            
          
     } 
     
     public function QrScanLogin(Request $request){  
         $tgid = $request->user->tgid; 
         $data = $request->post();  
         if(empty($data['code'])){
              return json(['code' => 0,'msg'=>"登录失败"]);   
         } 
         
         $user = Db::name('account')->where('roleId', 6)->where('tenantId', 2)->where('tgid', $tgid)->find(); 
         if(empty($user)){
            $account_tg = Db::name('account_tg')->where('bot', $this->BOT['API_BOT'])->where('tgid', $tgid)->find(); 
            if(empty($account_tg['tgid'])){
                return json(['code' => 1,'msg'=>"tgid获取失败"]);  
            }
            $key = strtoupper(md5($request->sessionId().rand(1,999)));
            $ga = new GoogleAuthenticator();
            $secret = $ga->createSecret();#生成谷歌密匙
            $user['regtime'] = time();
            $user['upid'] = 0;
            $user['rate'] = 0;
            $user['key'] = $key; 
            $user['SecretKey'] = $secret;
            $user['roleId'] = 6;
            $user['tenantId'] = 2; 
            $user['username'] = $account_tg['tgid'];  
            $user['tgid'] = $account_tg['tgid'];  
            $user['id'] = Db::name('account')->insertGetId($user);
         } 
         
        $user['plugin'] = $request->plugin; //自定义附加内容
        $user['remark'] = $this->BOT['API_BOT']; //自定义附加内容
        $tokenObject = Auth::login($user); 
        $JWTuid = $user['id'];  
        $JWT_MD5 = $tokenObject->token_md5;
        Redis::HSET("HJWTMD5_{$JWTuid}",$JWT_MD5,time());
        redis::EXPIRE("HJWTMD5_{$JWTuid}",config('plugin.TNTma.tntjwt.app.exp'));//设置过期时间
        
         Redis::HSET("QRcode",$data['code'],serialize($tokenObject));
         redis::EXPIRE("QRcode",10); 
         return json(['code' => 1,'msg'=>"登录成功"]); 
         
     } 
     
     
     
    
}