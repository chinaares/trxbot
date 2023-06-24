<?php
namespace app\middleware;

use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;

class OPTIONS implements MiddlewareInterface{
    public function process(Request $request, callable $next) : Response{  
        $response = $request->method() == 'OPTIONS' ? response('') : $next($request);
        $response->withHeaders([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET,POST,PUT,DELETE,OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type,Authorization,X-Requested-With,Accept,Origin,requesttype',
            'Access-Control-Expose-Headers'=> 'Content-Disposition',//返回给前端的文件头
        ]); 
        return $response;
    }
}