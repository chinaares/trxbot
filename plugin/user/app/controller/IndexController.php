<?php

namespace plugin\user\app\controller;

use support\Request;

class IndexController{

    public function index(Request $request){ 
        $path = $request->path();
        if(substr($path, -1) != '/'){
            return redirect($path.'/');  #访问地址如果不带/ 则跳转到带/
        } 
        return response()->file(base_path() . '/plugin/'.$request->plugin.'/public/index.html');
        
    }

}
