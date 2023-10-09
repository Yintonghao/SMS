<?php

namespace Mydom\Sms;

class CopyClass
{
    public static function copyFiles()
    {
        $sourceDir = __DIR__ . '/path/to/source';
        $targetDir = __DIR__ . '/path/to/target';

        // 实际的文件拷贝逻辑，可以使用PHP内置的函数，如copy()或rename()等

        copy('config/Dok.php',getcwd().'\\app\\Dok.php');
    }
}