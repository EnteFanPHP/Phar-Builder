<?php

function build(string $pharName, string $folderPath, string $path4Require) {
    if (!is_dir($folderPath)) {
        throw new Exception("Error: $folderPath is not a valid directory.");
    }
    if (!file_exists($path4Require)) {
        throw new Exception("Error: $path4Require does not exist.");
    }
    try {
        $bootstrap = explode("/", $path4Require);
        $bootstrap = $bootstrap[count($bootstrap)-1];
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
        return true;
    } catch(Exception $e) {
        echo $e->getMessage();
        return false;
    }
}
