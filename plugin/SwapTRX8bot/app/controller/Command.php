<?php
namespace plugin\SwapTRX8bot\app\controller;


use TNTma\TronWeb\Address;
use TNTma\TronWeb\Account;
use TNTma\TronWeb\Tron; 

use support\Redis;//redis缓存
use Hashids\Hashids; //数字加密
use Vectorface\GoogleAuthenticator;#谷歌验证
use Webman\RedisQueue\Client; #redis queue 队列
use think\facade\Db;#mysql https://www.kancloud.cn/manual/think-orm/1258003
use think\facade\Cache;#Cache https://www.kancloud.cn/manual/thinkphp6_0/1037634
use Tntma\Tntjwt\Auth; 

class Command extends Base{
    
    public function index($message){  
        $bot = $this->BOT['API_BOT'];
        $by = $this->BOT['Admin']; 
        
        $chatType = $message['chat']['type']; //会话类型 私人 群组 频道
        $chatId = $message['chat']['id'];//会话聊天ID
        $tgid = $message['from']['id'];//用户ID  
        
        if($chatType == "group"){
            $chatType = "supergroup";  
        } 
        
        preg_match('/\/(\w+)\s*(.*)/i', $message['text'], $com); 
        if(count($com) != 3){ 
            return true;
        } 
        
         
        
        $type = $com[1]; //正则取得的菜单命令内容
        $value = $com[2];
         
         
        #type指令更替 
        if(is_numeric($type) &&  strlen($type)==4){ 
            $type = "Login";
        } 
        #$value 更替
        if(is_numeric($value) && $value < 0){ 
            $qunid = $value;
            $value = "excel";      
        } 
        
        
        switch ($type) {  
            default:   
                //已兼容多条命令 多事件触发 - 更多模块可能考虑前端 特殊处理 比如回复键盘 ，url webapp 内联等等对应模块选择
                $command = Db::name('bot_commands')->where("del",0)->where("bot",$bot)->where("chatType",$chatType)->where("command",$type)->where("type",1)->cache("{$bot}_{$type}_{$chatType}_1")->select();
                 
                if($command->isEmpty()){
                    return "{$type}·命令未支持"; 
                } 
                
                foreach ($command as $commands) {    
                    $_text = $commands['text'] ?? "老板·未设定回复内容"; 
                    $so =[];
                    array_push($so,'del');
                    array_push($so,'=');
                    array_push($so,0); 
                    array_push($so,'comId');
                    array_push($so,'=');
                    array_push($so,$commands['id']);   
                    array_push($so,'type');
                    array_push($so,'=');
                    array_push($so,$commands['reply_markup']);  
                    $so = array_chunk($so,3);//拆分   
                    
                    $markup = Db::name('bot_markup')->where([$so])->cache("bot_markup_select_{$commands['id']}")->order('sortId asc')->select(); 
                    $keyboard[$commands['reply_markup']]=[];
                    $d1 = array();
                  
                        foreach ($markup as $value) {   
                            if(empty($value['class']) && $commands['reply_markup']!="keyboard"){ //keyboard 时允许class 空
                                continue;   
                            } 
                            if(!array_key_exists($value['aid'],$d1)){//行
                                $d1[$value['aid']] = [];
                            } 
                            if(!empty($value['class'])){//按钮正文
                                $d2['text'] = $value['text'];
                                
                                if($value['class'] == "web_app" || $value['class'] == "login_url"){
                                    $class['url']=$value[$value['class']]; //构建json
                                    $d2[$value['class']] = $class; //二次json插入
                                }else if($value['class'] == "excel"){
                                    $d2["class"] = "url";
                                    $d2["url"] = "https://t.me/{$this->BOT['API_BOT']}?start={$chatId}"; 
                                }else if($value['class'] == "group"){
                                    $d2["class"] = "url";
                                    $d2["url"] = "https://t.me/{$this->BOT['API_BOT']}?startgroup=true"; 
                                }else if($value['class'] == "lianxiren"){
                                    $d2["class"] = "url";
                                    $d2["url"] = "https://t.me/{$value['url']}"; 
                                }else{
                                    $d2[$value['class']] = $value[$value['class']];//对应字段的值
                                }  
                                array_push($d1[$value['aid']],$d2);
                                
                            }else{
                                array_push($d1[$value['aid']],["text"=>$value['text']]);//这里基本上是回复键盘了
                            } 
                        }
                         
                        $keyboard[$commands['reply_markup']] = array_values($d1); 
                        
                        $reply_markup = json_encode($keyboard); 
                        
                        $_text = preg_replace('/\n[^\S\n]*/i', "\n", $_text);
                        $_text = urlencode($_text);
                        
                         
                        
                        $this->send("/sendMessage?chat_id={$message['chat']['id']}&text={$_text}&reply_markup={$reply_markup}&reply_to_message_id={$message['message_id']}");  
                     
                } 
                break;
            
            
            
            case 'start':   
                $namea = $message['from']['last_name'] ?? "";
                $nameb = $message['from']['first_name'] ?? ""; 
                
                switch ($value) {//有参数时对号入座
                
                    default://无start参数时  
                    
                        #-----------------------------start 无参数群聊 ---------------------------------
                        if($chatType != "private"){
                            $Temppath = "\plugin\\{$this->plugin}\app\controller\Template";
                            $Template  =  new $Temppath;
                            $Template = $Template->reply_markup("start",$chatType,1,$chatId);  
                            if($Template['text']){ 
                                $this->send("/sendMessage?chat_id={$message['chat']['id']}&text=Hi 你好：<b>{$namea}·{$nameb}</b>\n{$Template['text']}&reply_markup={$Template['reply_markup']}&reply_to_message_id={$message['message_id']}  ");
                            }  
                            break;
                        } 
                        
                        
                        #-----------------------------start 无参数私聊 ---------------------------------
                        
                        $from = Db::name('account_tg')->where('bot', $this->BOT['API_BOT'])->where('tgid', $message['from']['id'])->find();
                        if(empty($from)){  
                                $from['bot'] = $this->BOT['API_BOT']; 
                                $from['tgid'] = $message['from']['id'];  
                                $from['username'] = $message['from']['username'] ?? "未设置"; 
                                $from['name'] = $namea.$nameb; 
                                $from['regtime'] = time();     
                                
                                if($value){ //有推广参数
                                    $hashid = new Hashids();
                                    $upid = $hashid->decode($value);
                                    if(!empty($upid[0])){ //效验通过
                                        $upinfo = Db::name('account_tg')->where('bot', $this->BOT['API_BOT'])->where('id', $upid[0])->find(); //推广人数据
                                        if(!empty($upinfo)){ 
                                            $from['up'] = $upinfo['tgid']; 
                                            Db::name('account_tg')->where('id', $upinfo['id'])->inc("tgnum",1)->update();   
                                            $text = "
                                            恭喜您 <b>邀请成功%2B 1</b>
                                            \n用户：<b>{$namea}·{$nameb}</b>  
                                            ";
                                            $reply_markup = json_encode([
                                                "inline_keyboard"=>[   
                                                    [["text"=>'邀请链接',"callback_data"=>"推广链接"],
                                                     ["text"=>'我的推广',"web_app"=>["url"=>$this->webapp()."/user/tg"]]
                                                    ],  
                                                    ]
                                            ]); 
                                            $this->send("/sendMessage?chat_id={$upinfo['tgid']}&text={$text}&reply_markup={$reply_markup}&reply_to_message_id={$message['message_id']}");
                                            #今日推广数据
                                            $date = date("Ymd"); 
                                            $total_tg = Db::name('bot_total_tg')->where('bot', $this->BOT['API_BOT'])->where('tgid', $upinfo['tgid'])->where('date', $date)->find();  
                                            if(empty($total_tg)){ 
                                                $total_tg['bot'] = $this->BOT['API_BOT'];
                                                $total_tg['tgid'] = $upinfo['tgid'];
                                                $total_tg['date'] = $date;
                                                $total_tg['tgnum'] = 1;
                                                $total_tg['time'] = time();
                                                Db::name('bot_total_tg')->insert($total_tg);  
                                            }else{ 
                                                Db::name('bot_total_tg')->where('id', $total_tg['id'])->inc("tgnum",1)->update(); 
                                            }
                                            
                                    
                                        }  
                                    }  
                                }
                                $date = date("Ymd"); 
                                $total_tg_d = Db::name('bot_total_tg')->where('bot', $this->BOT['API_BOT'])->where('tgid', 10)->where('date', $date)->find(); //10代表统计当日 新用户数量
                                if(empty($total_tg_d)){
                                    $total_tg_d['bot'] = $this->BOT['API_BOT'];
                                    $total_tg_d['tgid'] = 10;
                                    $total_tg_d['date'] = $date;
                                    $total_tg_d['account'] = 1;
                                    $total_tg_d['time'] = time();
                                    Db::name('bot_total_tg')->insert($total_tg_d);  
                                }else{ 
                                    Db::name('bot_total_tg')->where('bot', $this->BOT['API_BOT'])->where('date', $date)->where('tgid', 10)->inc("account",1)->update(); 
                                } 
                                
                                Db::name('account_tg')->insert($from); //插入新用户
                                
                        }else{
                            if($from['del'] == 1){
                                Db::name('account_tg')->where('id', $from['id'])->update(['del'=>0]);    
                            }
                        } 
                        
                        # 
                        
                        $Temppath = "\plugin\\{$this->plugin}\app\controller\Template";
                        $Template  =  new $Temppath;
                        $Template = $Template->reply_markup("start",$chatType,1); 
                        
                        if(empty($Template['text'])){
                            $Template['text'] = "本机器人为您提供以下服务：\n1.发送钱包地址可以查询钱包余额情况\n2.发送交易哈希可以查看交易状态详情\n3.给本机器人转USDT可以自动回TRX"; 
                        }
                        
                        $keyboard['resize_keyboard']=true;
                        $keyboard['keyboard'] = []; 
                        $d1 = array();
                        array_push($d1,["text"=>'💹兑换汇率']);
                        array_push($d1,["text"=>'🔰兑换地址']); 
                        array_push($keyboard['keyboard'],$d1);
                        
                        $d2 = array();
                        array_push($d2,["text"=>'🌐绑定地址',"web_app"=>["url"=> $this->webapp()."/user/addr"]]); 
                        
                        array_push($d2,["text"=>'🆘预支TRX']); 
                        array_push($keyboard['keyboard'],$d2);
                        
                        $keyboard = json_encode($keyboard);
                        
                        $this->send("/sendMessage?chat_id={$message['chat']['id']}&text=Hi 你好：<b>{$namea}·{$nameb}</b>\n你的电报ID：<code>{$message['from']['id']}</code>\n{$Template['text']}&reply_markup={$keyboard}");  //回复键盘
                         
                        
                        $price = Redis::GET("TRXprice"); 
                        $dec =  round($price * $this->setup['Rate'] / 100,2);
                        $price = $price -$dec;
                        $text ="
                        <b>当前兑换汇率：</b>
                        \n<code>1   USDT = ".round($price,2)." TRX</code>
                        \n<code>10  USDT = ".round($price*10,2)." TRX</code>
                        \n<code>100 USDT = ".round($price*100,2)." TRX</code>
                        \n\n钱包地址(trc20)：\n<code>{$this->addr}</code>\n点击上面地址自动复制
                        ";
                        
         
                        $this->send("/sendPhoto?chat_id={$message['chat']['id']}&photo=https://telegra.ph/file/caa1f5ee9a712397b3ad9.jpg&caption={$text}&reply_markup={$Template['reply_markup']}","url",null,2); 
                        
                         
                        
                        
                       
                        break;//无参数start 命令结束
                
                
                #----------------------start 带参数对号入座-----------------------------------------
                
                
                
                case 'help'://start=help 
                    $Temppath = "\plugin\\{$this->plugin}\app\controller\Template";
                    $Template  =  new $Temppath; 
                    $Template = $Template->reply_markup("help",$chatType,1,$chatId); 
                    $this->send("/sendMessage?chat_id={$message['chat']['id']}&text={$Template['text']}&reply_markup={$Template['reply_markup']}");  //&reply_to_message_id={$message['message_id']}   
                    break; 
                
                
                case 'excel':    
                    // $keep_setup = Db::name('keep_setup')->where("bot",$this->BOT['API_BOT'])->cache("{$this->BOT['API_BOT']}{$qunid}setup")->where("qunid",$qunid)->find();
                    // if(empty($keep_setup)){
                    //     $this->send("/sendMessage?chat_id={$message['chat']['id']}&text=Hi 你好：<b>{$namea}·{$nameb}</b>\n\n<b>该群数据为空</b>\n<b>请把机器人踢出重新拉入群</b>");
                    //     break;
                        
                    // }else if(!stripos($keep_setup['admin'], "@{$message['from']['username']} ")){
                    //     $this->send("/sendMessage?chat_id={$message['chat']['id']}&text=Hi 你好：<b>{$namea}·{$nameb}</b>\n\n很抱歉<b>你没有权限查看账单</b>");
                    //     break;
                        
                    // }
                    // $reply_markup = json_encode([
                    //                             "inline_keyboard"=>[   
                    //                                 [["text"=>'🌐点击查看网页账单',"url"=>"{$this->BOT['WEB_URL']}/app/user/%23/demo/down?qunid={$qunid}"],
                    //                                 //  ["text"=>'我的推广',"callback_data"=>"我的推广"]
                    //                                 ],  
                    //                                 ]
                    //                         ]);
                    $this->send("/sendMessage?chat_id={$message['chat']['id']}&text=Hi 你好：<b>{$namea}·{$nameb}</b>\n该机器人暂不支持网页账单<b>excel导出</b>&reply_markup=");
                    break;
                    
                    
                } 
            break; //start end       
                
 
                
                 
                
                
                
                
                
                
                
                
                
 
            //     $inline_keyboard['inline_keyboard'] = [];  
            //     if($chatType == "private"){
            //         $d1 = array();
            //         array_push($d1,["text"=>'💹兑换比例',"web_app"=>["url"=>$this->webapp()."/user/bili"]]);
            //         array_push($d1,["text"=>'🌐绑定地址',"web_app"=>["url"=>$this->webapp()."/user/addr"]]); 
            //         array_push($inline_keyboard['inline_keyboard'],$d1);
            //     }
            //     $d2 = array();
            //     array_push($d2,["text"=>'💚机器人开源交流群',"url"=>"https://t.me/TRXphp"]);
            //     array_push($inline_keyboard['inline_keyboard'],$d2);
            //     $d3 = array();
            //     array_push($d3,["text"=>'💚机器人技术分享频道',"url"=>"https://t.me/TRCphp"]); 
            //     array_push($inline_keyboard['inline_keyboard'],$d3);
                
            //     $inline_keyboard = json_encode($inline_keyboard);
                
    
 
            //     $price = Redis::GET("TRXprice"); 
            //     $dec =  round($price * $this->BOT['Rate'] / 100,2);
            //     $price = $price -$dec;
            //     $text ="
            //     <b>当前兑换汇率：</b>
            //     \n<code>1   USDT = ".round($price,2)." TRX</code>
            //     \n<code>10  USDT = ".round($price*10,2)." TRX</code>
            //     \n<code>100 USDT = ".round($price*100,2)." TRX</code>
            //     \n\n钱包地址(trc20)：\n<code>{$address}</code>\n点击上面地址自动复制
            //     ";
                
 
            //     $this->send("/sendPhoto?chat_id={$message['chat']['id']}&photo=https://telegra.ph/file/caa1f5ee9a712397b3ad9.jpg&caption={$text}&reply_markup={$inline_keyboard}","url",null,2); 
                
                 
                
            //     break;
                
                
                
                
                
                
                
            case 'Login':
                if($chatType == "private"){
                    if($tgid != $by){
                        $this->send("/sendMessage?chat_id={$tgid}&text=您无权登录(非管理员)&reply_to_message_id={$message['message_id']}");
                        return true; 
                        break;
                    } 
                    $user = Db::name('account')->where('roleId', 6)->where('tenantId', 2)->where('tgid', $tgid)->find();  
                     if(empty($user)){
                        $key = strtoupper(md5($tgid.rand(1,999)));
                        $ga = new GoogleAuthenticator();
                        $secret = $ga->createSecret();#生成谷歌密匙
                        $user['regtime'] = time();
                        $user['upid'] = 0;
                        $user['rate'] = 0; 
                        $user['google'] = 0;
                        $user['key'] = $key; 
                        $user['SecretKey'] = $secret;
                        $user['roleId'] = 6;
                        $user['tenantId'] = 2; 
                        $user['username'] = $user['roleId'].$tgid;  
                        $user['tgid'] = $tgid;  
                        $user['id'] = Db::name('account')->insertGetId($user);
                     }
                     
                    $user['plugin'] = $this->plugin; //自定义附加内容
                    $user['remark'] = $this->BOT['API_BOT']; //自定义附加内容
                    $tokenObject = Auth::login($user); 
                    $JWTuid = $user['id'];  
                    $JWT_MD5 = $tokenObject->token_md5;
                    Redis::HSET("HJWTMD5_{$JWTuid}",$JWT_MD5,time());
                    redis::EXPIRE("HJWTMD5_{$JWTuid}",config('plugin.TNTma.tntjwt.app.exp'));//设置过期时间 
                    Redis::HSET("QRcode",$com[1],serialize($tokenObject));
                    redis::EXPIRE("QRcode",10); 
                    $this->send("/sendMessage?chat_id={$tgid}&text=快捷登录成功&reply_to_message_id={$message['message_id']}"); 
                    return true;  
                    break;
                    
                    
                }   
                
                
                
                
        }
    }
    
}