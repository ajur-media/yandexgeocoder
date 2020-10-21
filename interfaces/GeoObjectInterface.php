<?php


namespace AJUR\Toolkit\YandexGeoCoder;

interface GeoObjectInterface
{
    /**
     * GeoObject constructor
     *
     * @param array $rawData
     */
    public function __construct(array $rawData);
    
    /**
     * Необработанные данные
     *
     * @return array
     */
    public function getRawData();
    
    /**
     * Обработанные данные
     *
     * @return array
     */
    public function getData();
    
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
    
    /**
     * Полный адрес
     *
     * @return string|null
     */
    public function getAddress();
    
    /**
     * Тип
     *
     * @return string|null
     */
    public function getKind();
    
    /**
     * Страна
     *
     * @return string|null
     */
    public function getCountry();
    
    /**
     * Код страны
     *
     * @return string|null
     */
    public function getCountryCode();
    
    /**
     * Административный округ
     *
     * @return string|null
     */
    public function getAdministrativeAreaName();
    
    /**
     * Область/край
     *
     * @return string|null
     */
    public function getSubAdministrativeAreaName();
    
    /**
     * Населенный пункт
     *
     * @return string|null
     */
    public function getLocalityName();
    
    /**
     * ?????
     *
     * @return string|null
     */
    public function getDependentLocalityName();
    
    /**
     * Улица
     *
     * @return string|null
     */
    public function getThoroughfareName();
    
    /**
     * Номер дома
     *
     * @return string|null
     */
    public function getPremiseNumber();
    
    /**
     * Полный адрес
     *
     * @return array
     */
    public function getFullAddressParts();
    
}

# -eof-
