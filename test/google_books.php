<?php

require 'vendor/autoload.php';

// Создаем экземпляр сканера полей
$scanner = new Kuvardin\FieldsScanner\FieldsScanner;

// Перечисляем запросы для поиска в API Google Books
$queries = ['Саган', 'Фейнман', 'Докинз', 'Невзоров'];

// Перебираем каждый поисковый запрос
foreach ($queries as $query) {
    echo "Searching: $query\n";

    // URL HTTP-запроса
    $url = 'https://www.googleapis.com/books/v1/volumes?' . http_build_query(['q' => $query]);

    // Отправка запроса и получение результата
    $response = file_get_contents($url);
    if ($response === false) {
        continue;
    }

    // Декодирование JSON-строки
    $response_json = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

    // Сканирование полученных данных
    $scanner->scan($response_json);
}

// Отображение результата
echo $scanner->result->getInfo();
