<?php

namespace AJUR\Toolkit\YandexGeoCoder;

use AJUR\Toolkit\YandexGeoCoder\Exception\CurlError;
use AJUR\Toolkit\YandexGeoCoder\Exception\MapsError;
use AJUR\Toolkit\YandexGeoCoder\Exception\ServerError;

/**
 * Class Api
 * @package Yandex\Geo
 * @license The MIT License (MIT)
 * @see http://api.yandex.ru/maps/doc/geocoder/desc/concepts/About.xml
 */
class Api implements ApiInterface
{
    /** дом */
    const KIND_HOUSE = 'house';
    
    /** улица */
    const KIND_STREET = 'street';
    
    /** станция метро */
    const KIND_METRO = 'metro';
    
    /** район города */
    const KIND_DISTRICT = 'district';
    
    /** населенный пункт (город/поселок/деревня/село/...) */
    const KIND_LOCALITY = 'locality';
    
    /** русский (по умолчанию) */
    const LANG_RU = 'ru-RU';
    
    /** украинский */
    const LANG_UA = 'uk-UA';
    
    /** белорусский */
    const LANG_BY = 'be-BY';
    
    /** американский английский */
    const LANG_US = 'en-US';
    
    /** британский английский */
    const LANG_BR = 'en-BR';
    
    /** турецкий (только для карты Турции) */
    const LANG_TR = 'tr-TR';
    
    /**
     * @var string Версия используемого api
     */
    protected $_version = '1.x';
    
    /**
     * @var array
     */
    protected $_filters = [];
    
    /**
     * @var Response|null
     */
    protected $_response;
    
    public function __construct($version = null)
    {
        if (!is_null( $version )) {
            $this->_version = (string)$version;
        }
        $this->clear();
    }
    
    public function load(array $options = [])
    {
        $apiUrl = sprintf( 'https://geocode-maps.yandex.ru/%s/?%s', $this->_version, http_build_query( $this->_filters ) );
        
        $curl = curl_init( $apiUrl );
        
        $options += array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPGET => 1,
            CURLOPT_FOLLOWLOCATION => 1,
        );
        curl_setopt_array( $curl, $options );
        
        $data = curl_exec( $curl );
        $code = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
        
        if (curl_errno( $curl )) {
            $error = curl_error( $curl );
            curl_close( $curl );
            throw new CurlError( $error );
        }
        curl_close( $curl );
        
        if (in_array( $code, array( 500, 502 ) )) {
            $msg = strip_tags( $data );
            throw new ServerError( trim( $msg ), $code );
        }
        
        $data = json_decode( $data, true );
        
        if (empty( $data )) {
            throw new Exception( "Can't load data by url: {$apiUrl}" );
        }
        
        if (!empty( $data[ 'error' ] )) {
            if (!empty( $data[ 'statusCode' ] ) and !empty( $data[ 'error' ] )) {
                throw new MapsError( $data[ 'error' ] . ' : ' . $data['message'], $data[ 'statusCode' ] );
            } else {
                throw new MapsError( $data[ 'error' ][ 'message' ], $data[ 'error' ][ 'code' ] );
            }
        }
        
        $this->_response = new Response( $data );
        
        return $this;
    }
    
    public function getResponse()
    {
        return $this->_response;
    }
    
    public function clear()
    {
        $this->_filters = array(
            'format' => 'json',
        );
        // указываем явно значения по-умолчанию
        $this
            ->setLang( self::LANG_RU )
            ->setOffset( 0 )
            ->setLimit( 10 );
//            ->useAreaLimit(false);
        $this->_response = null;
        return $this;
    }
    
    public function setPoint($longitude, $latitude)
    {
        $longitude = (float)$longitude;
        $latitude = (float)$latitude;
        $this->_filters[ 'geocode' ] = sprintf( '%F,%F', $longitude, $latitude );
        return $this;
    }
    
    public function setArea($lengthLng, $lengthLat, $longitude = null, $latitude = null)
    {
        $lengthLng = (float)$lengthLng;
        $lengthLat = (float)$lengthLat;
        $this->_filters[ 'spn' ] = sprintf( '%f,%f', $lengthLng, $lengthLat );
        if (!empty( $longitude ) && !empty( $latitude )) {
            $longitude = (float)$longitude;
            $latitude = (float)$latitude;
            $this->_filters[ 'll' ] = sprintf( '%f,%f', $longitude, $latitude );
        }
        return $this;
    }
    
    public function useAreaLimit($areaLimit)
    {
        $this->_filters[ 'rspn' ] = $areaLimit ? 1 : 0;
        return $this;
    }
    
    public function setQuery($query)
    {
        $this->_filters[ 'geocode' ] = (string)$query;
        return $this;
    }
    
    public function setKind($kind)
    {
        $this->_filters[ 'kind' ] = (string)$kind;
        return $this;
    }
    
    public function setLimit($limit = 10)
    {
        $this->_filters[ 'results' ] = (int)$limit;
        return $this;
    }
    
    public function setOffset($offset)
    {
        $this->_filters[ 'skip' ] = (int)$offset;
        return $this;
    }
    
    public function setLang($lang)
    {
        $this->_filters[ 'lang' ] = (string)$lang;
        return $this;
    }
    
    public function setToken($token)
    {
        $this->_filters[ 'apikey' ] = (string)$token;
        return $this;
    }
}

# -eof-
