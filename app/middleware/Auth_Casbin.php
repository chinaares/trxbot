<?php
namespace app\middleware;

use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;  
use support\Redis;
use think\facade\Cache;

#use Shopwwi\WebmanAuth\Facade\Auth;
#use Shopwwi\WebmanAuth\Exception\JwtTokenException;
use Tntma\Tntjwt\Auth;


class Auth_Casbin implements MiddlewareInterface{
    
    public function process(Request $request, callable $next) : Response{     
        if($request->controller == 'app\api\controller\verify'){//这个控制器不用进行中间件验证  
            $request->user = Auth::user(true); 
            return $next($request);
        }else{   
          //验证token有效期  
          $user = Auth::user(true);    
          if(empty($user)){  
            return json(['code' => 401, 'msg' => '未登录,请先登录','c'=>25]); #return redirect('/login'); 
          }else{
            $request->user = $user;  
            $JWTuid = $request->user->subID ?? $request->user->id;
          } 
          
           
          
          
          #JWT无限制登录用户数量时·通过redis Hash 检查当前token是否有效
        //   if(config('plugin.shopwwi.auth.app.guard.user.num') === -1){ 
        //       $JWT_MD5 = md5($request->header("authorization"));    
        //       if(!Redis::Hexists("HJWTMD5_{$JWTuid}",$JWT_MD5)){ 
        //           return json(['code' => 401, 'msg' => '登录过期,请重新登录']);  
        //       }     
        //   }
        $JWT_MD5 = md5($request->header("authorization"));     
          if(!Redis::Hexists("HJWTMD5_{$JWTuid}",$JWT_MD5)){ 
              return json(['code' => 401, 'msg' => '登录过期,请重新登录']);  
          }
            
          
            
            
            
          
          #获取请求方式和path
          $act = $request->method(); 
          $url = $request->path();   
          
          #获取菜单不验证角色权限和谷歌权限
          if($url == '/api/user/auth'){
              return $next($request);  
          }  
           
            
          
          
       
            //------------------------------------用户角色谷歌授权效验------------------------------  
            
             //用户已开启googel验证
             if($request->user->google){ 
                //是否存在谷歌验证数据(每次验证时600s过期)
                if(!Redis::exists("GYZ_{$JWTuid}")){
                    //取得用户需要进行googel验证的地址
                    $GoogUrl = Redis::hget("googurl", $JWTuid);
                    //判断当前访问url是否需要进行谷歌验证
                    if(stripos($GoogUrl, $url)){
                        return json(['code' => 402, 'msg' => '本次操作需要进行谷歌验证']);
                    } 
                }   
             }
            
             
            
            #不对id为:100000的用户进行权限效验
            if($user->id == 100000){ 
                return $next($request); 
            }
             
            
            
            
            
            
            //------------------------------------用户角色权限效验------------------------------  
            if(empty($user->sub)){  
                $Permission = Redis::hget("casbin_{$user->roleId}", $user->id."_api");  //主账号鉴权
            }else{
                $Permission = Redis::hget("submenu", $user->sub);  //子账号鉴权
            }
            
             
            if(!$Permission){ 
                #Redis::hset("casbin_{$user->roleId}",$user->id."_api", "UPDATE");
                return json(['code' => 301, 'msg' => "发现新的更新包.." ]);  #没有权限数据提示前端刷新
            }
            
            #不对GET请求进行权限判断
            if($act != "GET"){ 
                if(!stripos($Permission, $url)){
                    return json(['code' => 101, 'msg' => "没有权限操作该接口",'url'=>$url ]); 
                } 
             }    
             
             
             
             
          //一切通过 next 
          return $next($request); 
        }
                 
         
    }
}