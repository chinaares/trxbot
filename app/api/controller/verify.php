<?php

namespace app\api\controller;

use support\Request;
use support\Response;
use Exception;

use Tntma\Tntjwt\Auth;
#use Shopwwi\WebmanAuth\Facade\Auth;#jwt https://www.workerman.net/plugin/24
#use Shopwwi\WebmanAuth\Exception\JwtTokenException;
use think\facade\Db;#mysql https://www.kancloud.cn/manual/think-orm/1258003
use think\facade\Cache;#Cache https://www.kancloud.cn/manual/thinkphp6_0/1037634
use Respect\Validation\Exceptions\ValidationException;#字符验证器捕获错误
use support\Log;

use Webman\Push\Api; //push推送
use support\Redis;//redis缓存
use Webman\Captcha\CaptchaBuilder;#Captcha 验证码
use Webman\RedisQueue\Client; #redis queue 队列

use Casbin\WebmanPermission\Permission;#casbin权限
use Vectorface\GoogleAuthenticator;#谷歌验证

use Workerman\Timer;
use \Shopwwi\WebmanSearch\Facade\Search;
use Elastic\Elasticsearch\ClientBuilder;

use app\queue\rabbitmq; 

use Hashids\Hashids; 
use Workerman\Worker;

// use GuzzleHttp\Client as guzz ;
// use GuzzleHttp\Promise;


#不确定数量的请求
use GuzzleHttp\Pool;
use GuzzleHttp\Client as Guzz_Client;
use GuzzleHttp\Psr7\Request as Guzz_Request; 
use GuzzleHttp\Cookie\CookieJar;


use GatewayWorker\Lib\Gateway;

use Tinywan\Storage\Storage;
use Tinywan\Storage\Exception\StorageException;

use app\service\CommonService;

use TNTma\TronWeb\Address;
use TNTma\TronWeb\Account;
use TNTma\TronWeb\Tron;

use app\model; 
 
use zjkal\TimeHelper;

class verify{
    
    #生成验证码
    public function img(Request $request)
      {
           
          #var_dump($request->expectsJson());//true 判断是否是期待json返回
          #var_dump($request->acceptJson());//true 判断客户端是否接受json返回
          // 初始化验证码类
          $builder = new CaptchaBuilder; 
          $builder->setBackgroundColor(255, 255, 255);#设置颜色
          // 生成验证码
          $builder->build();
          // 获得验证码图片二进制数据 
          $img_content = $builder->get(); 
          
          //生成唯一key
          $ip = $request->getRealIp($safe_mode=true);
          $key = "key".str_replace(".","",$ip);//uniqid() . rand(1,999);  
          
          //以IP为tag标签  把验证码保存到缓存key （tag标签的作用是在登录成功后删除 整个tag标签下的所有缓存 防止刷新验证码次数太多后产生过多的垃圾文件）
          Cache::tag($ip)->set($key,strtolower($builder->getPhrase()),600);
          
          if(config('app.debug')){
            $code = $builder->getPhrase();// 调试模式
          }else{
            $code = $builder->getPhrase();
          }
          
          //输出base64数据
          return json(['code'=>0 ,'debug'=>$code , 'data'=>'data:image;base64,'.base64_encode($img_content),'key'=>$key]);
          
          // 将验证码的值存储到session中
          #$request->session()->set('captcha', strtolower($builder->getPhrase()));
          // 输出验证码二进制数据
          #return response($img_content, 200, ['Content-Type' => 'image/jpeg']);
      } 
    
    
    
    
    #登录 
    public function login(Request $request){ 
        
        $data = $request->post();
        $ip = $request->getRealIp($safe_mode=true);
        
        #TP validate验证器
        $validate = new \app\validate\User; 
        if (!$validate->scene('reg')->check($data)) { 
            return json(['code' => 1, 'msg' => $validate->getError()]);
        }
        #validate end 
      
      
          if (strtolower($data['code']) !== Cache::get($data['formkey']) ) {
              return json(['code' => 1, 'msg' => '输入的验证码不正确']);
          }
         
         $user = Db::name('account')->where('username', $data['username'])->find();  
         
         if(empty($user) ){
           return json(['code' => -1,'msg'=>"用户不存在"]);   
         } 
         
         if($user['password'] != md5($data['password']) ){
           return json(['code' => -1,'msg'=>"用户密码错误"]);   
         }  
         
         if($user['api'] === 0 ){
           return json(['code' => -1,'msg'=>"用户已被禁止登录"]);   
         }
         
         if($request->header("User-Agent") == 'okhttp/3.12.1'){
             if($user['roleId'] != 3){
                return json(['code' => -1,'msg'=>"账号无权登录App"]); 
             }
               
         }
         
         #谷歌验证
         if($user['google'] == 1 && empty($data['googlecode']) ){ 
            return json(['code' => 801,'msg'=>"您已开启谷歌验证"]); 
            
         }else if($user['google'] == 1 && !empty($data['googlecode'])){
             $ga = new GoogleAuthenticator();
             $checkResult = $ga->verifyCode($user['SecretKey'], $data['googlecode'], 1);
             if(!$checkResult){
                return json(['code' => 801,'msg'=>"谷歌验证码错误"]);  
             }
         }
         //end
         
         
         #IP验证
         $ipsafe = Db::name('ipsafe')->where("myid",$user['id'])->where("type",1)->find();
         if(!empty($ipsafe) && strlen($ipsafe['ip']) >8 ){
             if(!stripos("#".$ipsafe['ip'], $ip)){
                 return json(['code' => 300, 'msg' => "你当前网络无权登录该账号" ]); 
             }
             
         }     
         
         #角色
         $role = Db::table('sys_role')->where('roleId', $user['roleId'])->find(); 
         if(empty($role)){
            return json(['code' => 1, 'msg' => '该用户组角色被禁止登陆']); 
         }
         
         #租户
         $tenant = Db::table('sys_tenantId')->where('tenantId', $role['tenantId'])->find();
         if(empty($tenant)){
            return json(['code' => 1, 'msg' => '用户所属系统组·不存在或维护中..']); 
         }else{
            $user["tenantId"] =$tenant['tenantId']; 
         }
         
         
         #子账号
         if($user['roleId'] == 4){
             if(empty($user['upid'])){
                 return json(['code' => 1, 'msg' => '子账户登录提示：主号数据不存在']);  
             }
             $subuser = $user;  
             $user = Db::name('account')->where('id', $user['upid'])->find(); //取得主号数据
             #$role = Db::table('sys_role')->where('roleId', $user['roleId'])->find(); 
             if(empty($user)){
                 return json(['code' => 1, 'msg' => '子账户登录提示：主号数据异常']);  
             }  
             $user["one"] =$subuser['one'];
             $user["sub"] =$subuser['username'];
             $user["subID"] =$subuser['id'];
             $user["google"] =$subuser['google'];
         }
          
        $tokenObject = Auth::login($user);//生成token 
            
        $JWTuid = $subuser['id'] ?? $user['id'];  
        $JWT_MD5 = $tokenObject->token_md5;
        Redis::HSET("HJWTMD5_{$JWTuid}",$JWT_MD5,time());
        redis::EXPIRE("HJWTMD5_{$JWTuid}",config('plugin.TNTma.tntjwt.app.exp'));//设置过期时间
        
         
       
       //登录成功后最终删除整个tag标签(IP)下面的所有缓存·有效防止产生过多垃圾缓存 
       Cache::tag($ip)->clear();
       return json(['code' => 0,'msg'=>"登录成功" ,'data' => $tokenObject ]);  
    }
      
      
      
      
      
      
      
      
    public function QrCodeLogin(Request $request){
        $data =  $request->post();
        if(empty($data['code'])){
            return json(['code' =>1, 'msg' => '缺少qrcode' ]);   
        }
        $qr = Redis::HGET("QRcode",$data['code']);
        if(!empty($qr)){
            return json(['code' =>0, 'msg' => '扫码登录成功', 'qr' =>unserialize($qr)]);   
            
        } 
        return json(['code' =>1, 'msg' => '等待扫码' ]);   
        
          
        
    }
      
 
 
 
    
    
    
    #飞机登录
    public function TelegramLogin(Request $request){ 
        $data =  $request->all();
        $ip = $request->getRealIp($safe_mode=true); 
        
        if(empty($data['code']) || strlen($data['code']) != 4){ 
            return json(['code' =>1, 'msg' => '数据不能为空' ]);      
        }  
             
            if(Redis::tTl($data['code']) < 5){
                Redis::setex($data['code'], 600, '');
                Redis::setex("IP".$data['code'], 600, $ip);
                return json(['code' =>1, 'msg' => '链接机器人成功' ]); 
            }else{ 
                  $code = Redis::get($data['code']); //取得 redis 中的 user数据
                  if(empty($code)){
                    return json(['code' => 5, 'msg' => '未查询到数据' ,'data'=> Redis::tTl($data['code'])]);   
                  }else{
                    $user = json_decode($code,true);  
                    if(empty($user['Telegram'])){
                    #队列发消息给tg
                    $queueData = array("uid"=>$user['TGuid'],"text"=>"登陆失败,{$user['TGname']} - 您未绑定商户号");
                    Client::send('send_TGsend',$queueData);
                      return json(['code' => 4,'msg'=>"登录失败：【{$user['TGname']}】未绑定本平台" ,'data' => $user ]);    
                    }
                    //避免有人恶意登录·这里设置以下IP信息效验
                    if(Redis::get("IP".$data['code']) != $ip){
                        #队列发消息给tg
                        $queueData = array("uid"=>$user['TGuid'],"text"=>"登陆失败,IP数据效验失败");
                        Client::send('send_TGsend',$queueData);
                       return json(['code' => 4,'msg'=>"登录数据异常·请F5刷新页面重新登录" ]);    
                    }
                    
                    $JWTuid = $user['subID'] ?? $user['id'];
                    $JWTusername = $user['sub'] ?? $user['username'];
                     
                    //  #IP验证   - tg登录无视ip验证
                    //  $ipsafe = Db::name('ipsafe')->where("myid",$JWTuid)->where("type",1)->find();
                    //  if(!empty($ipsafe) && strlen($ipsafe['ip']) >8 ){
                    //      if(!stripos("#".$ipsafe['ip'], $ip)){
                    //         #队列发消息给tg
                    //         $queueData = array("uid"=>$user['TGuid'],"text"=>"登陆失败,客户端IP无权登陆该账号");
                    //         Client::send('send_TGsend',$queueData);
                    //          return json(['code' => 300, 'msg' => "你当前网络无权登录该账号" ]); 
                    //      }
                         
                    //  }
                     
                    Redis::del("IP".$data['code']);
               
                    $tokenObject = Auth::login($user);//生成token 
                    
                    #设定在线监测数据
                    $JWT_MD5 = $tokenObject->token_md5;
                    Redis::hset("HJWTMD5_{$JWTuid}",$JWT_MD5,time());
                    redis::EXPIRE("HJWTMD5_{$JWTuid}",config('plugin.TNTma.tntjwt.app.exp'));//设置过期时间
                    
                    //登录成功后最终删除整个tag标签(IP)下面的所有缓存·有效防止产生过多垃圾缓存
                    Cache::tag($ip)->clear();
                    Redis::del($data['code']); 
                    #队列发消息给tg
                    $queueData = array("uid"=>$user['TGuid'],"text"=>"{$JWTusername} - 已登录成功");
                    Client::send('send_TGsend',$queueData);
                    return json(['code' => 0,'msg'=>"登录成功" ,'data' => $tokenObject ]);       
                  }
                
            }
            
         
        
    }
    
    
    
    #【Telegram-bot Api】
    public function  telegram(Request $request){ 
         $data = $request->all(); 
         var_export($data);
         if(empty($data['message']['chat']['id'])){ //负数=群消息，正数=私聊消息
                echo "------------未识别会话窗口 message.chat.id ------------\n";
                 var_export($data);
                 echo "\n\n\n";
            return true; 
         }
         if(empty($data['message']['text'])){
             echo "------------未识别到消息内容 message.text------------\n";
             var_export($data);
             echo "\n\n\n";
             return true;  
         }
         $cid   =   $data['message']['chat']['id']; //通用 会话窗口ID
         $time  =   $data['message']['date'];       //通用 消息时间
         $msgid =   $data['message']['message_id']; //通用 对话窗口中的消息ID,修改,删除时有用 
         
         
         $msg   =   str_replace(PHP_EOL," ", $data['message']['text']);        //text
         $com   =   explode(" ",$msg);  
         
         
         $Sstart = "bind  商户绑定TG或解绑\nlogin 登录时使用TG快捷登录\nmoney 查询您的资产数据\ndata  查询今日的数据报表";
         $Qstart = "auth  商户>授权群机器人\ncdd   查询支付订单\ncdf   查询代付订单";
         
         
         
        //  $queueData = array("url"=>"/sendPhoto?chat_id={$cid}&reply_to_message_id={$msgid}&photo=https://test.22tw.cn/123.png&caption=商户：123456\n指令：今日数据图\n记录：".date("Y-m-d H:i:s"));
        //                         Client::send('send_TGsend',$queueData); 
        //                         return true;
         
         #私聊消息
         if($cid>1){ 
                $uid    = $data['message']['from']['id'];//发消息人ID
                $tuser  = $data['message']['from'];     //发消息人数据 id用户ID，last_name姓氏，first_name名字，username用户名
                
                 
                
                #----------------------------------------------------------------=>指令
                if(count($com) == 2){
                    if($com[0] == "login"){#快捷登录
                         if(is_numeric($com[1]) and  strlen($com[1])==4){ 
                             if(Redis::tTl($com[1]) > 5){ 
                                $user = Db::name('account')->json(['Telegram'])->where('Telegram->id',$uid)->setFieldType(['Telegram->id' => 'int'])->find();    
                                if($user){ 
                                    
                                    #子账号登录
                                    if($user['roleId'] == 4 && $user['upid']){
                                       $subuser = $user;//先把user定义为子账号数据
                                       $user = Db::name('account')->where('id', $subuser['upid'])->find(); //取得主账号数据 
                                       $user["one"] =$subuser['one'];
                                       $user["sub"] =$subuser['username'];
                                       $user["subID"] =$subuser['id'];
                                       $user["google"] =$subuser['google'];
                                    }
                                    
                                    $user['TGuid'] = $uid;  
                                    $user['TGname'] = $tuser['first_name'];
                                    Redis::setex($com[1],Redis::tTl($com[1]),json_encode($user));   
                                
                                }else{ 
                                    $tuser['TGuid'] = $uid;
                                    $tuser['TGname'] = $tuser['first_name'];
                                    Redis::setex($com[1],Redis::tTl($com[1]),json_encode($tuser));   
                                }
                             }
                             
                         }
                    
                        
                    }else if($com[0] == "bind"){#绑定飞机号 
                    
                        if(is_numeric($com[1]) and  strlen($com[1])==6){//绑定  
                             if(Redis::tTl("ID".$com[1]) > 5){   
                                Redis::setex($com[1],Redis::tTl($com[1]),json_encode($tuser)); //把tg用户信息放入redis ， user.php /my_open_Telegram 完成操作
                             } 
                            
                        }else if ($com[1] == "null"){//解除绑定
                            $user = Db::name('account')->json(['Telegram'])->where('Telegram->id',$uid)->setFieldType(['Telegram->id' => 'int'])->find();
                            if($user){
                                Db::name('account')->where('id' ,$user['id'])->update(['Telegram' => 'null']);
                                 
                                #队列通知消息notice
                                $queueData = array("uid"=>$user['id'],"msg"=>"已解除绑定Telegram(@{$tuser['username']})");
                                Client::send('send_notice',$queueData);
                                
                                //构造数据插入数据库
                                $Msga['myid'] = $user['id'];
                                $Msga['type'] = 'notice';
                                $Msga['title'] = "账户解绑telegram（{$tuser['username']}）";
                                $Msga['content'] = "你账户绑定的Telegram(@{$tuser['username']})已被解除绑定请确认是否为本人操作！";
                                $Msga['time'] = time();
                                Db::name('message')->insert($Msga);
                                
                                #队列发消息给tg
                                $queueData = array("uid"=>$uid,"text"=>"{$user['username']} - 解绑成功");
                                Client::send('send_TGsend',$queueData);   
                            }
                            
                        }   
                        
                    }
                     
                    return json(['code' => 0, 'msg' => 'success']);      
                }
                #----------------------------------------------------------------指令end 
            if(substr($msg,0,6) == "/start"){
                $queueData = array("url"=>"/sendMessage?chat_id={$cid}&reply_to_message_id={$msgid}&text=<b>机器人指令说明 - 专属</b>\n--------------------------------\n<code>{$Sstart}</code>");
                Client::send('send_TGsend',$queueData);
                return true;
                
            }
                
                  
                return json(['code' => 0, 'msg' => 'success']);
                
         #群消息      
         }else{
             $uid    = $data['message']['from']['id'];//发消息人ID
             $qunres = $data['message']['chat']; //群数据  
             
              
             
                #----------------------------------------------------------------=>指令
                if(count($com) == 2){ 
                    if(!preg_match("/^[A-Za-z0-9]+$/",$com[1])){//参数不是字母或数字组成的终止
                        return true; 
                    } 
                    
                    $tgbot = Db::name('tgbot')->where('qunid', $cid)->find();
                    if(empty($tgbot)){
                        $queueData = array("url"=>"/sendMessage?chat_id={$cid}&text=<b>\n本群无权使用机器人指令</b>\n【点击查看如何启用】\n\n<span class='tg-spoiler'>1.登录商户后台\n\n2.商户信息>支付设置\n\n3.电报群助手> 新增群组\n\n按弹出提示启用机器人功能</span>&reply_to_message_id={$msgid}");
                        Client::send('send_TGsend',$queueData);
                        return true;   
                    }
                    
                    
                    
                     
                    
                    if($com[0] == "/cdf" || $com[0] == "cdf"){  
                        
                        $tixianlog = Db::name('tixianlog')->where('myid', $tgbot['myid'])->where('dingdan', $com[1])->find();
                        if(empty($tixianlog)){
                            $queueData = array("url"=>"/sendMessage?chat_id={$cid}&text=<b>\n查询失败·代付订单不存在</b>&reply_to_message_id={$msgid}");
                            Client::send('send_TGsend',$queueData);
                            return true;
                            
                        }else{
                            $li[0]='未打款';
                            $li[1]='已打款';
                            $li[2]='已打款·通知接口失败';
                            $li[3]='打款失败·代付资金撤回';
                            $li[4]='打款失败·已取消该订单';
                            $rdata['msg'] = $li[$tixianlog['zt']]; 
                            $rdata['bankname'] = $tixianlog['bankname']; 
                            $rdata['bankuser'] = $tixianlog['bankuser']; 
                            $rdata['bankcard'] = $tixianlog['bankcard']; 
                            $rdata['money'] = $tixianlog['amoney'];
                            $rdata['time'] = $tixianlog['time'];
                            $rdata['oktime'] = $tixianlog['oktime'];
                            $rdata['num'] = $tixianlog['num']; 
                            if(empty($tixianlog['bankaddr'])){
                                $rdata['bankaddr'] = "未填写"; 
                            }else{
                                $rdata['bankaddr'] = $tixianlog['bankaddr']; 
                            }
                            if($tixianlog['zt']===1 || $tixianlog['zt'] ===2){ 
                                if(!empty($tixianlog['imgurl'])){
                                    $rdata['imgurl'] = getenv("API_URL").$tixianlog['imgurl'];  
                                }
                            }
                            if(empty($rdata['imgurl'])){
                                $queueData = array("url"=>"/sendMessage?chat_id={$cid}&reply_to_message_id={$msgid}&text=<b>代付订单 - 查询</b>\n--------------------------------\n银行：{$rdata['bankname']}\n支行：{$rdata['bankaddr']}\n姓名：{$rdata['bankuser']}\n卡号：{$rdata['bankcard']}\n金额：<b>{$rdata['money']}</b>\n状态：<b>{$rdata['msg']}</b>\n日期：".date("Y-m-d H:i:s",$rdata['time']));
                                Client::send('send_TGsend',$queueData);
                                return true;
                            }else{
                                $queueData = array("url"=>"/sendPhoto?chat_id={$cid}&reply_to_message_id={$msgid}&photo={$rdata['imgurl']}&caption=<b>代付订单 - 查询</b>\n--------------------------------银行：{$rdata['bankname']}\n支行：{$rdata['bankaddr']}\n姓名：{$rdata['bankuser']}\n卡号：{$rdata['bankcard']}\n金额：<b>{$rdata['money']}</b>\n状态：<b>{$rdata['msg']}</b>\n日期：".date("Y-m-d H:i:s",$rdata['time']));
                                Client::send('send_TGsend',$queueData); 
                                return true;
                            }
                        }  
                        
                    }else if($com[0] == "/cdd" || $com[0] == "cdd"){
                        $pay_log = Db::name('pay_log')->where('myid', $tgbot['myid'])->where('dingdan', $com[1])->find(); 
                        if(empty($pay_log)){
                            $queueData = array("url"=>"/sendMessage?chat_id={$cid}&text=<b>查询失败·支付订单不存在</b>&reply_to_message_id={$msgid}"); 
                            Client::send('send_TGsend',$queueData);
                            return true;
                        }
                        
                        $zt[0] = '待支付';
                        $zt[1] = '已支付';
                        $zt[2] = '已补发'; 
                        
                        $notify[0] = '等待通知';
                        $notify[1] = '通知成功';
                        $notify[2] = '补发成功';
                        $notify[3] = '订单有误·资金撤回';
                        
                        $queueData = array("url"=>"/sendMessage?chat_id={$cid}&reply_to_message_id={$msgid}&text=<b>支付订单 - 查询</b>\n--------------------------------\n订单：{$pay_log['dingdan']}\n模式：{$pay_log['qdmsg']}\n通道：<b>{$pay_log['qdid']}·</b>{$pay_log['qdname']}\n金额：<b>{$pay_log['money']}</b>\n存款：{$pay_log['remark']}\n状态：<b>{$zt[$pay_log['zt']]}</b>\n回调：<b>{$notify[$pay_log['notifyzt']]}</b>\n时间：".date("Y-m-d H:i:s",$pay_log['time']));
                        Client::send('send_TGsend',$queueData);
                        return true;
                        
                    }else if($com[0] == "/auth" || $com[0] == "auth"){ //这里应该进行鉴权发消息的人是否为管理员  
                        $redis_key = "auth_{$com[1]}";
                        if(Redis::EXISTS($redis_key)){
                            $client = new Guzz_Client(['timeout' => 5]);  
                            $res = $client->request('GET', getenv("BOT_URL").getenv("BOT_TOKEN")."/getChatMember?chat_id={$cid}&user_id={$uid}")->getBody();
                            $res = json_decode($res,true); 
                            if($res['result']['status'] =='administrator' || $res['result']['status'] =='creator'){  //管理员 或 群主
                                Redis::setex($redis_key, 60,json_encode($qunres));  
                            } 
                             
                            
                            
                            
                             
                        }
                    }
                    
                    
                     
                }#----------------------------------------------------------------指令消息if end
                
                
            if(substr($msg,0,6) == "/start"){
                $queueData = array("url"=>"/sendMessage?chat_id={$cid}&reply_to_message_id={$msgid}&text=<b>机器人指令说明 - 群组</b>\n--------------------------------\n<code>{$Qstart}</code>");
                Client::send('send_TGsend',$queueData);
                return true; //cdf end 
                
            } 
              
             
             
             
             return json(['code' => 0, 'msg' => 'success']);     
         }  
    }
    
    
    # 
    public function googelyz(Request $request){
        $JWTuid = $request->user->subID ?? $request->user->id;
        $data = $request->post(); 
        $user = Db::name('account')->where('id',$JWTuid)->find();
        $ga = new GoogleAuthenticator();
        $checkResult = $ga->verifyCode($user['SecretKey'], $data['value'], 1);
        if(!$checkResult){
            return json(['code' => 801,'msg'=>"授权验证失败"]);  
        }
        Redis::setex("GYZ_{$JWTuid}",600,1);
        return json(['code' => 0, 'msg' => "授权成功(有效期10分钟)"]);
    }
    
    
    #退出登录 
    public function LoginExit(Request $request){  
        $JWTuid = $request->user->subID ?? $request->user->id;   
        if(empty($JWTuid)){
            return json(['code' => 0,'msg'=>"用户已退出登录.." ]);   
        } 
        $JWT_MD5 = md5($request->header("authorization"));   
        Redis::HDEL("HJWTMD5_{$JWTuid}",$JWT_MD5);  
        return json(['code' => 0,'msg'=>"用户已退出登录.." ]);   
         
    }
    
    
    // #ipsafe 同步 
    // public function setipsafe(Request $request){ 
    //     $uid = $request->get("myid"); 
    //     $ipsafe = Db::name('ipsafe')->where("myid",$uid)->where("type",1)->find();
    //     return json(['code' => 0,'msg'=>"已同步" ]);   
         
    // }
    
    
    
  
#==============================================================以下为调试所用测试函数================================================================= 
  

    public function token(Request $request){    
        return json(['token' => config('app.debug')]); 
    }
    
  
  
   
    
    public function redisqueue(Request $request){ 
        $queue = 'send_mail';
        // 数据，可以直接传数组，无需序列化
        $data = ['SPORDER' => 'SP2022063021435117833538693089', 'DATA' => 'DATADATADATADATADATADATADATADATA'];
        // 投递消息
        $cc = Client::send($queue, $data); 
        return json(['code' => 1, 'key' => $cc]); 
    }  
    
    
    public function redisqueueS(Request $request){  
        // 数据，可以直接传数组，无需序列化
        $data = Redis::get('{redis-queue}-failed');
        return json(['code' => 1, 'key' => $data]); 
    }  
    
    
    
    public function redis(Request $request){  
        
         #Redis::del('token_user_100016');
        // Redis::del('casbin_1'); 
        // Redis::del('casbin_2'); 
        // Redis::del('casbin_3'); 
        // Redis::del('casbin_4'); 
        //$logout = Auth::logout();
        $data = Redis::keys('*');
        $data2 = Redis::get("token_user_100001");
        $data3 = Redis::hget("googurl","100016");
        
         
        //var_export($data2);
        //$n = Redis::tTl('7576e8b2469fe678e1990392093743d6');
        return json(['code' => 1, 'key' => $data, 'data2' => $data2, 'data3' => $data3]); 
    }
    
    
    
    
    public function pushmsga(Request $request){ 
                    $queueData = array("uid"=>100001,"msg"=>"通道ID:·优先出款任务已完成");
                    Client::send('send_notice',$queueData);
        // $push = new Api('http://127.0.0.1:3012',config('plugin.webman.push.app.app_key'),config('plugin.webman.push.app.app_secret'));
        // $Msga = [
        //     'class' => '',
        //     'icon'  => 'el-icon-star-on',
        //     'title'  => '你的账户已经解绑Telegram',
        //     'time'  => date("Y-m-d H:i:s")
        // ]; 
        // $push->trigger("user-100001", 'notice', $Msga);
        return json(['code' => 1, 'api' => $push]); 
    }
    
     
    
    
    
    public function casbin(Request $request)
    { 
        
        $apiurl = '/api/system/addCasbin'; 
        echo Permission::addPermissionForUser('admin', $apiurl, 'POST');#为user角色添加权限
         
        
        #Permission::deleteRolesForUser('user');//删除用户的所有权限
       
       # Permission::AddRolesForUser('100000', ["admin"]);#为用户添加角色权限 :AddRoleForUser ，AddRolesForUser 多个
        
        
        if (Permission::enforce("100001", $apiurl, "POST")) {
            return json(['code' => 1, 'api' => "通过"]); 
        } else {
            return json(['code' => 1, 'api' => "无权限"]); 
        }
    }
    
    public function google(Request $request){ 
        $ga = new GoogleAuthenticator();
        $secret = $ga->createSecret();#生成谷歌密匙
        echo "Secret is: {$secret}\n\n";
        $qrCodeUrl = $ga->getQRCodeUrl('Blog', $secret);#二维码base64
        echo "PNG Data URI for the QR-Code: {$qrCodeUrl}\n\n";
        $oneCode = $ga->getCode($secret);#当前验证码
        echo "Checking Code '$oneCode' and Secret '$secret':\n";
        $checkResult = $ga->verifyCode($secret, $oneCode, 2);
        if ($checkResult) {
            echo 'OK';
        } else {
            echo 'FAILED';
        }

    }
    
    
    public function  meiliso(Request $request){  
        echo  password_hash("zhuxian", PASSWORD_DEFAULT);
        
        $hash = '$2y$10$m/hJsOKkaw/dhqRxtxjYZOVD4hV/RQNFtzXWYIttvDlTMjThs0zre';
 
        if (password_verify('zhuxian', $hash)) {
            echo '密码正确';
        } else {
            echo '密码错误.';
        }
    
      return json(['code' => Cache::get('name'),'number'=>0, 'data' =>0   ]); 
        
    }
    
     
    public function  meilisos(Request $request){ 
    //     $data = [['id' => 1, 'dingdan' => '12124545']];
    //   $ins =  MeiliSearch::index('article')->create($data);
    $client = new Client('http://127.0.0.1:7700', 'masterKey');
    
 

      
      
      $searchResults  = $client->index('article')->search("165114567133212", ['offset' => 0,'limit' => 5]);
      $hits = $searchResults->getHits();
      $response = $searchResults->getRaw();
             
        
        return json(['ins' => 0, 'result' => $response]); 
    }
    
    public function  es(Request $request){ 
        $result = "";
        $search = Search::use('elasticsearch',['id'=>'id','index'=>'index']);
    //     $data = [ 
 
    //     ['id' => 3, 'title' => '你看见我的小熊了吗？' ,'create_at' => '2022-03-24 10:08:08','type' => 'B'],
    //     ['id' => 4, 'title' => '这是一个神奇的世界，因为你永远不会知道我在哪' ,'create_at' => '2022-03-24 10:08:08','type' => 'C']
    //   ];
    //   echo $search->create($data); 
    
     
      
      
       
     $result = $search->q("B")->get(); 
      return json(['number' => 0, 'result' =>$result]); 
      
      #->select(['id','title']) 指定显示字段
      #->limit(4) 指定显示条数
    
    
    }
    
        //   "sort"=> [
        //     ["dingdan"=> ["order"=> "desc"]]
        // ],  
        
    public function  escha(Request $request){ 
        $client = ClientBuilder::create()->setHosts(['127.0.0.1:9200'])->build(); 
        $resp = $client->get(array(
            'index'=>"index",
            'id'=>1
            ));  
        
            return json(['ins' => 1, 'result' => $resp->asArray()]); 
    }        
        
        
        
    public function  esso(Request $request){ 
        $client = ClientBuilder::create()->setHosts(['127.0.0.1:9200'])->build(); 
        $resp = $client->search(array( 'query'=>' ')); 
        
            return json(['ins' => 1, 'result' => $resp->asArray()]); 
    }           
        
        
        
        
        
    
    public function  ess(Request $request){ 
        
        $client = ClientBuilder::create()->setHosts(['127.0.0.1:9200'])->build();
        $params = [
            'index' => 'index',
            'body'  => [
                'query' => [
                    'wildcard' =>  [ "title"=> "我"] //like 模糊查询
                    ],
                   //order排序 
                //  "sort"=> [
                //      ["id"=> ["order"=> "desc"]]
                //     ], 
                  //limit    
                  "from"=> 0, 
                  "size"=> 12,
                  //聚合计算
                  "aggregations"=>[
                      "count"=> [
                          "value_count"=> [
                              "field"=> "id"
                              ]
                              ],
                    "summoney"=>[
                        "sum"=> [
                            "field"=> "id"
                            ]
                        ]
                    ]
                  //-------
                    
            ]//body end
        ];//params end
        $response = $client->search($params);  //搜索
        

printf("数据总数: %d\n", $response['hits']['total']['value']);
printf("Max score : %.4f\n", $response['hits']['max_score']);
printf("耗时      : %d ms\n", $response['took']);
 

 
        
        
            return json(['sum' => $response['aggregations'], 'result' => $response['hits']['hits']]); 
    }
    
    
    public function  raw(Request $request,$text=null){ 
        
        
        
        echo "\nRAW输出【header】\n";
        var_export($request->header());
        echo "\nRAW输出【data】：\n";
        var_export($request->POST());
        echo "\nRAW输出【cookie】：\n";
        var_export($request->cookie());
         
        
      return json(['txt'=>$text,'ip'=>$request->getRealIp($safe_mode=true),'metd' =>$request->method(),"data"=>$request->all(),'header' => $request->header(),"debug"=>!Worker::$daemonize]);  
    }
    
    
    
    
     

    public function  islogin(Request $request){ 
        $data['money'] = 11.00;
        $sql['okmoney'] = sprintf('%.2f',$data['money']);
        $pay_log = '2'; 
        if(ceil($sql['okmoney']) !=  $data['money']){  
                         $pay_log = '1'; 
                     }
        
        return response($pay_log);
    } 
    
    
    
    
 public function  cuzzhttp(Request $request){   
     
     
   $json = json_decode(curl_cookie_html("https://e.douyin.com/passport/web/get_qrcode/?next=https:%2F%2Fe.douyin.com%2Fsite&aid=1575",false,false,true),true);
 
   
   $encode = '<img src="data:image/jpg/png/gif;base64,' . $json['data']['qrcode'] .'" >'; 
   Redis::hset('ckkkk', '100', $json['data']['token']); 
   Redis::expire("ckkkk",60);
    
    return response($encode);
  
 }
 
 public function  cuzzhttpB(Request $request){   
    $client = new Guzz_Client(['timeout' => 5]); 
    $proxyIP = $client->request('GET', "http://api.tianqiip.com/getip?secret=louh4uiv9qh49k7n&num=1&type=txt&lb=%5Cn&port=2&time=3&sign=945b31bb8077aa67d9831a7dfa2890ae")->getBody();//-->getContents()
    $header = [
        'headers' => ['User-Agent'=>"123456" 
                     ],
        'proxy' => $proxyIP
        ];
    $res = $client->request('GET', "http://47.108.66.244:8686/api/verify/raw",$header)->getBody();//-->getContents()
 
    return response($res);
  
 }
 
 
 
 public function  ttt(Request $request){  
     
      if(preg_match("/^[A-Za-z]+$/",'cdd')){
          echo '123';
          }
     
    //  $goods = new \app\service\CommonService()  ;
    //  echo  $goods->test('dsaaaaaaaaa');
     
     #Redis::set("100001_Lvqudao",25);
      
      #$user = Account::where('username', '111222')->find(); 
      #$user = Account::find(100000); 
      #var_export($user);
     
     //$token = "【四川农信】您尾号1544的账户于8月1日16时37分到账人民币1000.00元(手机转账), 余额105.85元,对方账号尾号为6943,户名为易慧诗。";
     //$pattern = '/尾号(\d{4})的\S+?到账人民币(\S+?)元\S+?户名为(\S+?)。/';
     //$token = "【中国农业银行】周旭东于08月15日16:54向您尾号1975账户完成转存交易人民币3000.00余额:3000.00";
     //$pattern = '/】(\S+?)于\S+?您尾号(\d{4})账\S+?交易人民币(\S+?)余额/';
     //$token = "【河北农信】您的账户(尾号4209)于09月12日16:56周一军支付宝转账4800.00元，余额29910.00元。存款保险保护您的存款";
     //$pattern = '/尾号(\d{4})\S+?:\d{2}(\S+?)支付宝转账(\S+?)元/';
     //$token = "【河北农信】您的账户(尾号4209)于09月12日17:41其他渠道转入3007.00元，余额32917.00元。存款保险保护您的存款";
     //$pattern = '/尾号(\d{4})\S+?转入(\S+?)元/';
     $token = "【中国农业银行】您尾号1372账户10月15日15:08向王思聪完成转支交易人民币-42020.12，余额7093.28";
     //$token = str_replace(" ","",$token); 
     $pattern = '/尾号(\d{4})账户\S+?向(\S+?)完成\S+?人民币-(\S+?)，余额/';
     preg_match($pattern,$token,$result);
     return json($result);
     
 }
 
 
 public function  cuzzhttps(Request $request){
            $client = new Guzz_Client(['timeout' => 5]); 
            $requests = function ($num) {//若以参数并发请求，下面的 $requests(1) 传递
                //$uri = 'http://47.108.66.244:8686/api/verify/raw'; 
                $header = ['User-Agent'=>'eatipay/1.0','telegram'=>'@SmalPony'];
                $userarray = Db::name('account')->where("api",1)->where("webhook",1)->limit(0,10)->select();
                foreach($userarray as $k=>$v){
                    $maxmoney = Db::name('pay_qudao')->where("myid",$v['id'])->max('tmoney'); 
                    yield new Guzz_Request('POST',$v['webhookurl'],$header,"total={$v['tmoney']}&eload={$v['etc']}&max={$maxmoney}");
                } 
            };
            
            $pool = new Pool($client, $requests(1), [
                'concurrency' => 5,
                'fulfilled' => function ($response, $index) { 
                    $data = json_decode($response->getBody(),true);  
                    echo "\n\ntask".$index." | ok\n";
                         
                },
                'rejected' => function ($err, $index) {
                    // 每个请求失败时执行  
                    // $json = $err->getHandlerContext();
                    // var_dump($json['error'] );
                    // echo "\n\n".$index." | no" .$err->getMessage() ; 
                    Log::info("task:tsyue:任务异常".$err->getMessage());
                },
            ]);
            
            // 开始传输并创建一个 promise
            $promise = $pool->promise();
            
            // 等待请求池完成
            $promise->wait(); 
    }
    
 public function  way(Request $request){
     
     $data =  $request->all(); 
     Gateway::$registerAddress = '127.0.0.1:8236'; 
     
     Gateway::sendToAll("你好#兄弟们\n");
  
     
    //  Gateway::sendToAll("消息发送时间：".date("Y-m-d H:i:s")."\n\r");
     
    //  return json(['code' =>1,'msg'=>"ok"]);  
     
 
     
    //  $uid = "1234567";
    //  if(Gateway::isUidOnline($uid)){
    //     //  $list = Gateway::getClientIdByUid($uid); 
    //     //  Gateway::sendToClient($list[0], $data['msg']."\r\n");   
    //     # Gateway::sendToUid($uid, $data['msg']."\r\n"); 
    //     # Gateway::sendToUid("1234567", $data['msg']."\r\n"); 
         
          
    //  }else{
    //     return json(['code' =>1,'msg'=>$uid."不在线哦"]);  
    //  } 
     
    //  $client_id = Gateway::getClientIdByUid(123456); 
    // // Gateway::sendToClient("7f000001206f00000001", $message);
     
     
      
     
     $TCPcount = Gateway::getAllClientIdCount();
     $getAllClientSessions =  Gateway::getAllClientSessions();//获取当前所有在线client_id信息。
     $getAllUidList = Gateway::getAllUidList();//获得所有在线uid
     
     
    
     
     
       return json(['count' => $TCPcount,
                    '所有在线client信息' => $getAllClientSessions,
                    '所有在线uid'=>$getAllUidList
       ]);   
      
     
 }
 
  
 
public function task(Request $request)  {   
                            $hashid = new Hashids();
                            $upid = $hashid->decode("help");

var_dump(is_numeric("s"));
     return json(['u' =>$upid ]);
} 
    
 
public function trc20(Request $request)  { 
        $plugin = json_encode(array_keys(config('plugin'))); 
        if(preg_match('/SwapTRX8bot/', $plugin, $return)){
          return json(['u' =>$return ]);    
        }
         return json(['u' =>$plugin ]);       
      }
 
 
 
    /**
     * 生成表格
     */
    public function tttt(Request $request){  
        echo base64_decode("te6ccgEBAgEANQABTgAAAABUZWxlZ3JhbSBQcmVtaXVtIGZvciAzIG1vbnRocyAKClJlZgEAEiNlUTN5U1FPMw");
        $addr = $request->get("addr");
        $credential = Account::SetPrivateKey($addr);
        
		echo PHP_EOL.'转换 -> (私钥)KEY ：' . $addr ;  
		echo PHP_EOL.'转换 -> (公钥)PUC ：' . $credential->publicKey() ; 
		echo PHP_EOL.'转换 -> (地址)TRC ：' . $credential->address(); 
		echo "\n";

        return json(0);  
    }
    
    
    public function ton(Request $request){   
         $url = "https://fragment.com/api?hash=fd245629c0fc1a5766"; 
        $cookieJar = CookieJar::fromArray([
            'stel_ton_token' => 'b5ClCMRad-DDkgSgorgqG5Fev5qNpSFcGD-4kioOs0z53fCOUXIPpL0jnhSizu-GZH9ZYbdqY9HWePeqmP4XtmQtVtCisf4lPeS1Tkriq72dEhaHb6E0oseQIJ8ruJ7OL8D52H73Fy9G2rDpDAgFUC6GBMkwMhmAqHKfd6VZ41qHK7ch5vw;',
            'stel_ssid' => 'f8f6f44c1876ec581b_18342509975104871280',
        ], 'fragment.com');   
        try { 
            $client = new Guzz_Client(['timeout' => 5,'http_errors' => false,'verify' => false]);  
            $header = [ 
                    'cookies' => $cookieJar,
                    'form_params' => [
                        'query' => 'gd801',
                        'months' => 3,
                        'method' => 'searchPremiumGiftRecipient'
                     ]
                    ];       
            $user = json_decode($client->request('POST',$url,$header)->getBody(),true);
            if(empty($user['ok'])){
                return json(['code' => 0, 'msg' => "获取被赠送用户信息失败"]); 
            }
            $userName = $user['found']['name'];
            $recipient = $user['found']['recipient']; 
            #-----------创建订单---------
            $client = new Guzz_Client(['timeout' => 5,'http_errors' => false,'verify' => false]);  
            $header = [ 
                    'cookies' => $cookieJar,
                    'form_params' => [
                        'recipient' => $recipient,
                        'months' => 3,
                        'method' => 'initGiftPremiumRequest'
                     ]
                    ];       
            $req_id = json_decode($client->request('POST',$url,$header)->getBody(),true);
            if(empty($req_id['req_id'])){
                return json(['code' => 0, 'msg' => "创建赠送会员订单失败"]); 
            }
            $amount = $req_id['amount'];
            $req_id = $req_id['req_id'];
            #-----------确认订单---------
            $client = new Guzz_Client(['timeout' => 5,'http_errors' => false,'verify' => false]);  
            $header = [ 
                    'cookies' => $cookieJar,
                    'form_params' => [
                        'id' => $req_id,
                        'show_sender' => 1,
                        'method' => 'getGiftPremiumLink'
                     ]
                    ];       
            $qr_link = json_decode($client->request('POST',$url,$header)->getBody(),true);
            if(empty($qr_link['ok'])){
                return json(['code' => 0, 'msg' => "创建支付订单失败"]); 
            }
      
            $check_params = $qr_link['check_params']['id'];
            $expire_after = $qr_link['expire_after'];
            #-----------获取支付数据 
            $client = new Guzz_Client(['timeout' => 5,'http_errors' => false,'verify' => false]);         
            $qr_base64 = json_decode($client->request('GET',"https://fragment.com/tonkeeper/rawRequest?id={$check_params}&qr=1")->getBody(),true);
            if(empty($qr_base64['body']['params']['messages'][0]['payload'])){
                return json(['code' => 0, 'msg' => "支付数据拉取异常"]); 
            }
            $payload = base64_decode($qr_base64['body']['params']['messages'][0]['payload']); //支付数据
            $amountNum = $qr_base64['body']['params']['messages'][0]['amount'];//支付TON数量 - 精度9  =  10000000000
            $address = $qr_base64['body']['params']['messages'][0]['address'];//收款地址
            $expires_sec = $qr_base64['body']['params']['expires_sec'];//订单过期时间戳
            $source = $qr_base64['body']['params']['source'];//付款钱包地址 - 可能不一定需要这个钱吧支付 
            #------使用支付API进行支付-------------
 
            
      
            
        } catch (\Throwable $e) { 
            echo "\n\033[0;36m错误：".$e->getMessage()."\033[0m";  
        }
        
    }
 
    
}