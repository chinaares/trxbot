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


class upsql extends Command{
    protected static $defaultName = 'upsql';
    protected static $defaultDescription = '重新导入数据库-覆盖';

 
    protected function execute(InputInterface $input, OutputInterface $output){    
        $sql_file = run_path() . DIRECTORY_SEPARATOR ."support/data.php";
        if (!is_file($sql_file)) {
             echo "\n\033[0;31m错误：更新的数据文件不存在\033[0m\n"; 
             return self::SUCCESS;
         }
         $sql_query = file_get_contents($sql_file);
         $sql_query = removeComments($sql_query);
         $sql_query = splitSqlFile($sql_query, ';');
         foreach ($sql_query as $sql) { 
             Db::query($sql); 
         }    
        $output->writeln("\n强制更新数据 \033[0;32m [ok]\033[0m");
        return self::SUCCESS;
    }

}
