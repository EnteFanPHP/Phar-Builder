<?php

function build(string $pharName, string $folderPath, string $path4Require) {
    $timestarted = microtime(true);
    try {
        $bootstrap = explode("/", $path4Require);
        $bootstrap = $bootstrap[count($bootstrap)-1];
        echo $bootstrap;
        $phar = new Phar($pharName.".phar", 0);
        $phar->setMetaData(["bootstrap"=> $bootstrap]);
        $phar->setStub(
        '<?php require("phar://" . __FILE__ . "/'.$path4Require.'"); __HALT_COMPILER();'
        );
        $phar->startBuffering();
        
        $directory = new \RecursiveDirectoryIterator($folderPath, \FilesystemIterator::SKIP_DOTS);
        $iterator = new \RecursiveIteratorIterator($directory);
        $count = count($phar->buildFromIterator($iterator, $folderPath));
        
        $phar->stopBuffering();
        $timetooked = round(microtime(true) - $timestarted, 3) ." Seconds";
    } catch(Exception $e) {
        echo $e->getMessage();
    }
}
