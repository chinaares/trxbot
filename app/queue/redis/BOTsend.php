<?php
namespace app\queue\redis;

 
use Webman\RedisQueue\Consumer;
use Exception;
use think\facade\Db;
use think\facade\Cache;#Cache https://www.kancloud.cn/manual/thinkphp6_0/1037634
use support\Redis;//redis缓存
use Webman\Push\Api; //push推送 
use GatewayWorker\Lib\Gateway;

#不确定数量的请求
use GuzzleHttp\Pool;
use GuzzleHttp\Client as Guzz_Client;
use GuzzleHttp\Psr7\Request as Guzz_Request; 
use GuzzleHttp\Promise as Guzz_Promise;

use TNTma\TronWeb\Address;
use TNTma\TronWeb\Account;
use TNTma\TronWeb\Tron; 
    
class BOTsend implements Consumer{ 
    public $queue = 'BOTsend';// 要消费的队列名 
    public $connection = 'tgbot'; 

    #消费
    public function consume($data){   
        
            if($data['type'] != "url"){  
                //$BOT = config("GD.{$data['data']['bot']}");
                $BOT = Cache::get("@PonyYun");
                 
                if(empty($BOT)){
                    echo "\033[1;31m队列消费结束,未发现机器人配置\033[0m\n"; 
                    return;
                } 
                if(!empty($BOT['TRON_API_KEY'])){
                    $headers = ['TRON-PRO-API-KEY' => $BOT['TRON_API_KEY'] ];   
                }
            }
            
            // echo "\033[33m开始队列消费\033[0m\n";
            // var_dump($data);
            // echo "\n";
        
    //    try { 
            
            if($data['type'] == 'url'){
                if(!empty($data['url'])){ 
                    $client = new Guzz_Client(['timeout' => 8,'http_errors' => false]); 
                    $res = json_decode($client->request('GET', "{$data['url']}&parse_mode=HTML&allow_sending_without_reply=true&disable_web_page_preview=true")->getBody(),true); 
                    if(empty($res['ok'])){
                        echo "\033[31mTG_queue 访问API接口失败,{$res['description']}\033[0m\n"; 
                    }
                    
                }
                
            }else if($data['type'] == 'cha'){  
                $formtext = "\n\n<b>来自 <a href='tg://user?id={$data['data']['form']['id']}'> @{$data['data']['form']['first_name']}</a> 的钱包查询</b>\n\n";
                $arrtext = [
                    "查询地址"=>'<a href="https://tronscan.org/#/address/'.$data['data']['address'].'">'.substr ($data['data']['address'], 0,4).'...'.substr ($data['data']['address'], 26).'</a>',
                    "TRX余额"=>0,  
                    "usdt余额"=>0,   
                    "质押冻结"=>0,   
                    "可用能量"=>"0 / 0",   
                    "可用带宽"=>"0 / 0",   
                    "交易总数"=>"0 / 0",   
                    "收付比例"=>"0 / 0",   
                    "创建时间"=>'未知',   
                    "最后活跃"=>'未知',   
                ]; 
                $reply_markup = json_encode([
                    "inline_keyboard"=>[
                        [["text"=>'分享查询',"switch_inline_query"=>$data['data']['address']],
                        ["text"=>'再查一次',"switch_inline_query_current_chat"=>$data['data']['address']]
                        ],
                        
                        [["text"=>'兑换TRX',"url"=>"https://t.me/{$data['data']['bot']}"],
                        ["text"=>'联系作者',"url"=>"tg://user?id={$BOT['Admin']}"]]
                        
                        ]
                ]); 
                
                $client = new Guzz_Client(['timeout' => 8,'http_errors' => false,'headers' =>$headers]);  
                
                $promises = [
                    'trongrid' => $client->getAsync("https://api.trongrid.io/v1/accounts/{$data['data']['address']}"),
                    'tronscan'   => $client->getAsync("https://apilist.tronscan.org/api/account?address={$data['data']['address']}")
                ];
                $results = Guzz_Promise\unwrap($promises);//并发异步请求
                
                if($results['trongrid']){ 
                    $res = json_decode($results['trongrid']->getBody()->getContents(),true);  
                }
                if($results['tronscan']){ 
                    $tronscan = json_decode($results['tronscan']->getBody()->getContents(),true);  
                }
                 
                 
                if(!$res['success']){  
                   $_text= str_replace("=", "：",http_build_query($arrtext, '', "\n")); 
                  echo (string) $client->request('GET', "{$data['url']}/sendMessage?chat_id={$data['data']['chat']['id']}&text={$formtext}<b>很抱歉,你查询的地址无效\n\n</b>{$_text}&parse_mode=HTML&disable_web_page_preview=true&allow_sending_without_reply=true&reply_to_message_id={$data['data']['message_id']}")->getBody();   
                   return true;
                }
                if(count($res['data']) < 1){  
                    $_text= str_replace("=", "：",http_build_query($arrtext, '', "\n")); 
                    $client->request('GET', "{$data['url']}/sendMessage?chat_id={$data['data']['chat']['id']}&text={$formtext}<b>地址尚未激活,可预支TRX激活\n\n</b>{$_text}&parse_mode=HTML&disable_web_page_preview=true&allow_sending_without_reply=true&reply_to_message_id={$data['data']['message_id']}")->getBody();  
                    return true;
                } 
                 
                 
                $arrtext['TRX余额'] = "<b>".($res['data'][0]['balance'] / 1000000)."</b>";
                foreach ($res['data'][0]['trc20'] as $key=>$value) { 
                    if(!empty($value['TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t'])){
                        $arrtext['usdt余额'] = "<b>".($value['TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t'] / 1000000)."</b>";   
                        break;
                    }   
                }
                
                if(!empty($res['data'][0]['account_resource']['frozen_balance_for_energy']['frozen_balance'])){
                    $arrtext['质押冻结'] = "<b>".($res['data'][0]['account_resource']['frozen_balance_for_energy']['frozen_balance'] / 1000000)."</b>";  
                }
 
                 
                $arrtext['可用能量']   = $tronscan['bandwidth']['energyRemaining']." / ".$tronscan['bandwidth']['energyLimit'];
                $arrtext['可用带宽']   = $tronscan['bandwidth']['freeNetRemaining']." / ".$tronscan['bandwidth']['freeNetLimit'];
                $arrtext['交易总数']   = "<b>{$tronscan['transactions']}</b> 笔"; 
                $arrtext['收付比例']   = "收<b>{$tronscan['transactions_in']}</b> / 付<b>{$tronscan['transactions_out']}</b>";  
                
                if(!empty($res['data'][0]['create_time'])){ 
                    $arrtext['创建时间'] = date("Y-m-d H:i:s",substr ($res['data'][0]['create_time'],0,10));
                }
                if(!empty($res['data'][0]['latest_opration_time'])){ 
                    $arrtext['最后活跃'] = date("Y-m-d H:i:s",substr ($res['data'][0]['latest_opration_time'],0,10));
                } 
                
                $_text= str_replace("=", "：",http_build_query($arrtext, '', "\n"));
                
                $client->request('GET', "{$data['url']}/sendMessage?chat_id={$data['data']['chat']['id']}&text={$formtext}{$_text}&reply_markup={$reply_markup}&parse_mode=HTML&allow_sending_without_reply=true&disable_web_page_preview=true&reply_to_message_id={$data['data']['message_id']}")->getBody();  
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            }else if($data['type'] == "chatxid"){ 
                
                $formtext = "\n\n<b>来自 <a href='tg://user?id={$data['data']['form']['id']}'> @{$data['data']['form']['first_name']}</a> 的交易查询</b>\n\n";
                $arrtext = [
                    "查询哈希"=>'<a href="https://tronscan.org/#/transaction/'.$data['data']['txid'].'">**'.substr ($data['data']['txid'], -14).'</a>',
                    "所属区块"=>'47550857',
                    "付款地址"=>"**12345678",  
                    "收款地址"=>"**12345678",   
                    "转账数量"=>"0 TRX",   
                    "消耗费用"=>"0 TRX",   
                    "交易状态"=>"未知",   
                    "交易时间"=>"未知"
                ]; 
                $reply_markup = json_encode([
                    "inline_keyboard"=>[
                        [["text"=>'分享查询',"switch_inline_query"=>$data['data']['txid']],
                        ["text"=>'再查一次',"switch_inline_query_current_chat"=>$data['data']['txid']]
                        ]
                        
                        // [["text"=>'兑换TRX',"url"=>"https://t.me/{$data['data']['bot']}"],
                        // ["text"=>'联系作者',"url"=>"tg://user?id={$BOT['Admin']}"]]
                        
                        ]
                ]); 
                
                
                
                #$json = ['value' => 'b780145d9801d8ea2c4be290a41235d4e72d2b337bd8e8f2e5dfbfe671bf2b13','visible'=>true];
                $client = new Guzz_Client(['timeout' => 8,'http_errors' => false,'headers' =>$headers]);   
                $promises = [
                    'tronscanapi' => $client->getAsync("https://apilist.tronscanapi.com/api/transaction-info?hash={$data['data']['txid']}")
                ]; 
                $results = Guzz_Promise\unwrap($promises);//并发异步请求
                
                if(!empty($results['tronscanapi'])){ 
                    $tronscanapi = json_decode($results['tronscanapi']->getBody()->getContents(),true);  
                    
                    if(empty($tronscanapi)){
                        $client->request('GET', "{$data['url']}/sendMessage?chat_id={$data['data']['chat']['id']}&text={$formtext}<b>很抱歉,你查询的交易哈希无效\n\n</b>&parse_mode=HTML&disable_web_page_preview=true&allow_sending_without_reply=true&reply_to_message_id={$data['data']['message_id']}")->getBody(); 
                        return true;
                        
                    }
                    
                    if($tronscanapi['contractType'] == 1){//trx
                        $arrtext['所属区块']='<a href="https://tronscan.org/#/block/'.$tronscanapi['block'].'">'.$tronscanapi['block'].'</a>';
                        $arrtext['交易时间']=date("Y-m-d H:i:s",substr($tronscanapi['timestamp'],0,10));
                        $arrtext['付款地址']="**".substr($tronscanapi['ownerAddress'],-13);
                        $arrtext['收款地址']="**".substr($tronscanapi['contractData']['to_address'],-13);
                        $arrtext['转账数量']='<b>'.($tronscanapi['contractData']['amount'] / 1000000)." TRX</b>";
                        $arrtext['消耗费用']=($tronscanapi['cost']['net_fee'] / 1000000)." TRX";
                        
                        if($tronscanapi['contractRet'] == "SUCCESS"){
                            $arrtext['交易状态']="确认中..";
                            if($tronscanapi['confirmed']){
                                $arrtext['交易状态']="交易成功"; 
                            }  
                        
                        }else{
                             $arrtext['交易状态']="失败-".$tronscanapi['contractRet']; 
                        }
                     
                    
                        
                    }else if($tronscanapi['contractType'] == 31){//trc20
                        $arrtext['所属区块']='<a href="https://tronscan.org/#/block/'.$tronscanapi['block'].'">'.$tronscanapi['block'].'</a>';
                        $arrtext['交易时间']=date("Y-m-d H:i:s",substr($tronscanapi['timestamp'],0,10));
                        $arrtext['付款地址']="**".substr($tronscanapi['ownerAddress'],-13);
                        
                        if(empty($tronscanapi['tokenTransferInfo'])){
                            $arrtext['收款地址']="合约触发";
                            $arrtext['转账数量']="非转账·trc20";
                            
                        }else{ 
                            $arrtext['收款地址']="**".substr($tronscanapi['tokenTransferInfo']['to_address'],-13);
                            $arrtext['转账数量']="<b>".($tronscanapi['tokenTransferInfo']['amount_str'] / 1000000)." ".$tronscanapi['tokenTransferInfo']['symbol']."</b>";
                        }
                        $arrtext['消耗费用']=($tronscanapi['cost']['energy_fee'] / 1000000)." TRX";
                        if($tronscanapi['contractRet'] == "SUCCESS"){
                            $arrtext['交易状态']="确认中..";
                            if($tronscanapi['confirmed']){
                                $arrtext['交易状态']="交易成功"; 
                            }  
                        
                        }else{
                             $arrtext['交易状态']="失败-".$tronscanapi['contractRet']; 
                        }
                    
                    }    
                } 
                
                
                $_text= str_replace("=", "：",http_build_query($arrtext, '', "\n"));
                
                $client->request('GET', "{$data['url']}/sendMessage?chat_id={$data['data']['chat']['id']}&text={$formtext}{$_text}&reply_markup={$reply_markup}&parse_mode=HTML&allow_sending_without_reply=true&disable_web_page_preview=true&reply_to_message_id={$data['data']['message_id']}")->getBody();  
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
                
                
            }else if($data['type'] == "SwapOk"){ //转账TRX成功后发送消息给电报个人或者群组
                echo "\n\033[1;32m向{$data['data']['ufrom']}转账TRX：{$data['data']['oktrx']} 成功,发送电报消息..\033[0m\n";
                
                $client = new Guzz_Client(['timeout' => 8,'http_errors' => false]);
                #获取绑定了的地址的用户 进行私发电报消息
                
                $total_trc20 = Db::name('bot_total_trc20')->where('bot',$data['data']['bot'])->where('trc20',$data['data']['ufrom'])->find();
                
                
                
                
                #获取机器人对应需要接收通知结果的群 - 发送电报消息
                $so = [];
                array_push($so,"del");
                array_push($so,"=");
                array_push($so,0);
                
                array_push($so,"bot");
                array_push($so,"=");
                array_push($so,$data['data']['bot']);
                
                array_push($so,"send");
                array_push($so,"=");
                array_push($so,1);
                
                $so = array_chunk($so,3);//拆分 
                
                $group = Db::name('bot_group')->where([$so])->limit(0,10)->select(); //最多发送10个群
                if($group->isEmpty()){ 
                    echo "\n没有设置群组接收消息哟! 提示：把机器人拉进群时,管理员会看到一个提示消息：\033[33m是否接收兑换通知?\033[0m\n";
                    return true;
                }
                
                #构建消息格式 
                $formtext = "\n\n<b>来自 <a href='https://t.me/{$BOT['API_BOT']}'> @{$BOT['API_BOT']}</a> 的兑换通知</b>\n\n";
                
                $arrtext = [
                    "交易哈希"=>'<a href="https://tronscan.org/#/transaction/'.$data['data']['oktxid'].'">**'.substr ($data['data']['oktxid'], -14).'</a>',
                    "钱包地址"=>"**".substr ($data['data']['ufrom'], -13),  
                    "兑换汇率"=>"<b>".$data['data']['oktrx'] / ($data['data']['value'] / 1000000)."</b>",
                    "转账usdt"=>"<b>".($data['data']['value'] / 1000000) ."</b>",   
                    "兑换TRX "=>"<b>{$data['data']['oktrx']}</b>", 
                    "订单时间"=>date("Y-m-d H:i:s",$data['data']['oktime']),   
                ]; 
                $reply_markup = json_encode([
                    "inline_keyboard"=>[
                        [["text"=>'交易详情',"switch_inline_query_current_chat"=>$data['data']['oktxid']],
                        ["text"=>'查询余额',"switch_inline_query_current_chat"=>$data['data']['ufrom']]
                        ],
                        
                        [["text"=>'预支TRX',"url"=>"https://t.me/{$BOT['API_BOT']}"],
                        ["text"=>'联系作者',"url"=>"tg://user?id={$BOT['Admin']}"]]
                        
                        ]
                ]); 
                
                $_text= str_replace("=", "：",http_build_query($arrtext, '', "\n"));
                
                
                $promises = [ ]; 
                //给超管推送消息
                $promises["admindizhi"] = $client->getAsync("{$BOT['API_URL']}{$BOT['API_TOKEN']}/sendMessage?chat_id={$BOT['Admin']}&text={$formtext}{$_text}&reply_markup={$reply_markup}&parse_mode=HTML&disable_web_page_preview=true");
                
                //给群组推送消息
                foreach ($group as $value) { 
                    $promises[$value['groupid']] = $client->getAsync("{$BOT['API_URL']}{$BOT['API_TOKEN']}/sendMessage?chat_id={$value['groupid']}&text={$formtext}{$_text}&reply_markup={$reply_markup}&parse_mode=HTML&disable_web_page_preview=true");     
                } 
                //私人
                if($total_trc20['tgid'] >0 && $total_trc20['send'] == 1){
                    $promises["gerendizhi"] = $client->getAsync("{$BOT['API_URL']}{$BOT['API_TOKEN']}/sendMessage?chat_id={$total_trc20['tgid']}&text={$formtext}{$_text}&reply_markup={$reply_markup}&parse_mode=HTML&disable_web_page_preview=true");
                    
                } 
                $results = Guzz_Promise\unwrap($promises);//并发异步请求
                
                
                
            }else if($data['type'] == "commands"){ 
                $so =[]; 
                array_push($so,"del");
                array_push($so,'=');
                array_push($so,0);  
                array_push($so,"bot");
                array_push($so,'=');
                array_push($so,$data['bot']);
                array_push($so,"type");
                array_push($so,'=');
                array_push($so,1);
                $so = array_chunk($so,3);//拆分  
                $list = Db::name('bot_commands')->where([$so])->limit(0,20)->order('command asc')->select();  
                $commands = [];
                foreach ($list as $value) { 
                    $vs ['command'] = $value['command'];
                    $vs ['description'] = $value['description'];
                    array_push($commands,$vs);
                } 
                
                $client = new Guzz_Client(['timeout' => 8,'http_errors' => false]); 
                $res = $client->request('GET', "{$data['url']}/setMyCommands?commands=".json_encode($commands))->getBody();  

                
            }else if($data['type'] == "loadwebhook"){
                $client = new Guzz_Client(['timeout' => 10,'http_errors' => false]); 
                $res = json_decode($client->request('GET', "https://api.telegram.org/bot{$data['TOKEN']}/setWebhook?max_connections=100&url=".$data['URL'])->getBody(),true);
                if(!empty($res['ok'])){
                    #发送通知
                    $text = "\n\n机器人部署<b>1/4</b>\n<b>Webhook</b> 部署成功✅\n\n";
                    $reply_markup = json_encode([
                    "inline_keyboard"=>[   
                        [["text"=>'未设定',"callback_data"=>"NotifyMsg"]],
                        [["text"=>'🔍试一试查询钱包',"switch_inline_query_current_chat"=>""]] 
                        ]
                    ]); 
                    $client->request('GET', "https://api.telegram.org/bot{$data['TOKEN']}/sendMessage?chat_id={$data['Admin']}&text={$text}&reply_markup={$reply_markup}&parse_mode=HTML")->getBody();   
                }
                
            }else if($data['type'] == "loadUserMenu"){
                $client = new Guzz_Client(['timeout' => 10,'http_errors' => false]); 
                $res = json_decode($client->request('GET', "https://api.telegram.org/bot{$data['TOKEN']}/setChatMenuButton?menu_button=".'{"type":"web_app","text":"进入小程序","web_app":{"url":"'.$data['URL'].'"}}')->getBody(),true);
                if(!empty($res['ok'])){
                    #发送通知
                    $text = "\n\n机器人部署<b>2/4</b>\n<b>用户小程序</b> 部署成功✅\n\n当前号是管理号,默认显示：管理小程序\n其它任何飞机号使用本机器人为：进入小程序\n\n";
                    $reply_markup = json_encode([
                    "inline_keyboard"=>[   
                        [["text"=>'进入用户小程序',"web_app"=>['url'=>$data['URL']]],
                        ]
                        // [["text"=>'🔍试一试查询钱包',"switch_inline_query_current_chat"=>""]] 
                        ]
                    ]); 
                    $client->request('GET', "https://api.telegram.org/bot{$data['TOKEN']}/sendMessage?chat_id={$data['Admin']}&text={$text}&reply_markup={$reply_markup}&parse_mode=HTML")->getBody();   
                }
                
            }else if($data['type'] == "loadAdminMenu"){
                $client = new Guzz_Client(['timeout' => 10,'http_errors' => false]); 
                $res = json_decode($client->request('GET', "https://api.telegram.org/bot{$data['TOKEN']}/setChatMenuButton?chat_id={$data['Admin']}&menu_button=".'{"type":"web_app","text":"管理小程序","web_app":{"url":"'.$data['URL'].'"}}')->getBody(),true);
                if(!empty($res['ok'])){
                    #发送通知
                    $text = "\n \n机器人部署<b>3/4</b>\n<b>管理小程序</b> 部署成功✅\n同时下方为您提供普通用户小程序体验 \n";
                    $reply_markup = json_encode([
                    "inline_keyboard"=>[   
                        [["text"=>'管理小程序',"web_app"=>['url'=>$data['URL']]],
                         ["text"=>'用户小程序',"web_app"=>['url'=>$data['URLu']]]
                        ],
                        // [["text"=>'🔍试一试查询钱包',"switch_inline_query_current_chat"=>""]] 
                        ]
                    ]); 
                    $client->request('GET', "https://api.telegram.org/bot{$data['TOKEN']}/sendMessage?chat_id={$data['Admin']}&text={$text}&reply_markup={$reply_markup}&parse_mode=HTML")->getBody();  
                    
                    
                      
                }
                
            }else if($data['type'] == "loading4"){
                $client = new Guzz_Client(['timeout' => 10,'http_errors' => false]); 
                $text = "需机器人创造者手动操作<b>4/4</b>\n<b>部署内联查询·请按以下步骤操作🈯️</b>\n1.给机器人<b>".'<a href="https://t.me/BotFather">BotFather</a>'."</b>发送：<b>/mybot</b> 命令\n2. 选择你的机器人\n3.进入菜单选：<b>BOT Setting</b> → <b>Inline mode</b>  \n4.如果显示：<b>Turn on</b> 点击一下即可！";
                $client->request('GET', "https://api.telegram.org/bot{$data['TOKEN']}/sendMessage?chat_id={$data['Admin']}&text={$text}&parse_mode=HTML&disable_web_page_preview=true")->getBody();   
                
            }
            
 
        // } catch (\Throwable $e) { 
        //     echo $e->getMessage();
        // }    
        
    }
    
}