<?php

namespace AJUR\Toolkit\YandexGeoCoder;

/**
 * Class Response
 * @package AJUR\Toolkit\YandexGeoCoder
 * @license The MIT License (MIT)
 */
class Response implements ResponseInterface
{
    /**
     * @var GeoObject[]
     */
    protected $_list = [];
    /**
     * @var array
     */
    protected $_data;
    
    public function __construct(array $data)
    {
        $this->_data = $data;
        if (isset( $data[ 'response' ][ 'GeoObjectCollection' ][ 'featureMember' ] )) {
            foreach ($data[ 'response' ][ 'GeoObjectCollection' ][ 'featureMember' ] as $entry) {
                $this->_list[] = new GeoObject( $entry[ 'GeoObject' ] );
            }
        }
    }
    
    /**
     * Возвращает исходные данные
     *
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }
    
    public function getList()
    {
        return $this->_list;
    }
    
    public function getFirst()
    {
        $result = null;
        if (count( $this->_list )) {
            $result = $this->_list[ 0 ];
        }
        
        return $result;
    }
    
    public function getQuery()
    {
        $result = null;
        if (isset( $this->_data[ 'response' ][ 'GeoObjectCollection' ][ 'metaDataProperty' ][ 'GeocoderResponseMetaData' ][ 'request' ] )) {
            $result = $this->_data[ 'response' ][ 'GeoObjectCollection' ][ 'metaDataProperty' ][ 'GeocoderResponseMetaData' ][ 'request' ];
        }
        return $result;
    }
    
    public function getFoundCount()
    {
        $result = null;
        if (isset( $this->_data[ 'response' ][ 'GeoObjectCollection' ][ 'metaDataProperty' ][ 'GeocoderResponseMetaData' ][ 'found' ] )) {
            $result = (int)$this->_data[ 'response' ][ 'GeoObjectCollection' ][ 'metaDataProperty' ][ 'GeocoderResponseMetaData' ][ 'found' ];
        }
        return $result;
    }
    
    public function getLatitude()
    {
        $result = null;
        
        if (isset( $this->_data[ 'response' ][ 'GeoObjectCollection' ][ 'metaDataProperty' ][ 'GeocoderResponseMetaData' ][ 'Point' ][ 'pos' ] )) {
            list( , $latitude ) = explode( ' ', $this->_data[ 'response' ][ 'GeoObjectCollection' ][ 'metaDataProperty' ][ 'GeocoderResponseMetaData' ][ 'Point' ][ 'pos' ] );
            $result = (float)$latitude;
        }
        return $result;
    }
    
    public function getLongitude()
    {
        $result = null;
        if (isset( $this->_data[ 'response' ][ 'GeoObjectCollection' ][ 'metaDataProperty' ][ 'GeocoderResponseMetaData' ][ 'Point' ][ 'pos' ] )) {
            list( $longitude, ) = explode( ' ', $this->_data[ 'response' ][ 'GeoObjectCollection' ][ 'metaDataProperty' ][ 'GeocoderResponseMetaData' ][ 'Point' ][ 'pos' ] );
            $result = (float)$longitude;
        }
        return $result;
    }
}

# -eof-
