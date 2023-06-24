<?php
namespace plugin\SwapTRX8bot\app\controller;
 
use support\Request;



class Auth extends Base{
    
    //鉴权
    public function index(Request $request){ 
          $authData = $request->post();  
          if(empty($authData['hash']) || empty($authData['user']) || empty($authData['date'])){
              return json(["code"=>0,"msg"=>"请在电报机器人：@".$this->BOT['API_BOT']." 内打开本页面",]); 
              
          }
          $check_hash = $authData['hash']; 
          unset($authData['hash']); 
          $data_check_arr=[]; 
          foreach ($authData as $key => $value) {
              if(is_array($value)){
                  $data_check_arr[] = $key . '=' . json_encode($value,JSON_UNESCAPED_UNICODE);
              }else{
                  $data_check_arr[] = $key . '=' . $value;
              }  
          } 
          sort($data_check_arr);
          $data_check_string = implode("\n", $data_check_arr);  
          $secret = hash_hmac('sha256', $this->BOT['API_TOKEN'], 'WebAppData',TRUE);
          $hash = hash_hmac('sha256', $data_check_string, $secret); 
          if($hash != $check_hash){
              return json(["code"=>0,"msg"=>"小伙子,不要搞事哟,联系：@gd801",]);   
          } 
          if ((time() - $authData['auth_date']) > 86400) {
              return json(["code"=>0,"msg"=>"登录过期,请重新登录哟",]); 
          } 
          
        return json(["code"=>1,"msg"=>"成功","data"=>$authData['user']]); 
    }
    
}
