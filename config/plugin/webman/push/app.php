<?php
return [
    'enable'       => true,
    'websocket'    => 'websocket://0.0.0.0:3011',//客户端链接
    'api'          => 'http://0.0.0.0:3012',//服务端发消息端口
    'app_key'      => 'd44c681430dcbda971ca5c01ce2dfd1a',//客户端key
    'app_secret'   => '05e40ae9c9dbeddf606dcf4681ddca29',//服务端发消息所需
    'channel_hook' => 'http://127.0.0.1:8686/plugin/webman/push/hook',//上下线监听
    'auth'         => '/plugin/webman/push/auth'//鉴权地址
];

 