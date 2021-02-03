<?php

require 'vendor/autoload.php';

// Создаем экземпляр сканера полей
$scanner = new Kuvardin\FieldsScanner\FieldsScanner;

// Количество итераций
$limit = (int)($argv[1] ?? 10);

for ($i = 0; $i < $limit; $i++) {

    // Генерируем случайный IP-адрес
    $ip = random_int(1, 254) . '.' . random_int(1, 254) . '.' . random_int(1, 254) . '.' . random_int(1, 254);
    echo $ip, PHP_EOL;

    // URL HTTP-запроса
    $url = "http://ip-api.com/json/$ip";

    // Отправка запроса и получение результата
    $response = file_get_contents($url);
    if ($response === false) {
        continue;
    }

    // Декодирование JSON-строки
    $response_json = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

    // Сканирование полученных данных
    $scanner->scan($response_json);

    sleep(1);
}

// Отображение результата
echo $scanner->result->getInfo();
