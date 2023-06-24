<?php
namespace app\validate;

use think\Validate;

// 文档：https://www.kancloud.cn/manual/thinkphp6_0/1037629 
// max:25最大长度，length:6,15 区间长度，require 必须，number 数字，email 邮箱，'between:1,10区间数值，notBetween:1,10 非区间数字，allowIp:114.45.4.55 ip范围
// after:2016-3-18 某个日期之后
// before:2016-10-01日期之前
// expire:2016-2-1,2016-10-01 有效日期之内

class notify extends Validate{ 
    
    // 定义规则
    protected $rule =   [
        'Key'  => 'require|length:6,8',
        'no'  => 'require',
        'tel'  => 'require',
        'content'  => 'require',
        'type'  => 'require',
        'myid'  => 'require|length:6',
        'money'  => 'require|between:0.01,50000',
        'oktime'   => 'require|number|length:10,13', 
        'sign'   => 'require|length:32',  
    ];
    


    // 定义信息
    protected $message  =   [
        'Key.require' => 'key :: 秘钥错误·请正确配置监控助手',
        'Key.length' => 'key :: 秘钥错误·请正确配置监控助手',
        'no.require' => '? :: 流水订单号不能为空', 
        'tel.require' => '? :: 手机号码不能为空',
        'content.require' => '? :: 内容不能为空',
        
        'type.require' => '? :: 类型不能为空',
        'myid.require' => '? :: 商户不能为空',
        'myid.length' => '? :: 商户ID错误',
        'money.require' => '? :: 订单金额不能为空',
        'money.between' => '? :: 订单金额错误0.01 - 50000区间',
        'oktime.require' => '? :: 订单时间戳不能为空',
        'oktime.number' => '? :: 订单时间戳只能为纯数字',
        'oktime.length' => '? :: 订单时间戳长度为10位数/java只取到秒',
        'sign.require' => 'sign :: 签名不能为空',
        'sign.length' => 'sign :: 签名长度错误/32位MD5', 
    ];

    //定义场景
    protected $scene = [
        'alipayzz'  =>  ['Key','no','money','oktime','sign'],
        '100'  =>  ['myid','type','tel','content','oktime','sign'],
    ];
    
    
}