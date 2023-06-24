<?php
ini_set('memory_limit', '512M');
/**
 * Here is your custom functions.
 */
function mobilesystem($agent){
        $agent = strtolower($agent);
  		if(strpos($agent, 'iphone')){
         return '1';
        }elseif(strpos($agent, 'android')){
         return '2';
        }elseif(strpos($agent, 'ios')){
         return '1';
        }elseif(strpos($agent, 'linux')){
         return '2';
        }elseif(strpos($agent, 'mobile')){
         return '3';
        }elseif(strpos($agent, 'wap')){
         return '3';
        }elseif(strpos($agent, 'windows')){
         return '4';	
        }elseif(strpos($agent, 'os x')){
         return '5';	
        }else{
         return '0';	
        } 
      
}

function curl_get_https($url,$headers,$ssr="",$time=6){
    $curl = curl_init(); 
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_TIMEOUT, $time);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    if(!empty($headers)){
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);//设置请求头
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在
    if($ssr){
       curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //http
curl_setopt($curl, CURLOPT_PROXY, "121.207.92.150"); //代理服务器地址
curl_setopt($curl, CURLOPT_PROXYPORT, 3828); //代理服务器端口
        curl_setopt($curl, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
        
    }
    $tmpInfo = curl_exec($curl);     //返回api的json对象
    curl_close($curl);
    return $tmpInfo;   
}

function quwenbenzhongjian($str,$leftStr,$rightStr){
	if(strrpos($str,$leftStr) == false || strrpos($str,$rightStr) == false){return '';}
	$tArr = explode($leftStr,$str);
	$str = $tArr[1];
	$tArr = explode($rightStr,$str);
	return $tArr[0];
}
   

function curl_post_https($url,$data,$headers=null){ // 模拟提交数据函数
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
    // curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    // curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    // curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    if(!empty($headers)){
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);//设置请求头
    }
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
    curl_setopt($curl, CURLOPT_TIMEOUT, 10); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec($curl); // 执行操作
    curl_close($curl); // 关闭CURL会话
    return $tmpInfo; // 返回数据
}



function curl_cookie_html($url,$header = false,$ack = false,$bck = false){
	$cookie_jar = dirname(__FILE__)."/cookie.txt";
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	if($ack){
	  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);//读取携带cookie  
	}
	 
	curl_setopt($ch, CURLOPT_HEADER, 0 ); 
	if($header){
	   curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
	}
	 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 ); 
	if($bck){
	  curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);//保存更新cookie  
	} 
	$return = curl_exec( $ch ); 
	curl_close( $ch ); 
	return $return;
}