<?php
return [
    'enable' => true,//启用
    'key' => 'base68:N721v3Gt2I58HH7oiU7a70PQ+i8ekPWRqwI+JSnM1wo=',//加密·密钥
    'field' => ['id','username','tenantId','roleId','upid','tgid','google','sub','subID','remark','plugin'],//允许存入的字段 
    'iss' => 'http://www.baidu.com',//令牌签发者
    'exp' => 86400 //令牌有效期 
];