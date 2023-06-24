<?php
namespace plugin\SwapTRX8bot\app\controller;

use Webman\RedisQueue\Client; #redis queue 队列
use think\facade\Db;#mysql https://www.kancloud.cn/manual/think-orm/1258003
use think\facade\Cache;#Cache https://www.kancloud.cn/manual/thinkphp6_0/1037634
use support\Redis;//redis缓存
// use TNTma\TronWeb\Address;
// use TNTma\TronWeb\Account;
// use TNTma\TronWeb\Tron;

use GuzzleHttp\Pool;
use GuzzleHttp\Client as Guzz_Client;
use GuzzleHttp\Psr7\Request as Guzz_Request; 
use GuzzleHttp\Promise as Guzz_Promise;
//内联
class inline_query extends Base{
    
    public function index($inline_query){     
        $bot = $this->BOT['API_BOT'];
        $by = $this->BOT['Admin']; 
        $img =  $this->BOT['WEB_URL']."/app/".Request()->plugin."/img/yt".rand(1,3).".png";
        $type='help';
        $value = $inline_query['query'];
        if(!empty($value)){  
            if(preg_match('/^T[\w]{33}$/i', $inline_query['query'], $return)){ 
                $type = "查询地址";
                $value = $inline_query['query']; 
            }else if(preg_match('/^[0-9a-z]{64}$/', $inline_query['query'], $return)){ 
                $type = "查询哈希";
                $value = $inline_query['query']; 
            }      
        } 
        
         
        
        switch ($type) {
            
            
            default: 
                $reply_markup = json_encode([
                    "inline_keyboard"=>[
                        [["text"=>'分享机器人',"switch_inline_query"=>""],
                        ["text"=>'查询钱包',"switch_inline_query_current_chat"=>""]
                        ], 
                        [["text"=>'使用机器人',"url"=>"https://t.me/{$bot}"], 
                        ["text"=>'添加到群组',"url"=>"https://t.me/{$bot}?startgroup=true"]]
                        ]
                ]);
                $tgret =  $this->get('/answerInlineQuery?inline_query_id='.$inline_query['id'].'&cache_time=1&results=[{"type":"article","id":"1","title":"@机器人引用说明","description":"暂未支持该指令\n点击查看详细信息","thumb_url":"'.$img.'","input_message_content":{"message_text":"\n\n*嘿嘿嘿...\n\n@'.$bot.'*","parse_mode":"Markdown","disable_web_page_preview":true},"reply_markup":'.$reply_markup.'}]');  
                break;
            
            case 'help':    
                
                $Temppath = "\plugin\\{$this->plugin}\app\controller\Template";
                $Template  =  new $Temppath; 
                $Template = $Template->reply_markup("help","private",1); 
                if(empty($Template['text'])){
                    $Template['text'] = "<b>
                    您可以@机器人做一些进行操作哟
                    \n\n发送钱包地址,就可以查询到账户详细信息
                    \n\n发送交易哈希就可以查看交易详细情况状态等
                    \n\n全网最高比例 24小时全自动兑换TRX</b>
                    ";   
                }
                if(empty($Template['reply_markup'])){
                    $Template['reply_markup'] = json_encode([
                    "inline_keyboard"=>[
                        [["text"=>'分享机器人',"switch_inline_query"=>""],
                        ["text"=>'查询钱包',"switch_inline_query_current_chat"=>""]
                        ], 
                        
                        [["text"=>'兑换TRX',"url"=>"https://t.me/{$bot}"], 
                        ["text"=>'联系老板',"url"=>"tg://user?id={$by}"]]
                        ]
                    ]);     
                } 
                 
                $tgret =  $this->get('/answerInlineQuery?inline_query_id='.$inline_query['id'].'&cache_time=1&results=[{"type":"article","id":"1","title":"@机器人引用说明","description":"1.发送地址就可以查询钱包信息\n2.可关注机器人自动兑换TRX","thumb_url":"'.$img.'","input_message_content":{"message_text":"'.$Template['text'].'","parse_mode":"HTML","disable_web_page_preview":true},"reply_markup":'.$Template['reply_markup'].'}]');  
                break;
                
                
                
                
                
                
                
                
                
            case '查询哈希':  
                echo '查询交易哈希';
                $formtext = "\n\n\n*来自* [@".$bot."](https://t.me/{$bot}) *的交易查询*\n\n";
                $arrtext = [
                    "查询哈希"=>'[**'.substr($inline_query['query'], -14).'](https://tronscan.org/#/transaction/'.$inline_query['query'].')',
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
                        [["text"=>'分享查询',"switch_inline_query"=>$inline_query['query']],
                        ["text"=>'再查一次',"switch_inline_query_current_chat"=>$inline_query['query']]
                        ]
                        
                        // [["text"=>'兑换TRX',"url"=>"https://t.me/{$data['data']['bot']}"],
                        // ["text"=>'联系作者',"url"=>"tg://user?id={$BOT['Admin']}"]]
                        
                        ]
                ]); 
                
                $client = new Guzz_Client(['timeout' => 8,'http_errors' => false,'headers' => ['TRON-PRO-API-KEY' => getenv('TRONSCAN_APIKEY')]]); 
                $promises = [
                    'tronscanapi' => $client->getAsync("https://apilist.tronscanapi.com/api/transaction-info?hash={$inline_query['query']}")
                ]; 
                $results = Guzz_Promise\unwrap($promises);//并发异步请求
                
                if(!empty($results['tronscanapi'])){ 
                    $tronscanapi = json_decode($results['tronscanapi']->getBody()->getContents(),true);  
                     
                    
                    if($tronscanapi['contractType'] == 1){//trx
                        $arrtext['所属区块']='['.$tronscanapi['block'].'](https://tronscan.org/#/block/'.$tronscanapi['block'].')';
                        $arrtext['交易时间']=date("Y-m-d H:i:s",substr($tronscanapi['timestamp'],0,10));
                        $arrtext['付款地址']=substr($tronscanapi['ownerAddress'],0,3).'..'.substr($tronscanapi['ownerAddress'],-10);
                        $arrtext['收款地址']=substr($tronscanapi['contractData']['to_address'],0,3).'..'.substr($tronscanapi['contractData']['to_address'],-10);
                        $arrtext['转账数量']='*'.sprintf("%f",$tronscanapi['contractData']['amount'] / 1000000)." TRX*";
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
                        $arrtext['所属区块']='['.$tronscanapi['block'].'](https://tronscan.org/#/block/'.$tronscanapi['block'].')';
                        $arrtext['交易时间']=date("Y-m-d H:i:s",substr($tronscanapi['timestamp'],0,10));
                        $arrtext['付款地址']=substr($tronscanapi['ownerAddress'],0,3)."..".substr($tronscanapi['ownerAddress'],-13);
                        
                        if(empty($tronscanapi['tokenTransferInfo'])){
                            $arrtext['收款地址']="合约触发";
                            $arrtext['转账数量']="非转账·trc20";
                            
                        }else{ 
                            $arrtext['收款地址']=substr($tronscanapi['tokenTransferInfo']['to_address'],0,3)."..".substr($tronscanapi['tokenTransferInfo']['to_address'],-13);
                            $arrtext['转账数量']="*".sprintf("%f",$tronscanapi['tokenTransferInfo']['amount_str'] / 1000000)." ".$tronscanapi['tokenTransferInfo']['symbol'].'*';
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
                $tgret =  $this->get('/answerInlineQuery?inline_query_id='.$inline_query['id'].'&cache_time=1&results=[{"type":"article","id":"1","title":"查询哈希 **'.substr($inline_query['query'], -10).'","description":"状态：'.$arrtext['交易状态'].'\n点击查看详细信息","thumb_url":"'.$img.'","input_message_content":{"message_text":"'.$formtext.$_text.'","parse_mode":"Markdown","disable_web_page_preview":true},"reply_markup":'.$reply_markup.'}]');
                
                var_dump($tgret);
                return true;
                
                
                
                
            case '查询地址':  
                $formtext = "\n\n\n*来自* [@".$bot."](https://t.me/{$bot}) *的钱包查询*\n\n";
                $subaddr = substr ($inline_query['query'], 0,4).'***'.substr ($inline_query['query'], 26);
                
                $arrtext = [
                    "查询地址"=>"[".$subaddr."](https://tronscan.org/#/address/".$inline_query['query'].")",
                    "TRX余额"=>0,  
                    "usdt余额"=>0,   
                    "质押冻结"=>0,   
                    "剩余能量"=>"0 / 0",   
                    "剩余带宽"=>"0 / 0",   
                    "交易笔数"=>"0 / 0",   
                    "收支比率"=>"0 / 0",   
                    "创建时间"=>'未知',   
                    "最后活跃"=>'未知',   
                ];
                  
                
 
                $reply_markup = json_encode([
                    "inline_keyboard"=>[
                        [["text"=>'分享查询',"switch_inline_query"=>$inline_query['query']],
                        ["text"=>'再查一次',"switch_inline_query_current_chat"=>$inline_query['query']]
                        ],
                        
                        [["text"=>'兑换TRX',"url"=>"https://t.me/{$bot}"],
                        ["text"=>'联系作者',"url"=>"tg://user?id={$by}"]]
                        ]
                ]);
                
                
                $client = new Guzz_Client(['timeout' => 8,'http_errors' => false]);  
                $TRONSCANclient = new Guzz_Client(['timeout' => 8,'http_errors' => false,'headers' => ['TRON-PRO-API-KEY' => getenv('TRONSCAN_APIKEY')]]);  
                
                $promises = [
                    'trongrid' => $client->getAsync("https://api.trongrid.io/v1/accounts/{$inline_query['query']}"),
                    'tronscan'   => $TRONSCANclient->getAsync("https://apilist.tronscan.org/api/account?address={$inline_query['query']}")
                ];
                $results = Guzz_Promise\unwrap($promises);//并发异步请求
                 
                
                if($results['trongrid']){ 
                    $res = json_decode($results['trongrid']->getBody()->getContents(),true);  
                }
                if($results['tronscan']){ 
                    $tronscan = json_decode($results['tronscan']->getBody()->getContents(),true);  
                }
                 
                 
                if(!$res['success']){   
                    //无效地址
                    $_text= str_replace("=", "：",http_build_query($arrtext, '', "\n")); 
                    $tgret =  $this->get('/answerInlineQuery?inline_query_id='.$inline_query['id'].'&cache_time=1&results=[{"type":"article","id":"1","title":"无效的地址","description":"'.$subaddr.'\n点击查看详细信息","thumb_url":"'.$img.'","input_message_content":{"message_text":"'.$formtext ."*很抱歉,你查询的地址无效\n\n*". $_text.'","parse_mode":"Markdown","disable_web_page_preview":true},"reply_markup":'.$reply_markup.'}]'); 
                  return true;
                }
                if(count($res['data']) < 1){  
                    $_text= str_replace("=", "：",http_build_query($arrtext, '', "\n")); 
                    $tgret =  $this->get('/answerInlineQuery?inline_query_id='.$inline_query['id'].'&cache_time=1&results=[{"type":"article","id":"1","title":"钱包尚未激活","description":"'.$subaddr.'\n点击查看详细信息","thumb_url":"'.$img.'","input_message_content":{"message_text":"'.$formtext ."*地址尚未激活,可预支TRX激活\n\n*". $_text.'","parse_mode":"Markdown","disable_web_page_preview":true},"reply_markup":'.$reply_markup.'}]'); 
                    return true;
                } 
                 
                 
                $arrtext['TRX余额'] = "*".($res['data'][0]['balance'] / 1000000)."*";
                foreach ($res['data'][0]['trc20'] as $key=>$value) { 
                    if(!empty($value['TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t'])){
                        $arrtext['usdt余额'] = "*".($value['TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t'] / 1000000)."*";   
                        break;
                    }   
                }
                
                if(!empty($res['data'][0]['account_resource']['frozen_balance_for_energy']['frozen_balance'])){
                    $arrtext['质押冻结'] = "*".($res['data'][0]['account_resource']['frozen_balance_for_energy']['frozen_balance'] / 1000000)."*";  
                }
 
                 
                $arrtext['剩余能量']   = $tronscan['bandwidth']['energyRemaining']." / ".$tronscan['bandwidth']['energyLimit'];
                $arrtext['剩余带宽']   = $tronscan['bandwidth']['freeNetRemaining']." / ".$tronscan['bandwidth']['freeNetLimit'];
                $arrtext['交易笔数']   = "*{$tronscan['transactions']}*笔"; 
                $arrtext['收支比率']   = "收*{$tronscan['transactions_in']}* / 付*{$tronscan['transactions_out']}*";  
                
                if(!empty($res['data'][0]['create_time'])){ 
                    $arrtext['创建时间'] = date("Y-m-d H:i:s",substr ($res['data'][0]['create_time'],0,10));
                }
                if(!empty($res['data'][0]['latest_opration_time'])){ 
                    $arrtext['最后活跃'] = date("Y-m-d H:i:s",substr ($res['data'][0]['latest_opration_time'],0,10));
                } 
                
                
                
               $_text= str_replace("=", "：",http_build_query($arrtext, '', "\n")); 
               $tgret =  $this->get('/answerInlineQuery?inline_query_id='.$inline_query['id'].'&cache_time=1&results=[{"type":"article","id":"1","title":"查询成功","description":"'.$subaddr.'\n点击查看详细信息","thumb_url":"'.$img.'","input_message_content":{"message_text":"'.$formtext . $_text.'","parse_mode":"Markdown","disable_web_page_preview":true},"reply_markup":'.$reply_markup.'}]'); 
                break;
                 
            
             
        }
         
        
        return true; 
    }
    
}