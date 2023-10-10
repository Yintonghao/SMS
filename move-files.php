<?php
$filesToMove = [
    '/src/config/Dok.php',

];
$destinationDirectory = 'static';
foreach ($filesToMove as $file) {
    $source = __DIR__ . '/' . $file;
    $destination = __DIR__ . '/' . $destinationDirectory . '/' . basename($file);

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
