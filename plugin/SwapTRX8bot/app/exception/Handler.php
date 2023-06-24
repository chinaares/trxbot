<?php
namespace plugin\SwapTRX8bot\app\exception;

use Throwable;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\RedisQueue\Client; #redis queue 队列

/**
 * Class Handler
 * @package Support\Exception
 */
class Handler extends \support\exception\Handler
{
    public function render(Request $request, Throwable $exception): Response
    {
        $code = $exception->getCode(); 
        if ($request->expectsJson()) {
            $json = ['code' => $code ? $code : 500, 'message' => $this->_debug ? $exception->getMessage() : 'Server internal error', 'type' => 'failed'];
            $this->_debug && $json['traces'] = (string)$exception;
            return new Response(200, ['Content-Type' => 'application/json'],
                \json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }
        $error =  \nl2br((string)$exception) ?? 'Server internal error';
        preg_match('/(.*)\sin.*(plugin|vendor)(.*):([0-9]+)/i', $error, $return); 
        if(count($return) == 5){
            $explode = explode(".",$return[3]);
            $error = "项目运行错误\n路径：{$return[2]}{$explode[0]}\n行号：{$return[4]}\n错误：{$return[1]}";
            echo "\n\033[0;31m{$error}\033[0m\n"; 
            $queueData['plugin'] = $request->plugin; 
            $queueData['type'] = "Exception"; 
            $queueData['text'] = $error; 
            #Client::send('TG_queue',$queueData);
        }
        return new Response(500, [], $error);
    }
}
