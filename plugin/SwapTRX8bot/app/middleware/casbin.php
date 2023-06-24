<?php
namespace plugin\SwapTRX8bot\app\middleware;

use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;  
use support\Redis;
use think\facade\Cache; 
use Tntma\Tntjwt\Auth;


class casbin implements MiddlewareInterface{
    
    public function process(Request $request, callable $next) : Response{     
       #return json(['code' => 401, 'msg' => 'no','c'=>25]);  
    # var_dump($request->header("authorization"));
        $pathArr = explode("\\",$request->controller); 
         if($pathArr[count($pathArr)-1] == 'Telegram' || $pathArr[count($pathArr)-1] == 'Index'){//这个控制器不用进行中间件验证   
             return $next($request);
         }else{    
            $user = Auth::user(true);    
            if(empty($user)){    
                return json(['code' => 401, 'msg' => '未登录,请先登录','c'=>25]);  
            }else{
                $request->user = $user;   
            } 
            
            if($pathArr[count($pathArr)-1] == "Admin_api"){
                $BOT  = Cache::get("@PonyYun");  
                if($BOT['Admin'] != $user->tgid){
                    return json(['code' => 0,'msg'=>"无权访问"]);
                }      
            }
            
            
            // $JWT_MD5 = md5($request->header("authorization"));     
            // if(!Redis::Hexists("TG_login_{$user->id}",$JWT_MD5)){ 
            //   return json(['code' => 401, 'msg' => '登录过期,请重新登录']);  
            // }
 
            
 
            
 
              
           //一切通过 next 
           return $next($request); 
         }
        
              
         
    }
}
