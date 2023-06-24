<?php
/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

use support\Request;
use Workerman\Worker;

$debug = true;     
if(Phar::running()){
    $debug = false;      
}else if(count($_SERVER['argv']) >= 3){ 
    $debug = false;  
}  
return [
    'debug' => $debug,//调试模式 - 取决于start  还是 -d 
    'error_reporting' => E_ALL, 
    'default_timezone' => 'Asia/Shanghai',
    'request_class' => Request::class,
    'public_path' => base_path() . DIRECTORY_SEPARATOR . 'public',
    'runtime_path' => base_path(false) . DIRECTORY_SEPARATOR . 'runtime',
    'controller_suffix' => '',//控制器后缀
    'controller_reuse' => false,//是否复用控制器 
];
