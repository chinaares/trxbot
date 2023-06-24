<?php

namespace plugin\install\app\controller;

use support\Request;

class trxbot{

    public function index(){
        $lock = run_path() . DIRECTORY_SEPARATOR . 'runtime/ins_trxbot.lock';
        if(!is_file($lock)){
            return view('trxbot', ['name' => '电报机器人部署']); 
        } else {
            return json(['code' => 0,'msg'=>"电报机器人已安装成功,如果需要重新部署初始化,请删除：runtime/ins_trxbot.lock 文件,并重启项目" ]);  
        }
        
         
    }

}
