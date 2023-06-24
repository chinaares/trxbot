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


class ckmodel extends Command
{
    protected static $defaultName = 'ckmodel';
    protected static $defaultDescription = '检查数据表对应模型文件是否存在 - 不存在则创建';

    /**
     * @return void
     */
    protected function configure(){
        $this->addArgument('name', InputArgument::OPTIONAL, 'Name description');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output){   
        $table_info = Db::query('SHOW TABLE STATUS');
        foreach ($table_info as $value) {
            
            $table = explode("tb_",$value['Name']);
            if(count($table) > 1){
                $lock = run_path() . DIRECTORY_SEPARATOR . "app/model/{$table[1]}.php"; 
                if(!is_file($lock)){
$model = "<?php
namespace app\model;

use think\Model;

class {$table[1]} extends Model {
    //数据表名
    // protected \$name = '';
    // 模型数据不区分大小写
    protected \$strict = true;
    // 数据转换为驼峰命名
    protected \$convertNameToCamel = false;
}
";
                    $output->writeln("\033[0;32m创建模型文件：{$table[1]}.php\033[0m");
                    file_put_contents ($lock,$model); 
                }
            }
             
            // code...
        } 
        $output->writeln("\n\033[1;33m数据库模型文件检查完成！\033[0m");
        return self::SUCCESS;
    }

}
