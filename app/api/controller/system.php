<?php

namespace app\api\controller;

use support\Request;

use Shopwwi\WebmanAuth\Facade\Auth;#jwt https://www.workerman.net/plugin/24
use think\facade\Db;#mysql https://www.kancloud.cn/manual/think-orm/1258003
use think\facade\Cache;#Cache https://www.kancloud.cn/manual/thinkphp6_0/1037634
use Respect\Validation\Exceptions\ValidationException;#字符验证器捕获错误

use Webman\Push\Api; //push推送
use support\Redis;//redis缓存
use Gregwar\Captcha\CaptchaBuilder;#Captcha 验证码
#use Webman\RedisQueue\Client; #redis queue 队列

use Vectorface\GoogleAuthenticator;#谷歌验证

class system{
    
    #初始化获取用户数据  菜单 角色 权限
    public function listMenus(Request $request){   
        
        $data = $request->get();
        $so =[];
        array_push($so,"del");
        array_push($so,'=');
        array_push($so,0);
        
        array_push($so,"tenantId ");
        array_push($so,'=');
        array_push($so,$data['tenantId']);
        
        if(!empty($data['title'])){
            array_push($so,"title");
            array_push($so,'like');
            array_push($so,"%{$data['title']}%");    
        }
        if(!empty($data['path'])){
            array_push($so,"path");
            array_push($so,'like');
            array_push($so,"%{$data['path']}%");    
        }
        if(!empty($data['authority'])){
            array_push($so,"authority");
            array_push($so,'like');
            array_push($so,"%{$data['authority']}%");    
        }
          
        $so = array_chunk($so,3);//拆分
        $menu = Db::table('sys_menu')->where([$so])->order('sortNumber asc')->select();  
        return json(['code'=>0,'msg'=>'操作成功','data'=>$menu]); 
    }
        
        
        
    #初始化获取用户数据  菜单 角色 权限
    public function rolelist(Request $request){   
      $uid = $request->user->id;
      $data = $request->get();
      $page = ($data['page']-1)*$data['limit'];
      $so =[];
      $jsonVal = array();
      array_push($so,"del");
      array_push($so,'=');
      array_push($so,0);
      
      if(!empty($data['roleName'])){
          array_push($so,"roleName");
          array_push($so,'=');
          array_push($so,$data['roleName']);    
      }
      if(!empty($data['roleCode'])){
          array_push($so,"roleCode");
          array_push($so,'=');
          array_push($so,$data['roleCode']);    
      }
      if(!empty($data['comments'])){
          array_push($so,"comments");
          array_push($so,'=');
          array_push($so,$data['comments']);    
      }
      
      $so = array_chunk($so,3);//拆分 
      
      $count = Db::table('sys_role')->where([$so])->count(); 
      $list = Db::table('sys_role')->where([$so])->limit($page,$data['limit'])->order('roleId desc')->select(); 
      
      $jsonVal['count'] = $count;
      $jsonVal['list'] = $list;
          return json(['code'=>0,'msg'=>'操作成功','data'=>$jsonVal]); 
        }  
        
        
    public function role(Request $request){
        $data = $request->post(); 
        $method =$request->method();
        switch ($method) {
            case 'PUT':
                $save = Db::table('sys_role')->save($data);
                return json(['code'=>0,'msg'=>'修改角色成功']); 
                break;
                
            case 'POST':
                $save = Db::table('sys_role')->insert($data);
                return json(['code'=>0,'msg'=>'新增角色成功']); 
                break; 
            
            default:
                return json(['code'=>1,'msg'=>'不支持该操作']); 
                break;
        }
         
        
         
        
    }
        
        
    #角色权限    
    public function rolemenu(Request $request,$id){
        $data = $request->post(); 
        // if($id == 4){
        //     return json(['code'=>1,'msg'=>'请在[子账户管理] 单独设置每个子账户的权限!' ]);  
        // }
        
        if(empty($data)){#读取角色权限
        
            $role = Db::table('sys_role')->where('roleId', $id)->find(); 
            $menu = Db::table('sys_menu')->where([["tenantId","=",$role['tenantId']],["del","=",0]])->order('sortNumber asc')->select();  
            $a= array_column($menu->toArray(), null, 'menuId');        
            #数据表 方式2
            $menus = Db::table('sys_role_menu')->where([["tenantId","=",$role['tenantId']],['roleId','=',$id],['userId','=',0]])->find();
             if($menus){
                 foreach (json_decode($menus['menuText'],true) as $val1) {   
                     if(!empty($a[$val1])){
                     $a[$val1]['checked'] = true; 
                     }
                 } 
             }
            return json(['code'=>0,'msg'=>'操作成功','data'=>array_values($a)  ]);    
      
        }else{#修改角色权限   
            $menuText = json_encode($data);
        
            $role = Db::table('sys_role')->where('roleId', $id)->find();
            if(empty($role)){
               return json(['code'=>1,'msg'=>'角色不存在或已禁用' ]);  
            }
            
            if($id == 3){
                $menuText = str_replace("40,", "", json_encode($data));    
            }
            
            $sys_role_menu = Db::table('sys_role_menu')->where([['userId','=',0],['roleId','=',$role['roleId']],['tenantId','=',$role['tenantId']]])->find();
            if(empty($sys_role_menu)){ //新增
                $sql['userId'] = 0;
                $sql['roleId'] = $id;
                $sql['tenantId'] = $role['tenantId'];
                $sql['menuText'] = json_encode($data);
                $save = Db::table('sys_role_menu')->insert($sql);
            }else{//更新
                $save = Db::table('sys_role_menu')->where([['userId','=',0],['roleId','=',$role['roleId']],['tenantId','=',$role['tenantId']]])->update(['menuText' =>$menuText]);
            }
            
            Redis::del("casbin_{$role['roleId']}");
            Redis::del("submenu");
            return json(['code'=>0,'msg'=>'修改成功','data'=>count($data) ]);   
        }      
    } 
    
    #菜单新增&修改 删除
    public function menu (Request $request,$id = 0) {
        $data = $request->post(); 
        if($request->method() == "DELETE"){
            $update = Db::table('sys_menu')->where('menuId',$id)->update(['del' => 1]);  #更新  
            return json(['code'=>0,'msg'=>"删除成功",'data'=> $id ]);       
        } 
      $save = Db::table('sys_menu')->save($data);
      if($save){
        $zt = 0;  
        $msg = '操作成功';
      }else{
        $zt = 1; 
        $msg = '操作失败';
      }     
     return json(['code'=>$zt,'msg'=>$msg,'data'=>$save ]);      
    }   
    
    
    
    #用户管理 用户列表    
    public function userlist (Request $request) {
      $uid = $request->user->id;
      $data = $request->get();
      $page = ($data['page']-1)*$data['limit'];
      $so =[];
      $jsonVal = array();
      array_push($so,"del");
      array_push($so,'=');
      array_push($so,0);
      array_push($so,"id");
      array_push($so,'>');
      array_push($so,100000);
      
      if(!empty($data['username'])){
          array_push($so,"username");
          array_push($so,'like');
          array_push($so,"%{$data['username']}%");    
      } 
      
      
      $so = array_chunk($so,3);//拆分 
      
      $count = Db::name('account')->where([$so])->count(); 
      $list = Db::name('account')->where([$so])->limit($page,$data['limit'])->order('id desc')->select(); 
      $jsonVal['count'] = $count;
      $jsonVal['list'] = $list;
        return json(['code'=>0,'msg'=>"ok",'data'=>$jsonVal ]); 
        
    }
    
    #用户管理 新增用户    
    public function user (Request $request) {
        $uid = $request->user->id; 
        $data = $request->post();
        if(empty($data['com'])){
            return json(['code'=>1,'msg'=>"操作命令不能为空" ]);  
        }
        if($data['com'] == 'add'){
            $key = strtoupper(md5($request->sessionId().rand(1,999)));
            $ga = new GoogleAuthenticator();
            $secret = $ga->createSecret();#生成谷歌密匙
            $data['data']['regtime'] = time(); 
            $data['data']['key'] = $key; 
            $data['data']['tenantId'] = 1;//租户ID 
            $data['data']['SecretKey'] = $secret; 
            $data['data']['password'] = md5($data['data']['password']); 
            $save = Db::name('account')->insert($data['data']);
            return json(['code'=>0,'msg'=>"新增成功" ]);    
        }
        if($data['com'] == 'edit'){  
            if($data['data']['password']){
                $data['data']['password'] = md5($data['data']['password']);     
            }else{
                unset($data['data']['password']);
            }
            $save = Db::name('account')->save($data['data']);
            return json(['code'=>0,'msg'=>"修改成功" ]);    
        }
        
        if($data['com'] == 'del'){  
            Db::name('account')->whereIn('id',$data['id'])->update(['del' => 1]);
            return json(['code'=>0,'msg'=>"删除成功" ]);    
        }
        
        if($data['com'] == 'updatezt'){  
            Db::name('account')->where('id',$data['id'])->update(['api' => $data['zt']]);
            return json(['code'=>0,'msg'=>"状态已变更" ]);    
        }
        
        
        return json(['code'=>0,'msg'=>"ok",'data'=>"" ]); 
        
        
        
    }
    
    #租户列表
    public function tencntList (Request $request) { 
        $rolelist = Db::table('sys_tenantId')->where('del',0)->limit(0,20)->select(); 
        $Idmenu= array_column($rolelist->toArray(), null, 'tenantId');
        return json(['code'=>0,'msg'=>"ok",'list'=>$Idmenu ]);    
    }
    
    
    
    
    #用户管理 下拉框 获取角色列表    
    public function rolelistS (Request $request) {
        $uid = $request->user->id;
        
        $rolelist = Db::table('sys_role')->where('del',0)->limit(0,10)->order('roleId desc')->select();  
        return json(['code'=>0,'msg'=>"ok",'list'=>$rolelist ]);    
    }
    
    
    #用户管理 重置密码为123456    
    public function updateUserPassword (Request $request) {
        $uid = $request->user->id;
        $data = $request->post();
        Db::name('account')->where('id',$data['id'])->update(['password' => md5("123456")]);
        return json(['code'=>0,'msg'=>"重置密码成功" ]);    
    }
    
    #用户管理 重置数据    
    public function resetData (Request $request) {
        $uid = $request->user->id;
        $data = $request->post(); 
        
        $account = Db::name('account')->where('id',$data['id'])->find();
        if($account['roleId'] != 2 ){
            return json(['code'=>1,'msg'=>"无法重置该用户数据,因为他不是商户" ]);   
        }
        
        Db::name('account')->where('id',$data['id'])->update(['smoney' =>0,'tmoney' =>0,'ddnumber' =>0,'sxf' =>0,'etc' =>0]);//用户表
        Db::name('err_order')->where('myid',$data['id'])->update(['del'=>1]);//err_order
        Db::name('pay_log')->where('myid',$data['id'])->update(['del' =>1]);//pay_log
        #Db::name('pay_qudao')->where('myid',$data['id'])->where('upid',0)->update(['del' =>1]);//pay_qudao
        Db::name('timelog')->where('myid',$data['id'])->update(['del' =>1]);//timelog
        Db::name('today_log')->where('myid',$data['id'])->update(['del' =>1]);//today_log
        //Db::name('today_log')->where('myid',$data['id'])->where('date',date("Ymd",time()))->update(['del' =>0,'num' =>0,'oknum' =>0,'money' =>0,'okmoney' =>0]);//today_log 
        Db::name('today_qd_log')->where('myid',$data['id'])->update(['del' =>1]);//today_qd_log
        Db::name('money_log')->where('myid',$data['id'])->update(['del' =>1]);// 
        Db::name('tixianlog')->where('myid',$data['id'])->update(['del' =>1]);// 
        Db::name('pay_qudao')->where('myid',$data['id'])->where('del',0)->update(['zt' => 0,'date' => 0,'ddnumber' => 0,'oknumber' => 0,'okmoney' => 0,'zmoney' => 0,'tmoney' => 0,'sxf' => 0,'etc' => 0,'time' => time()]);//pay_qudao
        
        return json(['code'=>0,'msg'=>"重置数据成功" ]);    
    }
    
    
    
    
         
}         
         