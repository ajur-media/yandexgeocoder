<?php

namespace AJUR\Toolkit\YandexGeoCoder;

use AJUR\Toolkit\YandexGeoCoder\Exception\CurlError;
use AJUR\Toolkit\YandexGeoCoder\Exception\MapsError;
use AJUR\Toolkit\YandexGeoCoder\Exception\ServerError;

interface ApiInterface
{
    /**
     * Api constructor
     * @param null $version
     */
    public function __construct($version = null);
    
    /**
     * Запрашивает у геокодера данные
     *
     * @param array $options Curl options
     * @return $this
     * @throws Exception
     * @throws CurlError
     * @throws ServerError
     * @throws MapsError
     */
    public function load(array $options = []);
    
    /**
     * Возвращает "сырой" ответ геокодера
     *
     * @return Response
     */
    public function getResponse();
    
    /**
     * Очистка фильтров гео-кодирования
     *
     * @return self
     */
    public function clear();
    
    /**
     * Гео-кодирование по координатам (обратное)
     *
     * @see http://api.yandex.ru/maps/doc/geocoder/desc/concepts/input_params.xml#geocode-format
     * @param float $longitude Долгота в градусах
     * @param float $latitude Широта в градусах
     * @return self
     */
    public function setPoint($longitude, $latitude);
    
    /**
     * Географическая область для поиска объекта
     *
     * @param float $lengthLng Разница между максимальной и минимальной долготой в градусах
     * @param float $lengthLat Разница между максимальной и минимальной широтой в градусах
     * @param null|float $longitude Долгота в градусах
     * @param null|float $latitude Широта в градусах
     * @return self
     */
    public function setArea($lengthLng, $lengthLat, $longitude = null, $latitude = null);
    
    /**
     * Позволяет ограничить поиск объектов областью, заданной self::setArea()
     *
     * @param boolean $areaLimit
     * @return self
     */
    public function useAreaLimit($areaLimit);
    
    /**
     * Гео-кодирование по запросу (адрес/координаты)
     *
     * @param string $query
     * @return self
     */
    public function setQuery($query);
    
    /**
     * Вид топонима (только для обратного геокодирования)
     *
     * @param string $kind
     * @return self
     */
    public function setKind($kind);
    
    /**
     * Максимальное количество возвращаемых объектов (по-умолчанию 10)
     * @param int $limit
     * @return self
     */
    public function setLimit($limit = 10);
    
    /**
     * Количество объектов в ответе (начиная с первого), которое необходимо пропустить
     *
     * @param int $offset
     * @return self
     */
    public function setOffset($offset);
    
    /**
     * Предпочитаемый язык описания объектов
     *
     * @param string $lang - Api::{LANG_RU, LANG_UA, LANG_BY, LANG_US, LANG_BR, LANG_TR}
     * @return self
     */
    public function setLang($lang);
    
    /**
     * Задаем Ключ (токен) API Яндекс.Карт
     *
     * @see https://tech.yandex.ru/maps/doc/geocoder/desc/concepts/input_params-docpage
     * @param string $token
     * @return self
     */
    public function setToken($token);
    
}

# -eof-
