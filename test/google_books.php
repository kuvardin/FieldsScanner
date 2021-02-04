<?php

require 'vendor/autoload.php';

$scanner = new Kuvardin\FieldsScanner\FieldsScanner;
$queries = ['Саган', 'Фейнман', 'Докинз', 'Невзоров'];

foreach ($queries as $query) {
    echo "Searching: $query\n";
    $url = 'https://www.googleapis.com/books/v1/volumes?' . http_build_query(['q' => $query]);
    $response = file_get_contents($url);
    if ($response === false) {
        continue;
    }

    $response_json = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    $scanner->scan($response_json);
}

echo $scanner->result->getInfo();
