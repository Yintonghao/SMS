<?php

namespace Mydom\Sms;

class CopyClass
{
    public static function copyFiles()
    {
        $filename = 'Dok.php';
        $sourceDir = __DIR__ . '\\config\\' . $filename;
        if (!file_exists($sourceDir)) {
            exit('源文件不存在');
        }

        $appPath = app()->getAppPath();
        $targetDir = 'common\\lib';
        foreach (explode('\\', $targetDir) as $value) {
            $appPath .= $value.'\\';
            if (!is_dir($appPath)) {
                mkdir($appPath);
            }
        }

        $appPath .= $filename;
        if (!file_exists($appPath)) {
            copy($sourceDir, $appPath);
        }
    }
}