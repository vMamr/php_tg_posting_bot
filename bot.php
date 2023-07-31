<?php
$apiToken = "апи_токен_к_боту_который_админ_в_группе";
$chat_id = "@ссылка_на_группу";
$imgDirectory = '/images/';
$doneDirectory = '/done/';

echo "Начинаем проверку наличия изображений в директории {$imgDirectory}<br>";

$images = glob($imgDirectory . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

if(count($images) > 0) {
    echo "Найдено изображений: ". count($images) ."<br>";

    $randomIndex = array_rand($images);
    $image = $images[$randomIndex];
    echo "Выбранное изображение: {$image}<br>";

    // Заголовок с большой буквы
    $caption = pathinfo($image, PATHINFO_FILENAME);

    // Добавляем приписку
    $caption .= "\n\n" . $chat_id;
    echo "Заголовок: {$caption}<br>";

    $data = [
        'chat_id' => $chat_id,
        'photo' => new CURLFile(realpath($image)),
        'caption' => ucfirst($caption)
    ];

    $ch = curl_init('https://api.telegram.org/bot'.$apiToken.'/sendPhoto');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if(curl_errno($ch)){
        echo 'Ошибка запроса: ' . curl_error($ch) ."<br>";
    } else {
        echo "Ответ от API Telegram: {$response}<br>";
    }

    curl_close($ch);

    $moveResult = rename($image, $doneDirectory . basename($image));

    if($moveResult) {
        echo "Изображение успешно перемещено в {$doneDirectory}<br>";
    } else {
        echo "Не удалось переместить изображение в {$doneDirectory}<br>";
    }
} else {
    echo "В директории {$imgDirectory} не найдено изображений<br>";
}
