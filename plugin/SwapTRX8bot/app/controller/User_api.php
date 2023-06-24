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
use Hashids\Hashids; //数字加密
use GuzzleHttp\Client as Guzz_Client;

class User_api extends Base{ 
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
        //$date = date("Ymd"); 
        $dateh =  date("YmdH",$time - 3600 * 6); 
        //$total_d = Db::name('bot_total_d')->where([$so,['dated','=',$date]])->order('id desc')->find();  
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
        return json(['code' => 1,'msg'=>"获取成功",'count'=>null,'num'=>count($list),'data2'=>$list3 ]);    
     }
     
     
     
     public function mydhlog(Request $request){ 
         $tgid = $request->user->tgid; 
         $setup = Db::name('trx_setup')->where("plugin",$this->plugin)->where("bot",$this->BOT['API_BOT'])->cache("trx_{$this->BOT['API_BOT']}_setup")->find(); 
         if(empty($tgid)){
            return json(['code' => 401,'msg'=>"异常请重登"]);  
         }
         $account = Db::name('account_tg')->where("tgid",$tgid)->find();   
         $price = Redis::GET("TRXprice"); 
         if(empty($price)){
            $price = 10; 
         }else{
            $dec =  round($price * $setup['Rate'] / 100,2);
            $price = $price -$dec;
         }
         $hashid = new Hashids();
         $tid = $hashid->encode($account['id']);
         $account['TRXprice'] = round($price,2);
         $account['tgurl'] = "https://t.me/".$this->BOT['API_BOT']."?start=".$tid;
         return json(['code' => 1,'msg'=>"获取成功",'info'=>$account ]);    
         
     }
     
    public function tui_list(Request $request){ 
        $tgid = $request->user->tgid; 
        $data = $request->get();
        $page = ($data['page']-1)*$data['limit'];  
        $so =[]; 
        array_push($so,"del");
        array_push($so,'=');
        array_push($so,0);  
        array_push($so,"up");
        array_push($so,'=');
        array_push($so,$tgid); 
        array_push($so,"bot");
        array_push($so,'=');
        array_push($so,$this->BOT['API_BOT']); 
        $so = array_chunk($so,3);//拆分    
        $list = Db::name('account_tg')->where([$so])->limit($page,$data['limit'])->order('dhnum desc')->select();   
        
        
        $date = date("Ymd"); 
        $total_tg = Db::name('bot_total_tg')->where('bot', $this->BOT['API_BOT'])->where('tgid', $tgid)->where('date', $date)->find();  
        return json(['code' => 1,'msg'=>"获取成功",'num'=>count($list),'data' => $list,"total_tg"=>$total_tg ]);
        
    }
     
     
    public function addr_list(Request $request){
        $tgid = $request->user->tgid; 
        $data = $request->get();
        $page = ($data['page']-1)*$data['limit'];  
        $so =[];  
        array_push($so,"tgid");
        array_push($so,'=');
        array_push($so,$tgid);  
        array_push($so,"bot");
        array_push($so,'=');
        array_push($so,$this->BOT['API_BOT']);   
        $so = array_chunk($so,3);//拆分    
        // $count = Db::name('bot_total_trc20')->where([$so])->count(); 
        $account = Db::name('account_tg')->where([$so])->find();  
        $list = Db::name('bot_total_trc20')->where([$so])->limit($page,$data['limit'])->order('id desc')->select();  
         return json(['code' => 1,'msg'=>"获取成功",'num'=>count($list),'data' => $list ,"send"=>$account['send'] ]);
        
    }
    
    
     public function trc20_list_del(Request $request){   
         $tgid = $request->user->tgid; 
         $data = $request->post(); 
         if(empty($data['id'])){
             return json(['code' => 0,'msg'=>"地址不能为空"]);  
         }else{
             Db::name('bot_total_trc20')->where('id' ,$data['id'])->where('tgid' ,$tgid)->update(['tgid'=>0]);  
         } 
         return json(['code' => 1,'msg'=>"删除绑定成功"]);  
          
     }
     
     public function trc20_list_add(Request $request){  
         $tgid = $request->user->tgid;
         $data = $request->post(); 
         if(empty($data['trc20']) || strlen($data['trc20']) != 34){
             return json(['code' => 0,'msg'=>"请输正确地址"]);  
         }else{  
            $count = Db::name('bot_total_trc20')->where("bot",$this->BOT['API_BOT'])->where("tgid",$tgid)->count();  
            if($count >5){
                return json(['code' => 0,'msg'=>"绑定失败,最多只允许绑定6个地址"]);   
            }
             
             
            $trc20 = Db::name('bot_total_trc20')->where("bot",$this->BOT['API_BOT'])->where("trc20",$data['trc20'])->find();  
            if(empty($trc20)){
               $gid =  Db::name('bot_total_trc20')->insertGetId(["trc20" => $data['trc20'],'tgid'=>$tgid,'time'=>time(),'bot'=>$this->BOT['API_BOT']]); 
            }else{
                if($trc20['tgid'] != 0 && $trc20['tgid'] != $tgid){
                    return json(['code' => 0,'msg'=>"该地址已被他人绑定,请联系客服"]); 
                }else{
                    Db::name('bot_total_trc20')->where('id' ,$trc20['id'])->update(['tgid'=>$tgid]); 
                    $gid  = $trc20['tgid'];
                }
                
            }   
         } 
         return json(['code' => 1,'msg'=>"绑定地址成功",'gid'=>$gid]);
         
     }
     
     
     public function trc20_list_update(Request $request){   
         $tgid = $request->user->tgid;
         $data = $request->post(); 
         if(empty($data['id']) || strlen($data['trc20']) != 34){
             return json(['code' => 0,'msg'=>"请输正确地址"]);  
         }else{
             Db::name('bot_total_trc20')->where('id' ,$data['id'])->where('tgid' ,$tgid)->update(['trc20'=>$data['trc20']]);  
         } 
         return json(['code' => 1,'msg'=>"地址修改成功"]);  
          
     }
     
     
     
     public function notify_send(Request $request){  
         $tgid = $request->user->tgid;
         $data = $request->post(); 
         Db::name('account_tg')->where('bot' ,$this->BOT['API_BOT'])->where('tgid' ,$tgid)->update(['send'=>$data['send']]);
         Db::name('bot_total_trc20')->where('bot' ,$this->BOT['API_BOT'])->where('tgid' ,$tgid)->update(['send'=>$data['send']]);
          
         return json(['code' => 1,'msg'=>$data['send']?"接收兑换通知":"关闭兑换通知"]);  
         
     }
     
     
     
     public function dhlog_list(Request $request){   
        $tgid = $request->user->tgid;
        $data = $request->get();
        $page = ($data['page']-1)*$data['limit'];  
        
        
        $addarray ="";
         $addr = Db::name('bot_total_trc20')->where("tgid",$tgid)->where("bot",$this->BOT['API_BOT'])->limit(0,10)->select();
         foreach ($addr as $value) { 
            $addarray .=  $value["trc20"].",";
         }
          
        
        $so =[]; 
        array_push($so,"del");
        array_push($so,'=');
        array_push($so,0);  
        array_push($so,"bot");
        array_push($so,'=');
        array_push($so,$this->BOT['API_BOT']);
        $so = array_chunk($so,3);//拆分   
        $count = Db::name('bot_usdt_list')->where([$so])->count(); 
        $list = Db::name('bot_usdt_list')->where([$so])->whereIn("ufrom",$addarray)->limit($page,$data['limit'])->order('id desc')->select();  
        
        return json(['code' => 1,'msg'=>"获取成功",'count'=>$count,'num'=>count($list),'data' => $list ]);  
         
     }
     
     
     public function trxorice(Request $request){
         $setup = Db::name('trx_setup')->where("plugin",$this->plugin)->where("bot",$this->BOT['API_BOT'])->cache("trx_{$this->BOT['API_BOT']}_setup")->find(); 
         
         $client = new Guzz_Client(['timeout' => 5,'http_errors' => false,'verify' => false]);
         $res = json_decode($client->request('GET', "https://openapi.sun.io/v2/allpairs?page_size=1&page_num=0&token_address=TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t&orderBy=price")->getBody()->getContents(),true); 
            if(!empty($res['data']['0_TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t'])){
                $price = round($res['data']['0_TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t']['price'],2); 
                Redis::SETEX("TRXprice",600,$price);//trx实时价格 过期时间 redis
                
            }else{
                $price =  Redis::GET("TRXprice");
            }
            $dec =  round($price * $setup['Rate'] / 100,2);
            $price = $price -$dec;
            
         return json(['code' => 1,'msg'=>"获取成功",'price'=>$price ]);  
     }
     
     
     
}