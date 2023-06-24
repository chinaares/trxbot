<?php

namespace plugin\SwapTRX8bot\app\controller;

use support\Request;
use support\Response;

class Base extends Config{
 
    protected $model = null;
    
    protected function webapp(){ 
        return $this->BOT['WEB_URL']."/app/".Request()->plugin."/%23";
    }
    
    public function res(){  
        echo "plugin".Request()->plugin;
        return $this->BOT;
    }
    
}
    
    
    
 
