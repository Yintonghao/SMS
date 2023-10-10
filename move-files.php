<?php
$filesToMove = [
    '/src/config/Dok.php',
];

$destinationDirectory = '';
foreach (explode('\\',__DIR__) as $value){
    if($value == 'vendor') break;
    $destinationDirectory .= DIRECTORY_SEPARATOR . $value;
}
$destinationDirectory = ltrim($destinationDirectory,DIRECTORY_SEPARATOR);

foreach ($filesToMove as $file) {
    $source = __DIR__ . '/' . $file;
    $destination = $destinationDirectory . $destinationDirectory . 'app\\common\\lib\\' . basename($file);

    if (file_exists($source)) {
        if (!file_exists(dirname($destination))) {
            mkdir(dirname($destination), 0777, true);
        }
        if (rename($source, $destination)) {
            echo "Moved '{$source}' to '{$destination}'." . PHP_EOL;
        } else {
            echo "Failed to move '{$source}'." . PHP_EOL;
        }
    } else {
        echo "File '{$source}' doesn't exist." . PHP_EOL;
    }
}
