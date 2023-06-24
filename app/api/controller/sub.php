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

class sub{
    
    #子账户列表    
    public function sublist (Request $request) {
      $uid = $request->user->id;
      $data = $request->get();
      $page = ($data['page']-1)*$data['limit'];
      $so =[];
      $jsonVal = array();
      array_push($so,"del");
      array_push($so,'=');
      array_push($so,0);
      
      array_push($so,"roleId");
      array_push($so,'=');
      array_push($so,4);
      
      array_push($so,"upid");
      array_push($so,'=');
      array_push($so,$uid);
      
      if(!empty($data['keyword'])){
          array_push($so,$data['t']);
          array_push($so,'=');
          array_push($so,$data['keyword']);
      }
      
      
      $so = array_chunk($so,3);//拆分 
      
      $count = Db::name('account')->where([$so])->count(); 
      $list = Db::name('account')->where([$so])->limit($page,$data['limit'])->order('id desc')->select(); 
      $jsonVal['count'] = $count;
      $jsonVal['list'] = $list;
        return json(['code'=>0,'msg'=>"ok",'data'=>$jsonVal ]); 
        
    } 
    
    
    public function apizt(Request $request){
      $uid = $request->user->id;
      $data = $request->post();  
        $upuser = Db::name('account')->where('id', $data['id'])->find();
        if(empty($upuser) || $upuser['upid'] != $uid){
           return json(['code'=>1,'msg'=>'你无权操作该用户' ]);   
        }
        
        $update = Db::name('account')->where([['id',"=", $data['id']],['upid',"=", $uid]])->update(['api' => $data['zt']]);
        if(!$update){
            return json(['code' =>0,'msg'=>"{$upuser['username']}·修改失败"    ]);  
        }
        $msg = $data['zt']?"正常":"已封禁";
        return json(['code' => 1,'msg'=>"{$upuser['username']} → 账户状态:{$msg}"    ]);
    }
    
    public function googlezt(Request $request){
      $uid = $request->user->id;
      $data = $request->post();  
        $upuser = Db::name('account')->where('id', $data['id'])->find();
        if(empty($upuser) || $upuser['upid'] != $uid){
           return json(['code'=>1,'msg'=>'你无权操作该用户' ]);   
        }
        
        $update = Db::name('account')->where([['id',"=", $data['id']],['upid',"=", $uid]])->update(['google' => $data['zt']]);
        if(!$update){
            return json(['code' =>0,'msg'=>"{$upuser['username']}·修改失败"    ]);  
        }
        $msg = $data['zt']?"已开启":"已关闭";
        return json(['code' => 1,'msg'=>"{$upuser['username']} → 谷歌验证:{$msg}"    ]);
    }
    
    public function onezt(Request $request){
      $uid = $request->user->id;
      $data = $request->post();  
        $upuser = Db::name('account')->where('id', $data['id'])->find();
        if(empty($upuser) || $upuser['upid'] != $uid){
           return json(['code'=>1,'msg'=>'你无权操作该用户' ]);   
        }
        
        $update = Db::name('account')->where([['id',"=", $data['id']],['upid',"=", $uid]])->update(['one' => $data['zt']]);
        if(!$update){
            return json(['code' =>0,'msg'=>"{$upuser['username']}·修改失败"    ]);  
        }
        $msg = $data['zt']?"只能管理下属用户":"允许管理所有用户";
        Redis::del("HJWTMD5_{$upuser['id']}");
        return json(['code' => 1,'msg'=>"{$upuser['username']} → 限管下级号:{$msg}"    ]);
    }
    
    
    
    public function mtzt(Request $request){
      $uid = $request->user->id;
      $data = $request->post();  
        $upuser = Db::name('account')->where('id', $data['id'])->find();
        if(empty($upuser) || $upuser['upid'] != $uid){
           return json(['code'=>1,'msg'=>'你无权操作该用户' ]);   
        }
        
        $update = Db::name('account')->where([['id',"=", $data['id']],['upid',"=", $uid]])->update(['mt' => $data['zt']]);
        if(!$update){
            return json(['code' =>0,'msg'=>"{$upuser['username']}·状态修改失败mt"    ]);  
        }
        $msg = $data['zt']?"加款时扣取自身余额":"不限制";
        return json(['code' => 1,'msg'=>"{$upuser['username']} → 加款模式:{$msg}"    ]);
    }
    
    
    #获取子账户权限
    public function subauth(Request $request) {
        $uid = $request->user->id;
        $data = $request->get();
        
        $subuser = Db::name('account')->where('id', $data['id'])->find();
        
        #获取同角色拥有的菜单数据 - 如果是按用户获取对应数据则where使用 userId =$user['id'] 从而达到不同用户不同菜单
        $menus = Db::table('sys_role_menu')->where([["userId","=",$subuser['id']],["tenantId","=",$subuser['tenantId']],['roleId','=',$subuser['roleId']]])->find();//获取用户菜单
        if(empty($menus)){
            $menus = Db::table('sys_role_menu')->where([["userId","=",0],["tenantId","=",$subuser['tenantId']],['roleId','=',$subuser['roleId']]])->find();//获取角色菜单  
        }
        
        return json(['code' => 0,'msg'=>"获取成功" ,'data' => $menus ]);  
    }
    
    
    #修改子账户权限
    public function setSubMenu(Request $request) {
        $uid = $request->user->id;
        $data = $request->post();
        
        $subuser = Db::name('account')->where('id', $data['id'])->find(); 
        $menus = Db::table('sys_role_menu')->where([["userId","=",$subuser['id']],["tenantId","=",$subuser['tenantId']],['roleId','=',$subuser['roleId']]])->find();//获取用户菜单
        $menuText = json_encode($data['ids']);
        if(empty($menus)){
                $sql['userId'] = $subuser['id'];
                $sql['roleId'] = $subuser['roleId'];
                $sql['tenantId'] = $subuser['tenantId'];
                $sql['menuText'] = $menuText;
                $save = Db::table('sys_role_menu')->insert($sql);    
        }else{
            Db::table('sys_role_menu')->where([['userId','=',$subuser['id']],['roleId','=',$subuser['roleId']],['tenantId','=',$subuser['tenantId']]])->update(['menuText' =>$menuText]);
        } 
        Redis::hdel("submenu", $subuser['username']);//删除权限以便提示前端更新数据
        return json(['code' => 0,'msg'=>"设置成功" ,'data' => 0 ]);  
    }
    
    
    
    
    #加款扣款
    public function money (Request $request){    
      $uid = $request->user->id;  
      $data = $request->post();  
      
      $upuser = Db::name('account')->where('id', $data['id'])->find();
        if(empty($upuser) || $upuser['upid'] != $uid){
           return json(['code'=>1,'msg'=>'你无权操作该用户' ]);   
        }
      
       
      
      if($data['type'] === 1){
          Db::name('account')->where('id',$upuser['id'])->inc('money',$data['money'])->inc('smoney',$data['money'])->update();
 
              $log['type']= "加余额";
              $log['uid']=$upuser['id'];
              $log['username']=$upuser['username'];
              $log['money']=$data['money']; 
              $log['remark']=$data['text']??"";
              $log['y']=$request->user->subID ?? $request->user->id;
              $log['z']=$request->user->sub ?? $request->user->username;
              $log['time']=time();
              $log['date']=date("Y-m-d H:i:s");
              Db::name('log')->insert($log);  
          
      }else if($data['type'] === 2){
          Db::name('account')->where('id',$upuser['id'])->dec('money',$data['money'])->dec('smoney',$data['money'])->update();
          
              $log['type']= "减余额";
              $log['uid']=$upuser['id'];
              $log['username']=$upuser['username'];
              $log['money']=$data['money']; 
              $log['remark']=$data['text']??"";
              $log['y']=$request->user->subID ?? $request->user->id;
              $log['z']=$request->user->sub ?? $request->user->username;
              $log['time']=time();
              $log['date']=date("Y-m-d H:i:s");
              Db::name('log')->insert($log); 
          
      } 
      return json(['code' => 0,'msg'=>"操作成功" ]); 
    }
    
    
    #新增子账户
    public function subadd(Request $request){
      $uid = $request->user->id;
      $data = $request->post();  
        $user = Db::name('account')->where('username',$data['username'])->find();
        if(!empty($user)){
            return json(['code' => 1,'msg'=>"该账号已被占用"    ]);  
        } 
        $key = strtoupper(md5($request->sessionId().rand(1,999)));
        $ga = new GoogleAuthenticator();
        $secret = $ga->createSecret();#生成谷歌密匙
        $sql['regtime'] = time();
        $sql['rate'] = 0;
        $sql['key'] = $key;
        $sql['upid'] = $uid;
        $sql['money'] = 0;
        $sql['smoney'] = 0;
        $sql['SecretKey'] = $secret;
        $sql['roleId'] = 4;
        $sql['tenantId'] = 1;
        $sql['username'] = $data['username'];
        $sql['password'] = md5($data['password']);
        $sql['tmoney'] = $data['tmoney']??0;
        $save = Db::name('account')->insert($sql);
        return json(['code' => 0,'msg'=>"{$save} - 新增子账户成功"  ]);   
    }
    
    
    #卡商重置密码
    public function subpw(Request $request){
      $uid = $request->user->id;
      $data = $request->post(); 
      
        $upuser = Db::name('account')->where('id', $data['id'])->find();
        if(empty($upuser) || $upuser['upid'] != $uid){
           return json(['code'=>1,'msg'=>'你无权操作该用户' ]);   
        }
        
        $update = Db::name('account')->where([['id',"=", $data['id']],['upid',"=", $uid]])->update(['password' => md5($data['password'])]);
        if(!$update){
            return json(['code' => 1,'msg'=>"重置密码失败!"    ]);  
        }
        
        Redis::del("HJWTMD5_{$upuser['id']}");//把所有已登录用户踢掉线
        
        return json(['code' => 0,'msg'=>"重置密码成功"    ]);
      
    } 
    
    
    #删除子账户
    public function subdel(Request $request) {
        $uid = $request->user->id;
        $data = $request->post(); 
        Db::name('account')->where('upid',$uid)->where('id',$data['id'])->update(['del' => 1]);  
        return json(['code' => 0,'msg'=>"删除成功" ,'data' => 0 ]);   
    }
    
    
    
    
    
}
