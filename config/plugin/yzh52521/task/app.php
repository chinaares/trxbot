<?php
return [
    'enable' => true,
    'task'   => [
        'listen'            => '0.0.0.0:2345',
        'crontab_table'     => 'sys_crontab', //任务计划表
        'crontab_table_log' => 'sys_crontab_log',//任务计划流水表
        'debug'             => false, //控制台输出日志
        'write_log'         => false,// 任务计划日志
    ],
];
