<?php

$dir = '/rename/'; // Замените на вашу директорию
$textFile = '/name.txt'; // Замените на ваш текстовый файл
$destinationDir = '/images'; // Замените на вашу целевую директорию

if (!file_exists($textFile) || !is_readable($textFile)) {
    echo "Файл $textFile не найден или недоступен для чтения.\n";
    exit(1);
}

$newNames = file($textFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if (empty($newNames)) {
    echo "Файл $textFile пуст.\n";
    exit(1);
}

if (!file_exists($destinationDir) || !is_dir($destinationDir)) {
    if (!mkdir($destinationDir, 0755, true)) {
        echo "Не удалось создать директорию $destinationDir.\n";
        exit(1);
    }
}

if ($handle = opendir($dir)) {
    while (false !== ($fileName = readdir($handle))) {
        if ($fileName === "." || $fileName === "..") {
            continue;
        }

        if (!empty($newNames)) {
            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
            $newName = array_shift($newNames);

            if ($ext) {
                $newName .= ".$ext";
            }

            rename("$dir/$fileName", "$destinationDir/$newName");
            echo "Файл $fileName переименован в $newName и перемещен в $destinationDir\n";
        } else {
            echo "В файле $textFile недостаточно новых имен для всех файлов.\n";
            break;
        }
    }
    closedir($handle);

    file_put_contents($textFile, implode("\n", $newNames));

} else {
    echo "Не удалось открыть директорию $dir\n";
}

?>