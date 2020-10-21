<?php

namespace AJUR\Toolkit\YandexGeoCoder;

interface ResponseInterface
{
    /**
     * Response constructor
     *
     * @param array $data
     */
    public function __construct(array $data);
    
    /**
     * Возвращает исходные данные
     *
     * @return array
     */
    public function getData();
    
    /**
     * Возвращает список объектов из ответа геокодера
     *
     * @return GeoObject[]
     */
    public function getList();
    
    /**
     * Возвращает первый объект из ответа геокодера
     *
     * @return null|GeoObject
     */
    public function getFirst();
    
    /**
     * Возвращает исходный запрос
     *
     * @return string|null
     */
    public function getQuery();
    
    /**
     * Возвращает количество найденных результатов
     *
     * @return int
     */
    public function getFoundCount();
    
    /**
     * Широта в градусах. Имеет десятичное представление с точностью до семи знаков после запятой
     *
     * @return float|null
     */
    public function getLatitude();
    
    /**
     * Долгота в градусах. Имеет десятичное представление с точностью до семи знаков после запятой
     *
     * @return float|null
     */
    public function getLongitude();
    
}

# -eof-
