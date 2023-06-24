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
use Webman\RedisQueue\Client; #redis queue 队列

use Casbin\WebmanPermission\Permission;#casbin权限
use Vectorface\GoogleAuthenticator;#谷歌验证

#不确定数量的请求
use GuzzleHttp\Pool;
use GuzzleHttp\Client as Guzz_Client;
use GuzzleHttp\Psr7\Request as Guzz_Request; 
use GuzzleHttp\Promise as Guzz_Promise;

use app\model;

class tgbot{
    
    public function usdtlog_list(Request $request){
      $uid = $request->user->id;
      $bot = $request->user->remark;
      $Coulumn = $request->user->roleId==3?"upid":"myid";
      $data = $request->get();
      $page = ($data['page']-1)*$data['limit']; 
      $jsonVal = array();
      $so =[]; 
      
    //   array_push($so,"del");
    //   array_push($so,'=');
    //   array_push($so,0); 
      
      array_push($so,"bot");
      array_push($so,'=');
      array_push($so,$bot); 
      
      //参数不等于空进行匹配搜索    
      if(!empty($data['state'])){
          if($data['state'] == 1){
              array_push($so,"del");
              array_push($so,'=');
              array_push($so,1);   
          } 
 
      } 
      if(!empty($data['keyword'])){
          array_push($so,$data['t']?$data['t']:'tgid');
          array_push($so,'=');
          array_push($so,$data['keyword']);
      }
      
      if(!empty($data['timea'])){
          array_push($so,"time");
          array_push($so,'>');
          array_push($so,substr($data['timea'],0,10));
          array_push($so,"time");
          array_push($so,'<');
          array_push($so,substr($data['timeb'],0,10));
      }
      
     $so = array_chunk($so,3);//拆分 
     $date = date("Ymd"); 
     $bot_total_d = Db::name('bot_total_d')->where('bot', $bot)->where('dated', $date)->find();
 
     $count = Db::name('bot_usdt_list')->where([$so])->count(); 
     $list = Db::name('bot_usdt_list')->where([$so])->limit($page,$data['limit'])->order('id desc')->select();  
 
     $jsonVal['count'] = $count;
     $jsonVal['list'] = $list;  
     $jsonVal['total'] = $bot_total_d;  
      return json(['code' => 0,'msg'=>"获取成功",'num'=>count($list),'data' => $jsonVal ]);  
    }
    
    
    
    public function bot_user_list(Request $request){
      $uid = $request->user->id;
      $bot = $request->user->remark;
      $Coulumn = $request->user->roleId==3?"upid":"myid";
      $data = $request->get();
      $page = ($data['page']-1)*$data['limit']; 
      $jsonVal = array();
      $so =[]; 
      
    //   array_push($so,"del");
    //   array_push($so,'=');
    //   array_push($so,0); 
      
      array_push($so,"bot");
      array_push($so,'=');
      array_push($so,$bot); 
      
      //参数不等于空进行匹配搜索    
      if(!empty($data['state'])){
          if($data['state'] == 1){
              array_push($so,"del");
              array_push($so,'=');
              array_push($so,1);   
          } 
 
      } 
      if(!empty($data['keyword'])){
          array_push($so,$data['t']?$data['t']:'tgid');
          array_push($so,'=');
          array_push($so,$data['keyword']);
      }
      
      if(!empty($data['timea'])){
          array_push($so,"regtime");
          array_push($so,'>');
          array_push($so,substr($data['timea'],0,10));
          array_push($so,"regtime");
          array_push($so,'<');
          array_push($so,substr($data['timeb'],0,10));
      }
      
     $so = array_chunk($so,3);//拆分 
     
     $date = date("Ymd"); 
     $total_tg_d = Db::name('bot_total_tg')->where('bot', $bot)->where('tgid', 10)->where('date', $date)->find();
      
     $count = Db::name('account_tg')->where([$so])->count(); 
     $list = Db::name('account_tg')->where([$so])->limit($page,$data['limit'])->order('id desc')->select();  
 
     $jsonVal['count'] = $count;
     $jsonVal['list'] = $list; 
     $jsonVal['total'] = $total_tg_d; 
      return json(['code' => 0,'msg'=>"获取成功",'num'=>count($list),'data' => $jsonVal ]);  
    }
    
    
    
    
    
    
    
    
    
    
    
    public function trc20_list(Request $request){
      $uid = $request->user->id;
      $bot = $request->user->remark;
      $Coulumn = $request->user->roleId==3?"upid":"myid";
      $data = $request->get();
      $page = ($data['page']-1)*$data['limit']; 
      $jsonVal = array();
      $so =[]; 
      
      array_push($so,"del");
      array_push($so,'=');
      array_push($so,0); 
      
      array_push($so,"bot");
      array_push($so,'=');
      array_push($so,$bot); 
      
      //参数不等于空进行匹配搜索    
      if(!empty($data['state'])){
          if($data['state'] == 1){
              array_push($so,"tgid");
              array_push($so,'=');
              array_push($so,0);   
          }else if($data['state'] == 2){
              array_push($so,"disable");
              array_push($so,'=');
              array_push($so,1);   
          }  
 
      } 
      if(!empty($data['keyword'])){
          array_push($so,$data['t']?$data['t']:'trc20');
          array_push($so,'=');
          array_push($so,$data['keyword']);
      }
      
      if(!empty($data['timea'])){
          array_push($so,"regtime");
          array_push($so,'>');
          array_push($so,substr($data['timea'],0,10));
          array_push($so,"regtime");
          array_push($so,'<');
          array_push($so,substr($data['timeb'],0,10));
      }
      
     $so = array_chunk($so,3);//拆分 
     
 
     $count = Db::name('bot_total_trc20')->where([$so])->count(); 
     $list = Db::name('bot_total_trc20')->where([$so])->limit($page,$data['limit'])->order('id desc')->select();  
 
     $jsonVal['count'] = $count;
     $jsonVal['list'] = $list;  
      return json(['code' => 0,'msg'=>"获取成功",'num'=>count($list),'data' => $jsonVal ]);  
    }
    
    
    
    
    
    
    
    
    public function group_list(Request $request){
      $uid = $request->user->id;
      $bot = $request->user->remark;
      $Coulumn = $request->user->roleId==3?"upid":"myid";
      $data = $request->get();
      $page = ($data['page']-1)*$data['limit']; 
      $jsonVal = array();
      $so =[]; 
      
      array_push($so,"del");
      array_push($so,'=');
      array_push($so,0); 
      
      array_push($so,"bot");
      array_push($so,'=');
      array_push($so,$bot); 
      
      //参数不等于空进行匹配搜索    
      if(!empty($data['state'])){
          if($data['state'] == 1){
              array_push($so,"vip");
              array_push($so,'=');
              array_push($so,1);   
          }else if($data['state'] == 2){
              array_push($so,"vip");
              array_push($so,'=');
              array_push($so,0);   
          }  
 
      } 
      if(!empty($data['keyword'])){
          array_push($so,$data['t']?$data['t']:'groupid');
          array_push($so,'=');
          array_push($so,$data['keyword']);
      }
      
      if(!empty($data['timea'])){
          array_push($so,"time");
          array_push($so,'>');
          array_push($so,substr($data['timea'],0,10));
          array_push($so,"time");
          array_push($so,'<');
          array_push($so,substr($data['timeb'],0,10));
      }
      
     $so = array_chunk($so,3);//拆分 
     
 
     $count = Db::name('bot_group')->where([$so])->count(); 
     $list = Db::name('bot_group')->where([$so])->limit($page,$data['limit'])->order('id desc')->select();  
 
     $jsonVal['count'] = $count;
     $jsonVal['list'] = $list;  
      return json(['code' => 0,'msg'=>"获取成功",'num'=>count($list),'data' => $jsonVal ]);  
    }
    
    
    
    
    
    
    
    
    
    
    
    

    public function command_list(Request $request){
      $uid = $request->user->id;
      $bot = $request->user->remark;
      $Coulumn = $request->user->roleId==3?"upid":"myid";
      $data = $request->get();
      $data['page'] = 1;
      $data['limit'] = 50;
      $page = ($data['page']-1)*$data['limit']; 
      $jsonVal = array();
      $so =[]; 
      
      array_push($so,"del");
      array_push($so,'=');
      array_push($so,0); 
      
      array_push($so,"bot");
      array_push($so,'=');
      array_push($so,$bot); 
      
      if(empty($data['reply_markup'])){
          array_push($so,"reply_markup");
          array_push($so,'=');
          array_push($so,"inline_keyboard");    
      }else{
          array_push($so,"reply_markup");
          array_push($so,'=');
          array_push($so,$data['reply_markup']);    
      } 
       
      
      //参数不等于空进行匹配搜索    
    //   if(!empty($data['state'])){
    //       if($data['state'] == 1){
    //           array_push($so,"tgid");
    //           array_push($so,'=');
    //           array_push($so,0);   
    //       }else if($data['state'] == 2){
    //           array_push($so,"disable");
    //           array_push($so,'=');
    //           array_push($so,1);   
    //       }  
 
    //   } 
    //   if(!empty($data['keyword'])){
    //       array_push($so,$data['t']?$data['t']:'trc20');
    //       array_push($so,'=');
    //       array_push($so,$data['keyword']);
    //   }
      
    //   if(!empty($data['timea'])){
    //       array_push($so,"regtime");
    //       array_push($so,'>');
    //       array_push($so,substr($data['timea'],0,10));
    //       array_push($so,"regtime");
    //       array_push($so,'<');
    //       array_push($so,substr($data['timeb'],0,10));
    //   }
      
     $so = array_chunk($so,3);//拆分 
     
 
     $count = Db::name('bot_commands')->where([$so])->count(); 
     $list = Db::name('bot_commands')->where([$so])->limit($page,$data['limit'])->order('id desc')->select();  
 
     $jsonVal['count'] = $count;
     $jsonVal['list'] = $list;  
      return json(['code' => 0,'msg'=>"获取成功",'num'=>count($list),'data' => $jsonVal ]);  
    }  
    
    
    
    public function commands_list(Request $request){
      $uid = $request->user->id;
      $bot = $request->user->remark;
      $Coulumn = $request->user->roleId==3?"upid":"myid";
      $data = $request->get();
      $data['page'] = 1;
      $data['limit'] = 50;
      $page = ($data['page']-1)*$data['limit']; 
      $jsonVal = array();
      $so =[]; 
      
      array_push($so,"del");
      array_push($so,'=');
      array_push($so,0); 
      
      array_push($so,"bot");
      array_push($so,'=');
      array_push($so,$bot);  
      
      array_push($so,"chatType");
      array_push($so,'=');
      array_push($so,$data['chatType']);
      
      
      if(empty($data['type'])){
          array_push($so,"type");
          array_push($so,'=');
          array_push($so,1); 
      }else{
          array_push($so,"type");
          array_push($so,'=');
          array_push($so,$data['type']); 
      } 
  
 
      
     $so = array_chunk($so,3);//拆分 
     
 
     $count = Db::name('bot_commands')->where([$so])->count(); 
     $list = Db::name('bot_commands')->where([$so])->limit($page,$data['limit'])->order('id desc')->select();  
 
     $jsonVal['count'] = $count;
     $jsonVal['list'] = $list;  
      return json(['code' => 0,'msg'=>"获取成功",'num'=>count($list),'data' => $jsonVal ]);  
    } 
    
    
    public function comclass_list(Request $request){
        $data = $request->get();
        $bot = $request->user->remark;
        $so =[]; 
        array_push($so,"del");
        array_push($so,'=');
        array_push($so,0); 
        
        array_push($so,"plugin");
        array_push($so,'=');
        array_push($so,$request->user->plugin); 
        $so = array_chunk($so,3);//拆分 
      
        $list = Db::name('bot_comclass')->where([$so])->whereIn("chatType","all,{$data['chatType']}")->select();  
        $jsonVal['list'] = $list;  
        return json(['code' => 0,'msg'=>"获取成功",'num'=>count($list),'data' => $jsonVal ]); 
        
    }
    
    public function comtype_list(Request $request){
        $data = $request->get();
        $bot = $request->user->remark;
        $so =[]; 
        array_push($so,"plugin");
        array_push($so,'=');
        array_push($so,$request->user->plugin);  
        
        $so = array_chunk($so,3);//拆分  
        $list = Db::name('bot_comtype')->where([$so])->select();  
        $jsonVal['list'] = $list;  
        return json(['code' => 0,'msg'=>"获取成功",'num'=>count($list),'data' => $jsonVal ]); 
        
    }
    
    
    public function command_detail(Request $request){ //这个地方可能存在BUG 缺少bot 
      $uid = $request->user->id;
      $bot = $request->user->remark;
      $Coulumn = $request->user->roleId==3?"upid":"myid";
      $data = $request->get();
      $so =[]; 
       
      array_push($so,"del");
      array_push($so,'=');
      array_push($so,0); 
      
      array_push($so,"bot");
      array_push($so,'=');
      array_push($so,$bot); 
      
      array_push($so,"comId");
      array_push($so,'=');
      array_push($so,$data['comId']); 
      
      array_push($so,"type");
      array_push($so,'=');
      array_push($so,$data['type']); 
      
      $so = array_chunk($so,3);//拆分 
      $list = Db::name('bot_markup')->where([$so])->order('sortId asc')->select();   
 
      
    $keyboard=[];
    $d1 = array();
 
    foreach ($list as $value) {   
        if(empty($value['class']) && $data['type']!="keyboard"){ 
            continue; 
            
        } 
        if(!array_key_exists($value['aid'],$d1)){
            $d1[$value['aid']] = [];
        } 
        array_push($d1[$value['aid']],$value);
        // if(!empty($value['class'])){
        //     $d2['text'] = $value['text'];
        //     if($value['class'] == "web_app" || $value['class'] == "login_url"){
        //         $class['url']=$value[$value['class']]; //json
        //         $d2[$value['class']] = $class; 
        //     }else{
        //         $d2[$value['class']] = $value[$value['class']];//对应字段的值
        //     }  
        //     array_push($d1[$value['aid']],$d2);
            
        // }else{
        //     array_push($d1[$value['aid']],["text"=>$value['text']]);
        // }
      
    } 
 
    $keyboard = array_values($d1); 
    
  
      
      $jsonVal['list'] = $list;  
      return json(['code' => 0,'msg'=>"获取成功",'num'=>count($list),'data' => $keyboard ]);  
    }
    
    
    
    
    
    
    
    public function command_markup(Request $request){
      $uid = $request->user->id;
      $bot = $request->user->remark;
      $Coulumn = $request->user->roleId==3?"upid":"myid";
      $data = $request->post();
      $so =[];  
    
 
      
      array_push($so,"del");
      array_push($so,'=');
      array_push($so,0);  
      $so = array_chunk($so,3);//拆分 
 
      
      $add = [];
      $update = [];
      
      //$bot_commands = model\bot_commands::find($data['id']);
      $bot_commands = model\bot_commands::where('bot', $bot)->where('id', $data['id'])->find();
      
      if(empty($bot_commands)){
          return json(['code' => 1,'msg'=>"对应数据不存在" ]); 
      }
      
      
      foreach ($data['list'] as $index => $val){  
          
          foreach ($val as $index2 => $val2 ){ 
              if(empty($val2['class'])){
                  return json(['code' => 1,'msg'=>"未选择按钮事件类型" ]);   
              } 
              if(empty($val2['id'])){//新加入
                $sql['bot'] = $bot;
                $sql['comId'] = $bot_commands['id'];
                $sql['chatType'] = $bot_commands['chatType'];
                $sql['type'] = $bot_commands['reply_markup'];
                $sql['aid'] = $index + 1 ;
                $sql['sortId'] = $sql['aid']  * 10 + $index2;
                $sql['text'] = $val2['text'];
                $sql['class'] = $val2['class'];
                $sql[$val2['class']] = $val2['url']??"";
                array_push($add,$sql); 
                
              }else{//update   
                if($val2['bot'] != $bot){
                    continue;   
                }
              
                $val2['class'] = $val2['class'];
                $val2[$val2['class']] = $val2['url']??"";
                $val2['aid'] = $index + 1 ;
                $val2['sortId'] = $val2['aid']  * 10 + $index2;
                array_push($update,$val2);  
              } 
          }
          
           
          
      }
      
        $bot_markup = new model\bot_markup; 
        if(count($update) > 0){
            $bot_markup->saveAll($update);  
        }
        if(count($add) > 0){
           $bot_markup->saveAll($add);  
        } 
       
       Cache::delete("bot_markup_select_{$bot_commands['id']}"); //删除缓存
        
       return json(['code' => 0,'msg'=>"按钮同步更新成功"   ]);  
    }
    
    
    
    public function markup_del(Request $request){
      $uid = $request->user->id;
      $bot = $request->user->remark;
      $Coulumn = $request->user->roleId==3?"upid":"myid";
      $data = $request->post(); 
      
    //   model\bot_markup::where('id',$data['id'])->update(['del'=>1]);
      //model\bot_markup::update(['del' => 1],['id' => $data['id']])->cache("bot_markup_select_{$data['comId']}");
      Cache::delete("bot_markup_select_{$data['comId']}");
      Db::name('bot_markup')->where('id',$data['id'])->where('bot',$bot)->update(['del' => 1]);  #更新

      
      return json(['code' => 0,'msg'=>"按钮删除成功" ]);  
    }
    
    
    public function command_add(Request $request){
      $uid = $request->user->id;
      $bot = $request->user->remark;
      $Coulumn = $request->user->roleId==3?"upid":"myid";
      $data = $request->post();
      
      
      $bot_commands = model\bot_commands::where('bot', $bot)->where('command', '消息事件')->where('type', 0)->find();
      if(empty($bot_commands)){
          return json(['code' => 1,'msg'=>"该机器人没有消息事件主体- 请重新初始化机器人"   ]);      
      }
      
      Cache::delete("{$bot}_{$data['command']}_{$data['chatType']}_2");
      
       
      
      if(empty($data['id'])){
          $commands = model\bot_commands::where('bot', $bot)->where('command', $data['command'])->where('chatType', $data['chatType'])->find();
          $data['bot'] = $bot;
          $data['parentId'] = $bot_commands['id'];
          $data['type'] = 2;
          $data['reply_markup'] = 'inline_keyboard';
          model\bot_commands::create($data);
          return json(['code' => 0,'msg'=>"添加成功" ]); 
          
      }else{
          $commands = model\bot_commands::where('id', $data['id'])->find();
          unset($data['id']); 
          //model\bot_commands::update($data,['id' => $commands['id']]);
          Db::name('bot_commands')->where('id',$commands['id'])->where('bot',$bot)->update($data);  #更新
          
          if($data['type'] == 1){//菜单命令
              $queueData['plugin'] = $request->user->plugin; 
              $queueData['API_BOT'] = $bot;
              $queueData['type'] = "commands";
              $queueData['data'] = $data['chatType'];
              Client::send('TG_queue',$queueData);     
          }
          
          
          return json(['code' => 0,'msg'=>"修改成功" ]); 
      }
        
       
      
        
    }
    
    
    public function commands_add(Request $request){
      $uid = $request->user->id;
      $bot = $request->user->remark;
      $Coulumn = $request->user->roleId==3?"upid":"myid";
      $data = $request->post();
      
      
      $bot_commands = model\bot_commands::where('bot', $bot)->where('command', "菜单命令")->where('type', 0)->find();
      if(empty($bot_commands)){
          return json(['code' => 1,'msg'=>"该机器人没有菜单命令主体- 请重新初始化机器人"   ]);      
      }
      
      
      Cache::delete("{$bot}_{$data['command']}_{$data['chatType']}_1");  
 
      
      if(empty($data['id'])){
          $commands = model\bot_commands::where('bot', $bot)->where('command', $data['command'])->where('type',1)->find();
          $data['bot'] = $bot;
          $data['parentId'] = $bot_commands['id']; 
          $data['type'] = 1;
          $data['reply_markup'] = 'inline_keyboard';
          model\bot_commands::create($data);
          
          $queueData['plugin'] = $request->user->plugin; 
          $queueData['API_BOT'] = $bot;
          $queueData['type'] = "commands";
          $queueData['data'] = $data['chatType'];
          Client::send('TG_queue',$queueData); 
          
          return json(['code' => 0,'msg'=>"添加成功" ]); 
          
      }else{
          $commands = model\bot_commands::where('id', $data['id'])->find();
          unset($data['id']); 
          
          Db::name('bot_commands')->where('id',$commands['id'])->where('bot',$bot)->update($data);  #更新
          
          //model\bot_commands::update($data,['id' => $commands['id']]);
          
          $queueData['plugin'] = $request->user->plugin; 
          $queueData['API_BOT'] = $bot;
          $queueData['type'] = "commands";
          $queueData['data'] = $commands['chatType'];
          Client::send('TG_queue',$queueData); 
          
          return json(['code' => 0,'msg'=>"修改成功" ]); 
      }  
    }
    
    
    
    public function command_del(Request $request){
      $uid = $request->user->id;
      $bot = $request->user->remark;
      $Coulumn = $request->user->roleId==3?"upid":"myid";
      $data = $request->post();
      
      cache::delete("{$bot}_{$data['command']}_{$data['chatType']}");
      model\bot_commands::update(["del"=>1],['id' => $data['id']]);
      
      if($data['type'] == 1){//菜单命令
          $queueData['plugin'] = $request->user->plugin; 
          $queueData['API_BOT'] = $bot;
          $queueData['type'] = "commands";
          $queueData['data'] = $data['chatType'];
          Client::send('TG_queue',$queueData);    
      }
           
       return json(['code' => 0,'msg'=>"删除成功" ]); 
      } 
      
      
      
      
      
      
      
      
      
      
    public function send_Msg(Request $request){
      $uid = $request->user->id;
      $bot = $request->user->remark;
      $Coulumn = $request->user->roleId==3?"upid":"myid";
      $data = $request->post();
      $botlist = Db::name('bot_list')->where("plugin",$request->user->plugin)->where('bot',$bot)->cache("{$request->user->plugin}_{$bot}")->find();  
      if(empty($botlist)){
          return json(['code' => 1,'msg'=>"机器人数据获取失败" ]);  
      }
      
      if(empty($data['text']) || empty($data['tgid'])){
          return json(['code' => 1,'msg'=>"获取用户ID失败或消息内容为空" ]);  
      } 
      
      $client = new Guzz_Client(['timeout' => 8,'http_errors' => false]); 
      $res = json_decode($client->request('GET', "{$botlist['API_URL']}{$botlist['API_TOKEN']}/sendMessage?chat_id={$data['tgid']}&text={$data['text']}&parse_mode=HTML&allow_sending_without_reply=true&disable_web_page_preview=true")->getBody(),true); 
        if(empty($res['ok'])){
            return json(['code' => 1,'msg'=>"消息发送失败,{$res['description']}" ]);   
        }else{
            return json(['code' => 0,'msg'=>"消息发送成功" ]);
        } 
    }  
    
    
    
    public function bot_tuiqun(Request $request){
      $uid = $request->user->id;
      $bot = $request->user->remark;
      $Coulumn = $request->user->roleId==3?"upid":"myid";
      $data = $request->post(); 
      
      if(empty($data['qunid'])){
          return json(['code' => 1,'msg'=>"缺少群ID参数" ]);  
      } 
      
      $botlist = Db::name('bot_list')->where("plugin",$request->user->plugin)->where('bot',$bot)->cache("{$request->user->plugin}_{$bot}")->find();  
      if(empty($botlist)){
          return json(['code' => 1,'msg'=>"机器人数据获取失败" ]);  
      }
      
      $client = new Guzz_Client(['timeout' => 8,'http_errors' => false]); 
      $res = json_decode($client->request('GET', "{$botlist['API_URL']}{$botlist['API_TOKEN']}/leaveChat?chat_id={$data['qunid']}")->getBody(),true); 
        if(empty($res['ok'])){
            return json(['code' => 1,'msg'=>"退群失败,{$res['description']}" ]);   
        }else{
            return json(['code' => 0,'msg'=>"机器人退群成功" ]);
        } 
    } 
    
    
    
    public function group_update_zt(Request $request){
      $uid = $request->user->id;
      $bot = $request->user->remark;
      $Coulumn = $request->user->roleId==3?"upid":"myid";
      $data = $request->post(); 
      if(empty($data['id']) || empty($data['val']) ){
          return json(['code' => 1,'msg'=>"参数异常" ]);  
      }
      
      Db::name('bot_group')->where('plugin',$request->user->plugin)->where('bot',$bot)->where('id',$data['id'])->update([$data['val']=>(int)$data['zt'] ]);
      return json(['code' => 0,'msg'=>"操作成功" ]);
      
    }
    

    #TRX兑换配置信息获取
    public function bot_get_trxsetup(Request $request){
      $uid = $request->user->id;
      $bot = $request->user->remark;
      $Coulumn = $request->user->roleId==3?"upid":"myid"; 
      
      $botlist = Db::name('trx_setup')->where("plugin",$request->user->plugin)->where('bot',$bot)->cache("trx_{$bot}_setup")->find();  
      
      return json(['code' => 0,'msg'=>"操作成功",'data'=>$botlist ]);  
    } 
    
    
    #TRX兑换配置信息修改
    public function bot_trx_setup(Request $request){
      $uid = $request->user->id;
      $bot = $request->user->remark;
      $Coulumn = $request->user->roleId==3?"upid":"myid"; 
      $data = $request->post();  
      $data['addr']="";
      Db::name('trx_setup')->where('plugin',$request->user->plugin)->where('bot',$bot)->update($data);  
      Cache::delete("trx_{$bot}_setup");
      
      return json(['code' => 0,'msg'=>"修改成功",'data'=>0 ]);  
      
    } 
    
    
    #机器人获取信息
    public function bot_getmy(Request $request){
      $uid = $request->user->id;
      $bot = $request->user->remark;
      $Coulumn = $request->user->roleId==3?"upid":"myid";
/*      $data = $request->get(); 
      if(empty($data['id']) || empty($data['val']) ){
          return json(['code' => 1,'msg'=>"参数异常" ]);  
      }*/
      $botlist = Db::name('bot_list')->where("plugin",$request->user->plugin)->where('API_BOT',$bot)->cache("{$request->user->plugin}_{$bot}")->find();  
      if(empty($botlist)){
          return json(['code' => 1,'msg'=>"机器人数据获取失败" ]);  
      }
      $ret['API_BOT'] = $botlist['API_BOT'];
      $ret['API_TOKEN'] = $botlist['API_TOKEN'];
      $ret['Admin'] = $botlist['Admin'];
      
      $client = new Guzz_Client(['timeout' => 8,'http_errors' => false]);   
      $promises = [
          'getMe' => $client->getAsync("{$botlist['API_URL']}{$botlist['API_TOKEN']}/getMe"),
          'getAdmin' => $client->getAsync("{$botlist['API_URL']}{$botlist['API_TOKEN']}/getChat?chat_id=".$botlist['Admin'])
       ]; 
       $results = Guzz_Promise\unwrap($promises);//并发异步请求
       if($results['getMe']){
           $res = json_decode($results['getMe']->getBody()->getContents(),true);  
           if(empty($res['ok'])){
               return json(['code' => 1,'msg'=>"获取机器人信息失败"  ]); 
           }
           $ret['NAME'] =  $res['result']['first_name'];
           $ret['neilian']  =$res['result']['supports_inline_queries'];   
       }
       
       if($results['getAdmin']){
           $res = json_decode($results['getAdmin']->getBody()->getContents(),true);  
           if(empty($res['ok'])){
               return json(['code' => 1,'msg'=>"获取管理员信息失败"  ]); 
           }
            
         $ret['AdminUser'] = $res['result']['username']??"未设置";
           $ret['AdminName']  =$res['result']['first_name']??""; 
           $ret['AdminName']  .=$res['result']['last_name']??""; 
       }
      
      
      return json(['code' => 0,'msg'=>"操作成功",'data'=>$ret ]);
      
      
      
    } 
    
    
    #机器人信息设置
    public function bot_setup(Request $request){
      $uid = $request->user->id;
      $bot = $request->user->remark;     
      $Coulumn = $request->user->roleId==3?"upid":"myid";
      $data = $request->post(); 
      $botlist = Db::name('bot_list')->where("plugin",$request->user->plugin)->where('API_BOT',$bot)->cache("{$request->user->plugin}_{$bot}")->find();  
      
    
      $client = new Guzz_Client(['timeout' => 8,'http_errors' => false]);   
      $promises = [ 
          'setname' => $client->getAsync("{$botlist['API_URL']}{$botlist['API_TOKEN']}/setMyName?name=".$data['NAME']),
          'getAdmin' => $client->getAsync("{$botlist['API_URL']}{$botlist['API_TOKEN']}/getChat?chat_id=".$data['Admin'])
       ]; 
       $results = Guzz_Promise\unwrap($promises);//并发异步请求 
       
        
        if($results['getAdmin']){
           $res = json_decode($results['getAdmin']->getBody()->getContents(),true);  
           if(empty($res['ok'])){ 
               return json(['code' => 1,'msg'=>"你设定的管理员尚未关注机器人"  ]); 
           }
            
         $ret['AdminUser'] = $res['result']['username']??"未设置";
         $ret['AdminName']  =$res['result']['first_name']??""; 
         $ret['AdminName']  .=$res['result']['last_name']??""; 
         Db::name('bot_list')->where('plugin',$request->user->plugin)->where('API_BOT',$bot)->update(["Admin"=>$data['Admin'] ]);  
         Cache::delete("{$request->user->plugin}_{$bot}");
       }
      
      
      
      
       
      return json(['code' => 0,'msg'=>"操作成功",'data'=>"" ]);
    }
    
     
    
}