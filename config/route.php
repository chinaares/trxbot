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

use Webman\Route;

 
  

  Route::any('/api/system/rolemenu/{id}',  [app\api\controller\system::class, 'rolemenu']); #admin 获取角色权限
  Route::any('/api/system/menu[/{id}]',  [app\api\controller\system::class, 'menu']);  #admin 菜单管理
  Route::any('/api/verify/raw[/{id}]',  [app\api\controller\verify::class, 'raw']);  #admin 菜单管理
 
//  Route::options('[{path:.+}]', function (){
//     return response('');
// });
 
 


 
 
 



 