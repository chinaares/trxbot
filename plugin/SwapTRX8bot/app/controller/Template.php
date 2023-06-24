<?php
namespace plugin\SwapTRX8bot\app\controller;

use Webman\RedisQueue\Client; #redis queue 队列
use think\facade\Db;#mysql https://www.kancloud.cn/manual/think-orm/1258003
use think\facade\Cache;#Cache https://www.kancloud.cn/manual/thinkphp6_0/1037634
use support\Redis;//redis缓存
use TNTma\TronWeb\Address;
use TNTma\TronWeb\Account;
use TNTma\TronWeb\Tron;

use GuzzleHttp\Pool;
use GuzzleHttp\Client as Guzz_Client;
use GuzzleHttp\Psr7\Request as Guzz_Request; 
use GuzzleHttp\Promise as Guzz_Promise; 


use app\model;

class Template extends Base{
    
    public function bijia($type = 'bank',$chatId = null):array{   
        $setup['dangwei'] = 1; 
        $ico=array( '0'=>'1️⃣','1'=>'2️⃣','2'=>'3️⃣' ,'3'=>'4️⃣','4'=>'5️⃣' ,'5'=>'6️⃣','6'=>'7️⃣','7'=>'8️⃣' ,'8'=>'9️⃣'  ,'9'=>'🔟' );
        $icopay = array("all"=>"","bank"=>"","aliPay"=>"","wxPay"=>""); 
        $icopay[$type]="✅";
        $a1 = array();
        array_push($a1,["text"=>"所有{$icopay['all']}","callback_data"=>"选择_all"]);
        array_push($a1,["text"=>"银行卡{$icopay['bank']}","callback_data"=>"选择_bank"]);
        array_push($a1,["text"=>"支付宝{$icopay['aliPay']}","callback_data"=>"选择_aliPay"]);
        array_push($a1,["text"=>"微信{$icopay['wxPay']}","callback_data"=>"选择_wxPay"]);
        $a21 = array();
        $a22 = array(); 
        $a31 = array(); 
        
        $a32 = array(); 
        array_push($a31,["text"=>'减0.1',"callback_data"=>"减少_0.1"]);
        array_push($a31,["text"=>'加0.1',"callback_data"=>"增加_0.1"]);
        array_push($a32,["text"=>'减0.01',"callback_data"=>"减少_0.01"]);
        array_push($a32,["text"=>'加0.01',"callback_data"=>"增加_0.01"]);
        
        $a4 = array(); 
        array_push($a4,["text"=>'确认',"callback_data"=>"设定_0"]);
 
        $sshuilv = 7; 
        
        try {
            $client = new Guzz_Client(['timeout' => 5,'http_errors' => false,'verify' => false]); 
            $res = json_decode($client->request('GET', "https://www.okx.com/v3/c2c/tradingOrders/books?t=1679317498305&quoteCurrency=cny&baseCurrency=usdt&side=sell&paymentMethod={$type}&userType=all&receivingAds=false&urlId=10")->getBody(),true); 
            if(empty($res['requestId'])){
                throw new \Exception("获取币价失败了");  
            } 
            $text = "<b>[Okex商家实时交易汇率top10]</b>\n"; 
            for ($i = 0; $i < 10; $i++) { 
                if($i < 5){
                    if($i == $setup['dangwei']){ 
                        $sshuilv = $res['data']['sell'][$i]['price'];  
                        array_push($a21,["text"=>$ico[$i]."✅","callback_data"=>"设定_{$res['data']['sell'][$i]['price']}-{$i}"]); 
                    }else{
                        array_push($a21,["text"=>$ico[$i],"callback_data"=>"设定_{$res['data']['sell'][$i]['price']}-{$i}"]); 
                    }
                    
                     
                }else{
                    if($i == $setup['dangwei']){ 
                        $sshuilv = $res['data']['sell'][$i]['price'];  
                        array_push($a22,["text"=>$ico[$i]."✅","callback_data"=>"设定_{$res['data']['sell'][$i]['price']}-{$i}"]); 
                    }else{
                        array_push($a22,["text"=>$ico[$i],"callback_data"=>"设定_{$res['data']['sell'][$i]['price']}-{$i}"]); 
                    }
                }
                 
                $text .= "<code>{$ico[$i]}    {$res['data']['sell'][$i]['price']}   {$res['data']['sell'][$i]['nickName']}</code>\n";  
            }  
    
            return ["code"=>1,"msg"=>"获取成功","text"=>$text,"sshuilv"=>$sshuilv,"a11"=>$a1,"a21"=>$a21,"a22"=>$a22,"a31"=>$a31,"a32"=>$a32,"a4"=>$a4]; 
                 
             
            
            
        } catch (\Throwable $e) {   
            throw new \Exception("获取币价失败".$e->getMessage());   
            return ["code"=>0,"msg"=>$e->getMessage(),"text"=>"获取币价失败"];  
        }
    }
    
    

    
    
    public function qunadmin($chatId):array{
        $res =$this->get("/getChatAdministrators?chat_id={$chatId}");
        $text = "";
        foreach ($res as $value) {  
            if(empty($value['user']['username'])){//未设置用户名或者注销了的号过滤掉
                continue;
            }
            $text .= "@".$value['user']['username']." ";   
        } 
        return ["code"=>1,"msg"=>"获取成功","text"=>$text];
    }
    
    
    public function help():array{//start help
        $text = "
        <b>机器人使用说明：</b>
        暂未添加说明
        
        ";
        $text = preg_replace('/\n[^\S\n]*/i', "\n", $text);
        return ["code"=>1,"msg"=>"获取成功","text"=>urlencode($text)];
    
    }
    
    
    



    public function reply_markup($command,$chatType,$type=2,$startval=""):array{   
        $commands = Db::name('bot_commands')->where("del",0)->where("bot",$this->BOT['API_BOT'])->where("chatType",$chatType)->where("command",$command)->where("type",$type)->cache("{$this->BOT['API_BOT']}_{$command}_{$chatType}")->find();
        if(empty($commands)){
            return ["code"=>0,"msg"=>"获取失败,没有添加对应事件","text" =>"","reply_markup" =>""]; 
        }
    
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
        if($markup->isEmpty()){
            return ["code"=>0,"msg"=>"获取失败,事件没有添加对应按钮","text" =>$commands['text'],"reply_markup" =>""]; 
            
        }
        
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
                        $d2["url"] = "https://t.me/{$this->BOT['API_BOT']}?start={$startval}"; 
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
        $_text = preg_replace('/\n[^\S\n]*/i', "\n", $commands['text']);
        $_text = urlencode($_text); 
        return ["code"=>1,"msg"=>"获取成功","text"=>"{$_text}","reply_markup" =>$reply_markup ];      
      
      
    }   
}