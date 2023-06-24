<?php

namespace app\api\controller;

use support\Request;
use Tntma\Tntjwt\Auth;
#use Shopwwi\WebmanAuth\Facade\Auth;#jwt https://www.workerman.net/plugin/24
use think\facade\Db;#mysql https://www.kancloud.cn/manual/think-orm/1258003
use think\facade\Cache;#Cache https://www.kancloud.cn/manual/thinkphp6_0/1037634
use Respect\Validation\Exceptions\ValidationException;#字符验证器捕获错误

use Webman\Push\Api; //push推送
use support\Redis;//redis缓存 
use Webman\RedisQueue\Client; #redis queue 队列
use GuzzleHttp\Client as Guzz_Client;
use Casbin\WebmanPermission\Permission;#casbin权限
use Vectorface\GoogleAuthenticator;#谷歌验证

class user{
    
    public function appInfo(Request $request){
        $user = Db::name('account')->where('id', $request->user->id)->find(); #获取用户数据
        if(empty($user)){
           return json(['code' => 401, 'msg' => '角色数据丢失·请重新登录']); 
        }
        $users['userId'] = $user['id'];
        $users['username'] = $user['username'];
        $users['session_id'] = $user['key'];
        $users['version'] = getenv('APP_VERSION');
        $users['versionurl'] = getenv('APP_NEW_URL'); 
        return json(['code'=>0,'msg'=>'获取成功','data'=>$users]);  
    }
    
    
    #初始化获取用户数据  菜单 角色 权限
    public function auth(Request $request){ 
        $JWTuid = $request->user->subID ?? $request->user->id;
        
        $user = Db::name('account')->where('id', $request->user->id)->find(); #获取用户数据
        if(empty($user)){
            return json(['code' => 401, 'msg' => '用户数据丢失·请重新登录']);
        }
        $role = Db::table('sys_role')->where('roleId', $request->user->roleId)->find(); #获取角色数据 
        if(empty($role)){
            return json(['code' => 401, 'msg' => '角色数据丢失·请重新登录']);
        }
        
        //主题数据
        $theme = Db::table('sys_theme')->where('userId', $request->user->id)->find(); 
        if(empty($theme['theme'])){
            $users['theme'] = json_decode($role['theme'],true); 
        }else{
            $users['theme'] = json_decode($theme['theme'],true);
        }
        
       
        
        
        
        #--------------------自定义的一些数据----------------   
        $users['roles'] = array($role); //赋值角色数据 
        $users['username'] = $user['username']; 
        $users['nickname'] = $role['roleName'];
        $users['userId'] = $user['id'];
        $users['session_id'] = $user['key'];
        $users['info'] = ["moshi"=>$user['moshi'],"API_URL"=>getenv('API_URL') ];
        #----------------------------------- 
        
        
        #获取用户 & 角色菜单 ID表
        if(empty($request->user->sub)){  //主账号 
            $menus = Db::table('sys_role_menu')->where([["userId","=",$user['id']],["tenantId","=",$role['tenantId']],['roleId','=',$role['roleId']]])->find();//获取用户菜单
            if(empty($menus)){
                $menus = Db::table('sys_role_menu')->where([["userId","=",0],["tenantId","=",$role['tenantId']],['roleId','=',$role['roleId']]])->find();//获取角色菜单  
            }
            if(empty($menus['menuText'])){
               return json(['code' => 401,'msg'=>"异常·用户组角色未配置初始菜单" ]);  
            }
            
        }else{//子账号
            $subuser = Db::name('account')->where('username', $request->user->sub)->find();
            $subrole = Db::table('sys_role')->where('roleId', $subuser['roleId'])->find();  
            array_push($users['roles'],$subrole);//增加子账号角色  给前端
            
            $menus = Db::table('sys_role_menu')->where([["userId","=",$subuser['id']],["tenantId","=",$subrole['tenantId']],['roleId','=',$subrole['roleId']]])->find();//获取用户菜单
            if(empty($menus)){
                $menus = Db::table('sys_role_menu')->where([["userId","=",0],["tenantId","=",$subrole['tenantId']],['roleId','=',$subrole['roleId']]])->find();//获取角色菜单  
            }
            if(empty($menus['menuText'])){
               return json(['code' => 1,'msg'=>"异常·子用户组角色未配置初始菜单" ]);  
            }
            
            $users['username'] = $subuser['username']; 
            $users['nickname'] = $subrole['roleName']; 
            $users['subID'] = $subuser['id'];
            
        }
         
         
        #根据用户角色菜单ID，取得对应菜单
        $menu = Db::table('sys_menu')->where("del",0)->where("tenantId",$menus['tenantId'])->whereIn('menuId',implode(',',json_decode($menus['menuText'])))->withoutField('del')->order('sortNumber asc')->select();
        $Idmenu= array_column($menu->toArray(), null, 'menuId');//把id提为Key 方便后续其它操作 
        
         
        #遍历角色菜单 定义鉴权数据
        $casbin=""; 
        foreach ($menu as $item) {   
            if($item['menuType']==1){
                    $casbin .= "#".$item['authority']; 
                     
            }
        }  
        
        if(empty($request->user->sub)){    
            Redis::hset("casbin_{$role['roleId']}", $user['id']."_api", $casbin."#");//主账号 允许的接口权限
        }else{
            Redis::hset("submenu",$subuser['username'], $casbin."#");//子账号 允许的权限接口
        }
        
        
        
        #把需要进行谷歌验证的path写入缓存 
        $user_googel = Db::table('sys_user_googel')->where("myid",$JWTuid)->find();   
        $googurl="";  
        if(!empty($user_googel)){
            foreach (json_decode($user_googel['menuText'],true) as $item) {   
                if(!empty($Idmenu[$item])){ 
                    if($Idmenu[$item]['menuType']==1){//只对api接口进行谷歌验证检查
                        $googurl .= "#".$Idmenu[$item]['authority'];  
                    }
                    
                }
            }
            
        }
        Redis::hset("googurl", $JWTuid , $googurl."#");//需要进行谷歌验证的path
          
        
        
        
         
        $users['authorities'] = $menu; //赋值菜单数据 
         
          
          
        //--------------------APP有关数据----------------    
        $users['avatar'] = getenv('API_URL')."/Bank_ico/logo.png";
        $users['version'] = getenv('APP_VERSION');//版本号
        $users['UpdateUrl'] = getenv('APP_NEW_URL');//apk下载地址
        $users['UpdateForce'] = getenv('APP_Force');//是否强制更新
        $users['UpdateText'] = str_replace("//","\n",getenv('APP_Text')); //更新提示内容 
        //--------------------------------------------------------   
        
        
         
        $users['MenuName'] = "管理后台"; 
        $users['logo'] = $user['tenantId']; 
        $users['remark'] = $request->user->remark ?? ""; 
        $users['PUSH']['url'] =  localIP().":3011";
        $users['PUSH']['key'] =  config("plugin.webman.push.app.app_key");
             
             
          return json(['code'=>0,'msg'=>'操作成功','data'=>$users]); 
        }
 
        
    #账号基本信息
     public function user_basic(Request $request) {  
         $JWTuid = $request->user->subID ?? $request->user->id;
         $uid = $request->user->id;
         $Coulumn = $request->user->roleId==3?"upid":"myid";
         $user = Db::name('account')->where('id', $uid)->field(['id','google','SecretKey','Telegram','key','smoney','tmoney','money','rate','numMoney','sauto','etc','webhook','webhookurl','moshi'])->find(); 
         $ipsafe = Db::name('ipsafe')->where('myid', $JWTuid)->where('type', 1)->find();//登录ip
         $ipapi = Db::name('ipsafe')->where('myid', $uid)->where('type', 2)->find(); //支付ip
         $DaifuIP = Db::name('ipsafe')->where('myid', $uid)->where('type', 3)->find();//代付ip
         
         if(!empty($request->user->subID)){
             $subuser = Db::name('account')->where('id', $request->user->subID)->find();   
             $user['id']= $subuser['id'];
             $user['google'] = $subuser['google'];
             $user['SecretKey']= $subuser['SecretKey'];
             $user['Telegram']= $subuser['Telegram'];
             $v['sub']['money'] = $subuser['money'];
             $v['sub']['smoney'] = $subuser['money'];
             
            //  unset($subuser['key']);
            //  unset($subuser['SecretKey']); 
            //  $v['subuser'] = $subuser; 
         }
         
         $ga = new GoogleAuthenticator();
         $secret = $ga->createSecret();#生成谷歌密匙
         $v['google'] = $user['google'];
         if($user['google'] == 0 && empty($user['SecretKey'])){
            $v['key'] = $secret; 
            Db::name('account')->where('id',$JWTuid)->update(['SecretKey' => $secret]);  #更新
         }elseif($user['google'] == 0){
            $v['key'] = $user['SecretKey'];
         }else{
            $v['key'] = ''; 
         }
         
         $tjson = json_decode($user['Telegram'],true);
         if(empty($tjson['id'])){
            $v['Telegram'] = 0; 
         }else{
            $v['Telegram'] = 1; 
            $v['TelegramInfo'] = "{$tjson['first_name']} @{$tjson['username']}"; 
         }
          
          
          
         
         $v['lei']= Db::name('pay_lei')->where('del',0)->field(['id','name'])->order('id desc')->select(); 
         
         $v['GYZ_menu'] = Db::table('sys_user_googel')->where("myid",$JWTuid)->find();//获取用户需要验证的菜单&功能
         
         if(!empty($ipsafe['ip'])){
            $ipsafe['ip'] = str_replace("#", "\n", $ipsafe['ip']);
         }
         if(!empty($ipapi['ip'])){
            $ipapi['ip'] = str_replace("#", "\n", $ipapi['ip']);
         }
         if(!empty($DaifuIP['ip'])){
            $DaifuIP['ip'] = str_replace("#", "\n", $DaifuIP['ip']);
         }
         
         
         $v['loginIp'] = $ipsafe['ip']??'*.*.*.*';
         $v['apiIp'] = $ipapi['ip']??'*.*.*.*';
         $v['DaifuIp'] = $DaifuIP['ip']??'*.*.*.*';
         $v['gateurl'] = getenv("API_URL");
         $v['sauto'] = $user['sauto'];
         
         
         $qunlist = Db::name('tgbot')->where($Coulumn,$uid)->select(); 
         $v['qunlist'] = $qunlist;
          
         $v['tips'] = $user['key'];  
         unset($user['key']);
         unset($user['SecretKey']); 
         $v["user"]=$user;
         return json(['code'=>0,'msg'=>'操作成功','data'=>$v]);      
     }
     
     
     
     public function theme(Request $request) { 
         $uid = $request->user->id;
         $data =  $request->get(); 
         $theme = Db::table('sys_theme')->where('userId', $uid)->find();
         if(empty($theme)){ 
             Db::table('sys_theme')->insert(['userId'=>$uid,'theme' => $data['cache']]);  
         }else{ 
             if(empty($data['cache'])){
                  Db::table('sys_theme')->where('userId',$uid)->update(['theme' => ""]); 
             }else{
                 Db::table('sys_theme')->save(['userId'=>$uid,'theme' => $data['cache']]); 
             }  
         }
         return json(['code' => 1,'msg'=>"update ok"]);
         
         
     }
    #在线终端     
    public function zxzd(Request $request) { 
        $uid = $request->user->subID ?? $request->user->id; 
        $v['JWT'] =  md5($request->header("authorization")); 
        $v['JWTALL'] = Redis::HGETALL("HJWTMD5_{$uid}");
        return json(['code'=>1,'msg'=>'成功','data'=>$v]);  
    }
    
    
    #账号安全 谷歌验证开启/关闭
     public function my_open_Google(Request $request) {  
         $uid = $request->user->id;
         $JWTuid = $request->user->subID ?? $request->user->id;
         
         $data =  $request->post();
         $Auser = Db::name('account')->where('id', $JWTuid)->field(['google','SecretKey'])->find(); 
         $ga = new GoogleAuthenticator();
         $secret = $ga->createSecret();#生成谷歌密匙
         if($data['edit'] && $data['googpass']){
             $checkResult = $ga->verifyCode($Auser['SecretKey'], $data['googpass'], 2);
             if(!$checkResult){
                return json(['code' => 801,'msg'=>"谷歌验证码错误"]);  
             } 
            Db::name('account')->where('id',$JWTuid)->update(['google' => 1]);  #更新 
            
            //$user_googel = Db::table('sys_user_googel')->where("myid",$JWTuid)->find();
            // if(empty($user_googel)){  
            //     $sql['myid'] = $JWTuid;
            //     $sql['menuText'] = "[57,39,50,3,4,65,66]"; //开启谷歌验证后写入默认需要鉴权的页面
            //     $save = Db::table('sys_user_googel')->insert($sql);
            // } 
           $user = Db::name('account')->where('id',$uid)->find();  //如果是子账号请加sub
           if(!empty($request->user->subID)){
               $subuser = Db::name('account')->where('id',$request->user->subID)->find(); 
               $user["one"] =$subuser['one'];
               $user["sub"] =$subuser['username'];
               $user["subID"] =$subuser['id'];
               $user["google"] =$subuser['google'];
           }
           
            
           
           
            Redis::del("HJWTMD5_{$JWTuid}");//踢掉所有已登录用户
             
           
            #生成新的登录用户jwt
            $tokenObject = Auth::login($user);//生成token 
            $JWT_MD5 = $tokenObject->token_md5;
            Redis::hset("HJWTMD5_{$JWTuid}",$JWT_MD5,time());
            redis::EXPIRE("HJWTMD5_{$JWTuid}",config('plugin.TNTma.tntjwt.app.exp'));//设置过期时间
            
        
            return json(['code'=>0,'msg'=>'开启谷歌验证成功','token'=>$tokenObject]);  
         }else{
            
            $ga = new GoogleAuthenticator(); 
             $checkResult = $ga->verifyCode($Auser['SecretKey'], $data['googpass'], 2);
             if(!$checkResult){
                return json(['code' => 801,'msg'=>"谷歌验证码错误"]);  
             } 
             
           Db::name('account')->where('id',$JWTuid)->update(['google' => 0]);  #更新   
           Redis::del("GYZ_".$JWTuid);//删除10分内的谷歌授权
           
           $user = Db::name('account')->where('id',$uid)->find();  //取得主账号数据·子账号请加sub
           if(!empty($request->user->subID)){
               $subuser = Db::name('account')->where('id',$request->user->subID)->find(); 
               $user["one"] =$subuser['one'];
               $user["sub"] =$subuser['username'];
               $user["subID"] =$subuser['id'];
               $user["google"] =$subuser['google'];
           }
           
            Redis::del("HJWTMD5_{$JWTuid}");//踢掉所有已登录用户 
           
            #生成新的登录用户jwt
            $tokenObject = Auth::login($user);//生成token 
            $JWT_MD5 = $tokenObject->token_md5;
            Redis::hset("HJWTMD5_{$JWTuid}",$JWT_MD5,time());
            redis::EXPIRE("HJWTMD5_{$JWTuid}",config('plugin.TNTma.tntjwt.app.exp'));//设置过期时间 
           
           return json(['code'=>0,'msg'=>'关闭谷歌验证成功','token'=>$tokenObject]);   
         } 
         
     }
     
     
     
    #账号安全 重置谷歌秘钥
     public function googelRet(Request $request) {  
         $uid = $request->user->id;
         $JWTuid = $request->user->subID ?? $request->user->id;
         
         $data =  $request->post();
         $user = Db::name('account')->where('id', $JWTuid)->field(['google','SecretKey'])->find(); 
         $ga = new GoogleAuthenticator();
         $secret = $ga->createSecret();#生成谷歌密匙
         
         $ga = new GoogleAuthenticator(); 
             $checkResult = $ga->verifyCode($user['SecretKey'], $data['googpass'], 2);
             if(!$checkResult){
                return json(['code' => 801,'msg'=>"谷歌验证码错误"]);  
             } 
             
           Db::name('account')->where('id',$JWTuid)->update(['google' => 0,'SecretKey'=>$secret]);  #更新   
           Redis::del("GYZ_".$JWTuid);//删除10分内的谷歌授权
           
           $user = Db::name('account')->where('id',$uid)->find();  //取得主账号数据·子账号请加sub
           if(!empty($request->user->subID)){
               $subuser = Db::name('account')->where('id',$request->user->subID)->find(); 
               $user["one"] =$subuser['one'];
               $user["sub"] =$subuser['username'];
               $user["subID"] =$subuser['id'];
               $user["google"] =$subuser['google'];
           }
           
            Redis::del("HJWTMD5_{$JWTuid}");//踢掉所有已登录用户 
           
            #生成新的登录用户jwt
            $tokenObject = Auth::login($user);//生成token 
            $JWT_MD5 = $tokenObject->token_md5;
            Redis::hset("HJWTMD5_{$JWTuid}",$JWT_MD5,time());
            Redis::EXPIRE("HJWTMD5_{$JWTuid}",config('plugin.TNTma.tntjwt.app.exp'));//设置过期时间
            
            
           
           return json(['code'=>0,'msg'=>'谷歌秘钥已重置并关闭,请重新绑定！','token'=>$tokenObject]);  
         
     }
     
     
     
     
     
     
         
    #绑定telegram 
    public function my_open_Telegram(Request $request) {  
        $JWTuid = $request->user->subID ?? $request->user->id;
        $data =  $request->all();  
        $ip = $request->getRealIp($safe_mode=true);  
        if(empty($data['code']) || strlen($data['code']) != 6){ 
            return json(['code' =>1, 'msg' => '数据不能为空' ]);      
        }  
        if(Redis::tTl($data['code']) < 5){ 
            $user = Db::name('account')->where('id', $JWTuid)->field(['Telegram'])->find();
            //未绑定才进行设定
            if($user['Telegram'] == 'null'){
                Redis::setex($data['code'], 600, '');
                Redis::setex("ID".$data['code'], 600, $JWTuid);
                Redis::setex("IP".$data['code'], 600, $ip);
                return json(['code' =>1, 'msg' => '链接机器人成功' ]);    
            }else{
               return json(['code' => 4,'msg'=>"你的账号已绑定Telegram·请重试！" ]); 
            } 
            
        }else{
            $tuser = Redis::get($data['code']);
            if(empty($tuser)){
                return json(['code'=>5,'msg'=>'未查询到数据' , 'data'=>Redis::tTl($data['code']) ]);
            }else{
                
                if(Redis::get("IP".$data['code']) != $ip){
                    return json(['code' => 4,'msg'=>"登录数据异常·请F5刷新页面重新登录" ]);
                }else{
                    $user = Db::name('account')->where('id', $JWTuid)->find();
                    if($user['Telegram'] == 'null'){
                        $tuserJson = json_decode($tuser,true);
                        $users = Db::name('account')->json(['Telegram'])->where('Telegram->id',$tuserJson['id'])->setFieldType(['Telegram->id' => 'int'])->find(); 
                        if($users){
                            #队列发消息给tg
                            $queueData = array("uid"=>$tuserJson['id'],"text"=>"绑定失败,已绑定给其它账号({$users['username']})");
                            Client::send('send_TGsend',$queueData);
                          return json(['code' => 4,'msg'=>"绑定失败！{$tuserJson['first_name']} @{$tuserJson['username']} <br> 已绑定给其它账号({$users['username']})" ]);  
                        } 
                        Db::name('account')->where('id' ,$JWTuid)->update(['Telegram' => $tuser]); 
                        Redis::del($data['code']);
                        Redis::del("ID".$data['code']);
                        Redis::del("IP".$data['code']);
                        
                        
                        #Push 给前端发送实时消息 并入数据库------------------------------------
                        $queueData = array("uid"=>$user['id'],"msg"=>"商户成功绑定Telegram(@{$tuserJson['username']})");
                        Client::send('send_notice',$queueData);
                        //构造数据插入数据库
                        $Msga['myid'] = $JWTuid;
                        $Msga['title'] = "账户绑定telegram（{$tuserJson['first_name']}）";
                        $Msga['type'] = 'notice';
                        $Msga['content'] = "你的账户已成功绑定Telegram·你可以再登录时使用该方式登录本站！TG账户信息：{$tuserJson['first_name']} @{$tuserJson['username']}";
                        $Msga['time'] = time();
                        Db::name('message')->insert($Msga);
                        #Push End-------------------------------------------------------------
                        
                        #队列发消息给tg
                        $queueData = array("uid"=>$tuserJson['id'],"text"=>"{$user['username']} - 绑定成功 [{$tuserJson['first_name']} @{$tuserJson['username']}]");
                        Client::send('send_TGsend',$queueData);
                        
                        return json(['code' => 0,'msg'=>"恭喜你,绑定成功 - {$tuserJson['first_name']} @{$tuserJson['username']}", 'data'=> $tuserJson ]);
                    }else{
                       return json(['code' => 4,'msg'=>"你的账号已绑定Telegram·请重试2" ]);  
                    }
                }     
            }
        }  
      
      return json(['code'=>0,'msg'=>'fuck']);     
    }
         
     
     
        
        
    
    #顶部获取未读消息
    public function message_notice(Request $request) { 
        $myid = $request->user->id;
        if(!empty($request->user->subID)){
            $myid =  $myid.",".$request->user->subID; 
        }
        $list = Db::name('message')->withoutField(['myid','read'])->where([['del', '=',0],['type', '=','notice'],['read', '=',0] ])->whereIn('myid',$myid)->limit(0,9)->order('id desc')->select();
        $listr = Db::name('message')->withoutField(['myid','read'])->where([['del', '=',0],['type', '=','letter'],['read', '=',0] ])->whereIn('myid',$myid)->limit(0,9)->order('id desc')->select(); 
        $listrr = Db::name('message')->withoutField(['myid','read'])->where([['del', '=',0],['type', '=','todo'],['read', '=',0] ])->whereIn('myid',$myid)->limit(0,9)->order('id desc')->select(); 
        #$list = array_filter($list);#去除空数据  
        
        $v['notice'] = $list;
        $v['letter'] = $listr;
        $v['todo'] = $listrr; 
        return json(['code' => 0,'msg'=>"获取成功" ,'data' => $v ]);
        
        }
        
    #我的消息 获取消息 3集合 
    public function my_message_notice(Request $request) {  
        $data = $request->get();
        $page = ($data['page']-1)*$data['limit'];
        
        $myid = $request->user->id;
        if(!empty($request->user->subID)){
            $myid =  $myid.",".$request->user->subID; 
        }
        
        
        $list = Db::name('message')->withoutField(['myid'])->where([['del', '=',0],['type', '=',$data['where']]])->whereIn('myid',$myid)->limit($page,$data['limit'])->order('id desc')->select();  
        
        $count = Db::name('message')->where([['del', '=',0],['type', '=',$data['where']]])->whereIn('myid',$myid)->count(); 
        $v['count'] = $count; 
        $v['list'] = $list; 
        return json(['code' => 0,'msg'=>"获取成功" ,'data' => $v ]); 
        } 
        
        
        
    #我的消息 未读消息条数
    public function my_message_Noread(Request $request) {   
        $myid = $request->user->id;
        if(!empty($request->user->subID)){
            $myid =  $myid.",".$request->user->subID; 
        }
        $noticecount = Db::name('message')->where([['read', '=',0],['del', '=',0],['type', '=','notice']])->whereIn('myid',$myid)->count();
        $lettercount = Db::name('message')->where([['read', '=',0],['del', '=',0],['type', '=','letter']])->whereIn('myid',$myid)->count();
        $todocount = Db::name('message')->where([['read', '=',0],['del', '=',0],['type', '=','todo']])->whereIn('myid',$myid)->count();
        
        $v['noticecount'] =  $noticecount;
        $v['lettercount'] =  $lettercount;
        $v['todocount'] =  $todocount; 
        
        return json(['code' => 0,'msg'=>"获取成功" ,'data' => $v ]); 
        } 
        
        
            
    #标记消息为已读  3清空+3标记     
    public function message_read(Request $request) { 
        $myid = $request->user->id;
        if(!empty($request->user->subID)){
            $myid =  $myid.",".$request->user->subID; 
        }
        $type = $request->post('type');  
        $idarray = $request->post('id');    
        if(empty($idarray)){
         $number = Db::name('message')->where('type',$type)->whereIn('myid',$myid)->update(['read' => 1]);    
        }else{
         $number = Db::name('message')->whereIn('id',$idarray)->update(['read' => 1]);   
        }
         
        return json(['code' => 0,'msg'=>"已阅读消息:{$number}条"]); 
        
        } 
    
    #通用删除消息    
    public function my_message_del(Request $request) {    
        $id = $request->post('id'); 
        $number = Db::name('message')->where('myid',$request->user->id)->whereIn('id',$id)->update(['del' => 1]); 
        $numbers = Db::name('message')->where('myid',$request->user->subID)->whereIn('id',$id)->update(['del' => 1]); 
        $number = $number+ $numbers;
        return json(['code' => 0,'msg'=>"成功删除消息:{$number}条"]);
    }
        
        
        
    #修改密码    
    public function UpdatePassword(Request $request) {  
        $JWTuid = $request->user->subID ?? $request->user->id;
        $oldPassword = md5($request->post('oldPassword'));  
        $newpassword = md5($request->post('password')); 
        if($request->post('password') != $request->post('password2')){
          return json(['code' => 1,'msg'=>"两次输入的新密码不一致"]);    
        }
        
        $user = Db::name('account')->where('id', $JWTuid)->find(); 
        if($oldPassword != $user['password'] ){
          return json(['code' => 1,'msg'=>"原始密码错误"]);  
        }
        
        $number = Db::name('account')->where([['id','=',$JWTuid]])->update(['password' => $newpassword,'spassword'=>$request->post('password')]);   
      
        return json(['code' => 0,'msg'=>"密码修改成功!"]); 
        
        }    
        
    #重置key    
    public function retkey(Request $request) {
          
        $data = $request->post('key');
        if($data == "@smalpony"){
            $key = strtoupper(md5($request->sessionId().rand(1,999)));
            Db::name('account')->where('id',$request->user->id)->update(['key' => $key]);  #更新  
        }
        return json(['code' => 0,'msg'=>"Key秘钥·重置成功",'key'=>$key]); 
        
        
    }  
    
    
    
    #设置谷歌授权页面   
    public function SetGoogelMenu(Request $request) {
        $JWTuid = $request->user->subID ?? $request->user->id;
     
         
        $data = json_encode($request->post('ids'));  
        
        // #根据用户角色菜单ID，取得对应菜单
        $menu = Db::table('sys_menu')->where("del",0)->whereIn('menuId',implode(',',$request->post('ids')))->select(); 
        #遍历菜api单 定义鉴权数据
        $googurl=""; 
        foreach ($menu as $item) {   
            if($item['menuType']==1){
                    $googurl .= "#".$item['authority']; 
                     
            }
        } 
        Redis::hset("googurl", $JWTuid , $googurl."#");//需要进行谷歌验证的path
        
        $user_googel = Db::table('sys_user_googel')->where("myid",$JWTuid)->find();
        if(empty($user_googel)){  
            $sql['myid'] = $JWTuid;
            $sql['menuText'] = $data;  
            $save = Db::table('sys_user_googel')->insert($sql);
            return json(['code' => 0,'msg'=>"成功设置指定功能页面需要谷歌验权"]);
        }else{
            Db::table('sys_user_googel')->where('myid',$JWTuid)->update(['menuText' => $data]);  #更新 
            return json(['code' => 0,'msg'=>"已更新谷歌验权功能页面"]);
        }    
    } 
    
    
    
    #设置登录IP白名单   
    public function LoginIp(Request $request) {
        $JWTuid = $request->user->subID ?? $request->user->id;  
        $uid = $request->user->id;
        $data = $request->post(); 
        $ipsafe = Db::name('ipsafe')->where("myid",$JWTuid)->where("type",1)->find();
        if(empty($data['ip'])){
            $data['ip'] = '*.*.*.*';     
        }
        $data['ip'] = str_replace("\n", "#", $data['ip']);
        
        if(empty($ipsafe)){
            $sql['myid'] = $JWTuid;
            $sql['type'] = 1;
            $sql['ip'] = $data['ip']; 
            Db::name('ipsafe')->insert($sql);
            return json(['code' => 0,'msg'=>"设置成功"]); 
        }  
        Db::name('ipsafe')->where('myid',$JWTuid)->where('type',1)->update(['ip' => $data['ip']]);
        return json(['code' => 0,'msg'=>"设置已变更"]);   
    }  
    
    #设置请求IP白名单   
    public function ApiIp(Request $request) {
          
        $uid = $request->user->id;
        $data = $request->post(); 
        $ipsafe = Db::name('ipsafe')->where("myid",$uid)->where("type",2)->find();
        if(empty($data['ip'])){
            $data['ip'] = '*.*.*.*';     
        }
        $data['ip'] = str_replace("\n", "#", $data['ip']);
        
        if(empty($ipsafe)){
            $sql['myid'] = $uid;
            $sql['type'] = 2;
            $sql['ip'] = $data['ip']; 
            Db::name('ipsafe')->insert($sql);
            Redis::hset("ApiIp",$uid,$data['ip']);//同步给redis
            return json(['code' => 0,'msg'=>"设置成功"]); 
        }
        Redis::hset("ApiIp",$uid,$data['ip']);//同步给redis
        Db::name('ipsafe')->where('myid',$uid)->where('type',2)->update(['ip' => $data['ip']]);
        return json(['code' => 0,'msg'=>"支付请求IP白名单·设置完成"]);   
    }
    
    #设置代付IP白名单   
    public function DaifuIp(Request $request) {
          
        $uid = $request->user->id;
        $data = $request->post(); 
        $ipsafe = Db::name('ipsafe')->where("myid",$uid)->where("type",3)->find();
        if(empty($data['ip'])){
            $data['ip'] = '*.*.*.*';     
        }
        $data['ip'] = str_replace("\n", "#", $data['ip']);
        
        if(empty($ipsafe)){
            $sql['myid'] = $uid;
            $sql['type'] = 3;
            $sql['ip'] = $data['ip']; 
            Db::name('ipsafe')->insert($sql);
            Redis::hset("DaifuIp",$uid,$data['ip']);//同步给redis
            return json(['code' => 0,'msg'=>"设置成功"]); 
        } 
        Redis::hset("DaifuIp",$uid,$data['ip']);//同步给redis
        Db::name('ipsafe')->where('myid',$uid)->where('type',3)->update(['ip' => $data['ip']]);
        return json(['code' => 0,'msg'=>"代付请求IP白名单·设置完成"]);   
    }
    
    #卡商 商户收支明细报表
    public function log_money (Request $request){
      $uid = $request->user->id;
      $data = $request->get();
      $Coulumn = $request->user->roleId==3?"upid":"myid";
      
      $page = ($data['page']-1)*$data['limit']; 
      $jsonVal = array();
      $so =[];
      array_push($so,"myid");
      array_push($so,'=');
      array_push($so,$uid);
      
      array_push($so,"del");
      array_push($so,'=');
      array_push($so,0);
      
      
      
      
      
 
      
      //参数不等于空进行匹配搜索    
      if(!empty($data['state'])){
          array_push($so,"type");
          array_push($so,'=');
          array_push($so,$data['state']);
      } 
      if(!empty($data['keyword'])){
          array_push($so,$data['t']);
          array_push($so,'=');
          array_push($so,$data['keyword']);
      }
      
      
      
 
      
     $so = array_chunk($so,3);//拆分 
     
     if(empty($data['state'])){
         $incmoney = Db::name('money_log')->where([$so,["money",">",0],["type",">",0],["sxf",">",0]])->sum('money'); 
         $decmoney = Db::name('money_log')->where([$so,["money","<",0],["type",">",0]])->sum('money');
         $decmoney2 = Db::name('money_log')->where([$so,["money","<",0],["type",">",0]])->count();
         $sxfmoney = Db::name('money_log')->where([$so,["type",">",0]])->sum('sxf');
     }
      
      $count = Db::name('money_log')->where([$so])->count(); 
      $list = Db::name('money_log')->where([$so])->limit($page,$data['limit'])->order('id desc')->select(); 
      
      $jsonVal['count'] = $count;
      $jsonVal['list'] = $list;
      $jsonVal['inc'] = $incmoney ?? 0;
      $jsonVal['dec'] = $decmoney ?? 0;
      $jsonVal['dec2'] = $decmoney2 ?? 0;
      $jsonVal['sxf'] = $sxfmoney ?? 0;
        
        
      return json(['code' => 0,'msg'=>"获取成功" ,'msg'=>"获取成功" ,'data' => $jsonVal ]);  
    }
    
    
    
    public function UserInfo(Request $request) {
        $uid = $request->user->id;
        $user = Db::name('account')->where('id', $uid)->field(['id','username','smoney','tmoney','money','rate','key'])->find();
        if(!empty($user)){ 
            $user['uid'] = $uid."";
            $user['decmoney'] = $user['smoney'] - $user['tmoney'];  
        }
        
        return json(['code' => 0,'msg'=>"获取成功" ,'info' => $user ]);  
    }
    
    
 
    
    
 
    
    
    
 
    
    
    
 
    
    
 
    
    #新增子账户
    public function daifuAuto(Request $request){
      $uid = $request->user->id;
      $data = $request->post(); 
      if($data['auto'] === 0){
        Db::name('account')->where('id',$uid)->update(['sauto' => 1]);  
        return json(['code' => 0,'msg'=>"自动分笔 - 已开启",'auto'=>1  ]);  
      }else{
        Db::name('account')->where('id',$uid)->update(['sauto' =>0]);  
        return json(['code' => 0,'msg'=>"自动分笔 - 已关闭",'auto'=>0  ]);  
      }     
    }
    
    
    #开关推送代付余额功能
    public function Webhookzt(Request $request){
      $uid = $request->user->id;
      $data = $request->post(); 
      if($data['zt'] === 0){
        $user = Db::name('account')->where('id',$uid)->find();
        if(substr($user['webhookurl'],0,4) != "http"){
           return json(['code' => 2,'msg'=>"开启失败·接收推送地址有误,请重新设置地址" ]); 
        }
        
        Db::name('account')->where('id',$uid)->update(['webhook' => 1]);  
        return json(['code' => 0,'msg'=>"代付余额推送 - 已开启",'webhook'=>1  ]);  
      }else{
        Db::name('account')->where('id',$uid)->update(['webhook' =>0]);  
        return json(['code' => 0,'msg'=>"代付余额推送 - 已关闭",'webhook'=>0  ]);  
      }     
    }
    
    
    
    #设置推送地址
    public function Webhook(Request $request){
        $uid = $request->user->id;
        $data = $request->post();
        if(empty($data['webhookUrl'])){
           return json(['code' => 2,'msg'=>"请准确填写接口地址" ]); 
        }
        Db::name('account')->where('id',$uid)->update(['webhookurl' => $data['webhookUrl']]);  
        return json(['code' => 0,'msg'=>"Webhook地址设置成功" ]); 
    }
    
     
    
    
    
    
    public function exit(Request $request){
        $uid = $request->user->id;
        $JWTuid = $request->user->subID ?? $request->user->id;
        $data = $request->get(); 
        if(empty($data['type'])){ 
            redis::hdel("HJWTMD5_{$JWTuid}",$data['jwt']);
            return json(['code' => 0,'msg'=>"用户已被踢下线.." ]); 
        }else{
            $all = redis::HGETALL("HJWTMD5_{$JWTuid}");
             foreach ($all as $key=>$value) {
                 if($key != $data['jwt']){
                    redis::hdel("HJWTMD5_{$JWTuid}",$key); 
                 }
             }  
           return json(['code' => 0,'msg'=>"已踢出其它用户.." ]);   
             
        }   
         
    }
    
    
    
    #获取飞机群列表
    public function TGqunlist(Request $request){
        $uid = $request->user->id;
        $data = $request->post(); 
        $Coulumn = $request->user->roleId==3?"upid":"myid";
        $list = Db::name('tgbot')->where($Coulumn,$uid)->where('del',0)->select(); 
        
        return json(['code' => 0,'msg'=>"获取成功..",'data'=>$list ]);  
    
     
    
    }
    
    
    
    
    
    #飞机群授权 + 解除群授权
    public function TGqunbind(Request $request){
        $uid = $request->user->id;
        $data = $request->post(); 
        $Coulumn = $request->user->roleId==3?"upid":"myid";
        $ip = $request->getRealIp($safe_mode=true); 
        
        if(!empty($data['jiechu'])){
            Db::name('tgbot')->where($Coulumn,$uid)->where('qunid',$data['jiechu'])->update(['del' => 1]); 
            
            $queueData = array("url"=>"/sendMessage?chat_id={$data['jiechu']}&text=<b>本群机器人已被停止授权</b>\n机器人将在8秒后退出本群"); 
            Client::send('send_TGsend',$queueData);
            
            $queueData = array("notifyurl"=>getenv("BOT_URL").getenv("BOT_TOKEN")."/leaveChat?chat_id=".$data['jiechu'],"form"=>[]); 
            Client::send('send_ddbf',$queueData,10);//10s后退群
            
            
            
            // $client = new Guzz_Client(['timeout' => 5]);  
            // $res = $client->request('GET', getenv("BOT_URL").getenv("BOT_TOKEN")."/leaveChat?chat_id=".$data['jiechu'])->getBody(); 
            return json(['code' =>1, 'msg' => '已停止对该群授权' ]); 
            
        }
        
        
        
        if(empty($data['auth']) || strlen($data['auth']) != 4){ 
            return json(['code' =>0, 'msg' => 'auth不能为空' ]);      
        }
        
        $redis_key = "auth_{$data['auth']}";
        $redis_key_IP = "auth_{$data['auth']}_IP";
        
        if(Redis::tTl($redis_key) < 5){
            Redis::setex($redis_key, 600,"");//设置一个空白的key 用于后续填充数据 
            Redis::setex($redis_key_IP, 600,$ip);
            return json(['code' =>2, 'msg' => '连接机器人成功' ]); 
        } 
        $auth = Redis::get($redis_key); //取得 redis keu中的数据
        if(empty($auth)){
            return json(['code' => 3, 'msg' => '未查询到数据' ,'data'=> Redis::tTl($redis_key)]);   
        }
        if(Redis::get($redis_key_IP) != $ip){ 
            Redis::del($redis_key);
            Redis::del($redis_key_IP);
            return json(['code' => 0,'msg'=>"授权失败,创建者IP和授权IP不符·请重试" ]);    
        } 
        
        $authjson = json_decode($auth,true);
         
        
        
        $tgqun = Db::name('tgbot')->where('qunid' ,$authjson['id'])->where('myid' ,$uid)->find();
        if($tgqun){ 
            Db::name('tgbot')->where('id',$tgqun['id'])->update(['del' => 0,'name' =>$authjson['title'],'json' => $auth]);  
        }else{
            $sql[$Coulumn] = $uid;
            $sql['name'] = $authjson['title'];
            $sql['qunid'] = $authjson['id'];
            $sql['json'] = $auth;
            $sql['time'] = time();
            Db::name('tgbot')->insert($sql);
            
        }
        $queueData = array("url"=>"/sendMessage?chat_id={$authjson['id']}&text=<b>本群机器人功能授权成功</b>\n\n发送:<u>/start</u> 查询指令"); 
        Client::send('send_TGsend',$queueData);
        Redis::del($redis_key);
        Redis::del($redis_key_IP);
        return json(['code' => 1,'msg'=>"授权成功",'data'=>$authjson]);    
        
        
        
        
    }
    
    
        
}