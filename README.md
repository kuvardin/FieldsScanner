# PHP-library for scan the structure of foreign data

## Installation
```shell
composer require kuvardin/fields-scanner
```

## Usage example
```php
<?php

require 'vendor/autoload.php';

// Создаем экземпляр сканера полей
$scanner = new Kuvardin\FieldsScanner\FieldsScanner;

// Перечисляем запросы для поиска в API Google Books
$queries = ['Саган', 'Фейнман', 'Докинз', 'Невзоров'];

// Перебираем каждый поисковый запрос
foreach ($queries as $query) {

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
```
### Result example
```text
Types: assoc_array (4)
Type assoc_array:
	[kind]
		Types: string (4)
		Type string:
			Length: 13-13 (cannot be empty)
			Examples:
				books#volumes - 4
	[totalItems]
		Types: int (4)
		Type int:
			Values: 224 - 486
			Examples:
				486 - 1
				280 - 1
				224 - 1
				437 - 1
	[items]
		Types: dissoc_array (4)
		Type dissoc_array:
			Length: 10-10 (cannot be empty)
			Child
				Types: assoc_array (40)
				Type assoc_array:
					[kind]
						Types: string (40)
						Type string:
							Length: 12-12 (cannot be empty)
							Examples:
								books#volume - 10
					[id]
						Types: string (40)
						Type string:
							Length: 12-12 (cannot be empty)
							Examples:
								VbOgzQEACAAJ - 1
								YwlNAQAAMAAJ - 1
								HA45AQAAMAAJ - 1
								AdcVAQAAMAAJ - 1
								SUc5AQAAMAAJ - 1
								howOAQAAIAAJ - 1
								5zttDwAAQBAJ - 1
								vUojAQAAIAAJ - 1
								vWlTDAAAQBAJ - 1
								e6ALEAAAQBAJ - 1
					[etag]
						Types: string (40)
						Type string:
							Length: 11-11 (cannot be empty)
							Examples:
								D92BnLUrkhA - 1
								9YmMjHXdpw8 - 1
								TlGscp3fCiw - 1
								BtudGXodosc - 1
								Yg/GYDQVBQc - 1
								ereqdXdkC60 - 1
								vu5DqlDsAeM - 1
								oYHC786DxtU - 1
								/tEKRKdLJIk - 1
								JMsfSfZ8SxM - 1
					[selfLink]
						Types: string (40)
						Type string:
							Length: 56-56 (cannot be empty)
							Examples:
								https://www.googleapis.com/books/v1/volumes/VbOgzQEACAAJ - 1
								https://www.googleapis.com/books/v1/volumes/YwlNAQAAMAAJ - 1
								https://www.googleapis.com/books/v1/volumes/HA45AQAAMAAJ - 1
								https://www.googleapis.com/books/v1/volumes/AdcVAQAAMAAJ - 1
								https://www.googleapis.com/books/v1/volumes/SUc5AQAAMAAJ - 1
								https://www.googleapis.com/books/v1/volumes/howOAQAAIAAJ - 1
								https://www.googleapis.com/books/v1/volumes/5zttDwAAQBAJ - 1
								https://www.googleapis.com/books/v1/volumes/vUojAQAAIAAJ - 1
								https://www.googleapis.com/books/v1/volumes/vWlTDAAAQBAJ - 1
								https://www.googleapis.com/books/v1/volumes/e6ALEAAAQBAJ - 1
					[volumeInfo]
						Types: assoc_array (40)
						Type assoc_array:
							[title]
								Types: string (40)
								Type string:
									Length: 4-145 (cannot be empty)
									Examples:
										Через месяц, через год - 1
										Shamanstvo u buri͡at Irkustskoĭ guberniĭ - 1
										Novye materīaly o shamanstvi︠e︡ i buri︠a︡t - 1
										Sobranie sochineniĭ - 1
										Ėtnograficheskoe obozri︠e︡nĭe - 1
										Образцы народной литературы тюркских племен, живущих в Южной Сибири и Дзунгарской степи: Нарѣчія барабинцев, тарских, тоболских и тюменских татар - 1
										Эволюция мозга. Драконы Эдема. - 1
										Собрание сочинений - 1
										Голубая точка. Космическое будущее человечества - 1
										Четыре стороны сердца - 1
							[authors]
								Types: dissoc_array (34), not_exists (6)
								Type dissoc_array:
									Length: 1-4 (cannot be empty)
									Child
										Types: string (49)
										Type string:
											Length: 8-28 (cannot be empty)
											Examples:
												Франсуаза Саган - 2
												N. N. Agapitov - 1
												Matveĭ Nikolaevich Khangalov - 2
												Василий Васильевич Радлов - 1
												Саган К. - 1
												Матвей Николаевич Хангалов - 1
												Карл Саган - 1
												Ричард Фейнман - 1
							[publishedDate]
								Types: string (39), not_exists (1)
								Type string:
									Length: 4-10 (cannot be empty)
									Examples:
										2019 - 1
										1883 - 1
										1890 - 1
										1959 - 1
										1894 - 1
										1870 - 1
										2004 - 1
										2015-01-01 - 1
										2020-11-27 - 1
										2017-10-11 - 1
							[industryIdentifiers]
								Types: dissoc_array (39), not_exists (1)
								Type dissoc_array:
									Length: 1-2 (cannot be empty)
									Child
										Types: assoc_array (62)
										Type assoc_array:
											[type]
												Types: string (62)
												Type string:
													Length: 5-7 (cannot be empty)
													Examples:
														ISBN_10 - 2
														ISBN_13 - 2
														OTHER - 6
											[identifier]
												Types: string (62)
												Type string:
													Length: 10-23 (cannot be empty)
													Examples:
														5389171896 - 1
														9785389171893 - 1
														CHI:56991625 - 1
														UCAL:C2981109 - 1
														IND:30000001735392 - 1
														MINN:31951000731494Q - 1
														UCAL:$B490631 - 1
														9785521008322 - 1
														5521008322 - 1
														STANFORD:36105120973537 - 1
							[readingModes]
								Types: assoc_array (40)
								Type assoc_array:
									[text]
										Types: bool (1)
										Type bool:
											Examples:
												false - 7
												true - 3
									[image]
										Types: bool (1)
										Type bool:
											Examples:
												false - 3
												true - 7
							[pageCount]
								Types: int (12), not_exists (28)
								Type int:
									Values: 31 - 16199
									Examples:
										155 - 1
										169 - 1
										144 - 1
										208 - 1
										512 - 1
										16199 - 1
										827 - 1
										31 - 1
										170 - 1
										284 - 1
							[printType]
								Types: string (40)
								Type string:
									Length: 4-4 (cannot be empty)
									Examples:
										BOOK - 10
							[categories]
								Types: dissoc_array (38), not_exists (2)
								Type dissoc_array:
									Length: 1-1 (cannot be empty)
									Child
										Types: string (38)
										Type string:
											Length: 6-31 (cannot be empty)
											Examples:
												Interpersonal relations - 1
												Shamanism - 1
												Buriats - 2
												Ethnology - 1
												Altaic languages - 1
												Fiction - 2
												Social Science - 1
												Education - 1
												<...>
```
