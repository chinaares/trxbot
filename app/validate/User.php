<?php
namespace app\validate;

use think\Validate;

// 文档：https://www.kancloud.cn/manual/thinkphp6_0/1037629 
// max:25最大长度，length:6,15 区间长度，require 必须，number 数字，email 邮箱，'between:1,10区间数值，notBetween:1,10 非区间数字，allowIp:114.45.4.55 ip范围
// after:2016-3-18 某个日期之后
// before:2016-10-01日期之前
// expire:2016-2-1,2016-10-01 有效日期之内

class User extends Validate
{
    // 定义规则
    protected $rule =   [
        'username'  => 'require|length:5,15',
        'password'  => 'require|length:6,15',
        'formkey'  => 'require',
        'code'   => 'require|length:5', 
        'googlecode'   => 'number|length:6', 
        'email' => 'email',
        'age' => 'age',
    ];

    // 定义信息
    protected $message  =   [
        'username.require' => '账号不能为空',
        'username.length'     => '账号长度为5-15位',
        'password.require' => '密码不能为空',
        'password.length'     => '密码长度为6-32位',
        'code.require' => '验证码不能为空',
        'code.length'     => '验证码长度为5位',  
        'googlecode.number' => '谷歌验证码为6位整数',
        'googlecode.length'     => '谷歌验证码长度为6位', 
        'formkey.require' => 'formkey不能为空',
        'age.number'   => '年龄必须是数字',
        'email'        => '邮箱格式错误',    
    ];

    //定义场景
    protected $scene = [
        'reg'  =>  ['username','password','code','formkey','googlecode'],
    ];

}