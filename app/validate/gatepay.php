<?php
namespace app\validate;

use think\Validate;

// 文档：https://www.kancloud.cn/manual/thinkphp6_0/1037629 
// max:25最大长度，length:6,15 区间长度，require 必须，number 数字，email 邮箱，'between:1,10区间数值，notBetween:1,10 非区间数字，allowIp:114.45.4.55 ip范围
// after:2016-3-18 某个日期之后
// before:2016-10-01日期之前
// expire:2016-2-1,2016-10-01 有效日期之内

class gatepay extends Validate
{ 
    // 定义规则
    protected $rule =   [
        'myid'  => 'require|length:6',
        'order'  => 'require|length:5,50',
        'money'  => 'require|between:0.01,500000',
        'returnurl'   => 'require', 
        'notifyurl'   => 'require', 
        'time'   => 'require|number|length:10', 
        'paycode'   => 'require|number|length:3', 
        'sign'   => 'require|length:32',  
        'bankname'  => 'require|length:2,18',
        'bankuser'  => 'require|length:2,5',
        'bankcard'  => 'require|length:10,30',
        'withdrawQueryUrl'  => 'require',
        'callToken'  => 'require',
    ];

    // 定义信息
    protected $message  =   [
        'myid.require' => 'myid :: 商户ID不能为空',
        'myid.length'     => 'myid :: 商户ID错误',
        'order.require' => 'order :: 订单号不能为空',
        'order.length'     => 'order :: 订单号长度5-50',
        'money.require' => 'money :: 订单金额不能为空',
        'money.between' => 'money :: 订单金额错误0.01 - 500000区间',
        'returnurl.require' => 'returnurl :: 同步跳转地址不能为空',
        'notifyurl.require' => 'notifyurl :: 异步通知地址不能为空', 
        'time.require' => 'time :: 订单时间戳不能为空',
        'time.number' => 'time :: 订单时间戳只能为纯数字',
        'time.length' => 'time :: 订单时间戳长度为10位数/java只取到秒',
        'paycode.require' => 'paycode :: 支付编号不能为空 - 后台>商户信息>对接信息 - 查看编码',
        'paycode.number' => 'paycode :: 支付编号为纯数字 - 后台>商户信息>对接信息 - 查看编码',
        'paycode.length' => 'paycode :: 支付编号长度为3位数 - 后台>商户信息>对接信息 - 查看编码',
        'sign.require' => 'sign :: 签名不能为空',
        'sign.length' => 'sign :: 签名长度错误/32位MD5', 
        'bankname.require' => '收款银行·不能为空',
        'bankname.length'     => '收款银行·长度2-18',
        'bankuser.require' => '收款人姓名·不能为空',
        'bankuser.length'     => '收款人姓名·长度2-5',
        'bankcard.require' => '银行卡号·不能为空',
        'bankcard.length'     => '银行卡号·长度10-30',
        'withdrawQueryUrl.require' => '反查地址·不能为空',
        'callToken.require' => '反查Token·不能为空',
    ];

    //定义场景
    protected $scene = [
        'pay'  =>  ['myid','order','money','returnurl','notifyurl','time','sign','paycode'],
        'settle'  =>  ['myid','order','money','notifyurl','time','sign','bankname','bankuser','bankcard'],
        'settlequery'  =>  ['myid'],
        'daifu100035'  =>  ['withdrawQueryUrl','callToken'],
    ]; 

}