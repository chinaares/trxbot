<?php
return [
    'enable'            => true, 
    'build_dir'  => BASE_PATH . DIRECTORY_SEPARATOR . 'build', 
    'phar_filename'     => 'webman.phar', 
    'bin_filename' => 'webman', 
    'signature_algorithm'=> Phar::SHA256,  
    'private_key_file'  => '', 
    
    #完整打包
    // 'exclude_pattern'   => '#^(?!.*(config/plugin/webman/console/app.php|composer.json|LICENSE|README.md|/.github/|/.idea/|/.git/|/.setting/|/runtime/|/vendor-bin/|/build/|/z_TIPS/))(.*)$#',
    
    #兑币机打包
    // 'exclude_pattern'   => '#^(?!.*(config/plugin/webman/console/app.php|composer.json|LICENSE|README.md|/.github/|/.idea/|/.git/|/.setting/|/runtime/|/vendor-bin/|/build/|/z_TIPS/|/app/api/controller/up.php|/app/api/controller/system.php|/app/api/controller/payment.php|/plugin/adminbot/|/plugin/keepbot/|/plugin/tgvipbot/|/app/gate/|/config/route.php))(.*)$#',
    
    
    #keepbot打包(托管模式)
     'exclude_pattern'   => '#^(?!.*(config/plugin/webman/console/app.php|composer.json|LICENSE|README.md|/.github/|/.idea/|/.git/|/.setting/|/runtime/|/vendor-bin/|/build/|/z_TIPS/|/app/api/controller/up.php|/app/api/controller/system.php|/app/api/controller/payment.php|/plugin/SwapTRX8bot/|/plugin/tgvipbot/|/app/gate/|/config/route.php))(.*)$#',

    'exclude_files'     => [
        '.env', 'LICENSE', 'composer.json', 'composer.lock','start.php','windows.php','windows.bat'
    ]
];
