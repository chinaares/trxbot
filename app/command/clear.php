<?php

namespace app\command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

// use GuzzleHttp\Pool;
// use GuzzleHttp\Client as Guzz_Client;
// use GuzzleHttp\Psr7\Request as Guzz_Request; 
// use GuzzleHttp\Promise as Guzz_Promise;

// use TNTma\TronWeb\Address;
// use TNTma\TronWeb\Account;
// use TNTma\TronWeb\Tron; 

use think\facade\Db;


class clear extends Command{
    protected static $defaultName = 'deldata';
    protected static $defaultDescription = '清理出干净数据库';

 
    protected function execute(InputInterface $input, OutputInterface $output){    
        $sql = "
        DELETE FROM `tb_bot_vip_paylog`; 
        alter table `tb_bot_vip_paylog` auto_increment =1; 
        DELETE FROM `tb_bot_vip_setup`; 
        alter table `tb_bot_vip_setup` auto_increment =1; 
        DELETE FROM `tb_bot_vip_userlist`; 
        alter table `tb_bot_vip_userlist` auto_increment =1; 
        DELETE FROM `tb_trx_setup`; 
        alter table `tb_trx_setup` auto_increment =1; 
        DELETE FROM `tb_bot_xufei_log`; 
        alter table `tb_bot_xufei_log` auto_increment =1; 
        DELETE FROM `tb_bot_group_user`;
        alter table `tb_bot_group_user` auto_increment =1; 
        DELETE FROM `tb_bot_list`;
        alter table `tb_bot_list` auto_increment =1; 
        DELETE FROM `tb_bot_group`;
        alter table `tb_bot_group` auto_increment =1; 
        DELETE FROM `tb_bot_total_tg`;
        alter table `tb_bot_total_tg` auto_increment =1; 
        DELETE FROM `tb_keep_log`;
        alter table `tb_keep_log` auto_increment =1; 
        DELETE FROM `tb_keep_logc`;
        alter table `tb_keep_logc` auto_increment =1; 
        DELETE FROM `tb_keep_setup`;
        alter table `tb_keep_setup` auto_increment =1; 
        DELETE FROM `tb_keep_total`;
        alter table `tb_keep_total` auto_increment =1; 
        DELETE FROM `tb_keep_totalz`;
        alter table `tb_keep_totalz` auto_increment =1;  
        DELETE FROM `tb_keep_user`;
        alter table `tb_keep_user` auto_increment =1;
        ";    
        $sql_query = splitSqlFile($sql, ';');
         foreach ($sql_query as $sql) { 
             Db::query($sql);
             $output->writeln("\033[0;32m{$sql}\033[0m");
         }    
         
        shell_exec("rm -rf ".run_path() . DIRECTORY_SEPARATOR ."runtime/cache");
        $output->writeln("\n\033[1;33m数据库清理完成！\033[0m");
        return self::SUCCESS;
    }

}
