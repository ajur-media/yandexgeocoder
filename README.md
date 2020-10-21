API для работы с сервисом Яндекс.Геокодирование
===============================================

Служба Яндекс.Карт предлагает своим пользователям сервис геокодирования. Он позволяет определять координаты и получать
сведения о географическом объекте по его названию или адресу и наоборот, определять адрес объекта на карте по его
координатам (обратное геокодирование).

Например, по запросу «Москва, ул. Малая Грузинская, д. 27/13» геокодер возвратит географические координаты этого
дома: «37.571309, 55.767190» (долгота, широта). И, наоборот, если в запросе указать географические координаты
дома «37.571309, 55.767190», то геокодер вернет его адрес.

Пример
------

```php
<?php

use AJUR\Toolkit\YandexGeoCoder\Api;

require_once 'vendor/autoload.php';

$api = new Api();

// Можно искать по точке (обратное геокодирование)
// $api->setPoint(30.5166187, 50.4452705);

// Или можно искать по адресу (прямое геокодирование)
$api->setQuery('Санкт-Петербург, Невский проспект, 35');

// Настройка фильтров
$api
    ->setLimit(1) // кол-во результатов
    ->setLang( Api::LANG_RU) // локаль ответа
    ->setToken('<token>') // api ключ
    ->load();

$response = $api->getResponse();

echo "Found: ", $response->getFoundCount(), PHP_EOL;
echo "Query: ", $response->getQuery(), PHP_EOL;

$object = $response->getFirst();

echo "Lat: ", $object->getLatitude(), PHP_EOL;
echo "Lon: ", $object->getLongitude(), PHP_EOL;

// Список найденных точек
$collection = $response->getList();

foreach ($collection as $n => $item) {
    echo "[{$n}] ";
    
    echo "Address: ", $item->getAddress(), PHP_EOL;
    echo "Lat: ", $item->getLatitude(), PHP_EOL;
    echo "Lon: ", $item->getLongitude(), PHP_EOL;
}

```