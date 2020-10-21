<?php

namespace AJUR\Toolkit\YandexGeoCoder;

/**
 * Class GeoObject
 *
 * @package AJUR\Toolkit\YandexGeoCoder
 * @license The MIT License (MIT)
 */
class GeoObject implements GeoObjectInterface
{
    protected $_addressHierarchy = [
        'Country'               => ['AdministrativeArea'],
        'AdministrativeArea'    => [ 'SubAdministrativeArea', 'Locality' ],
        'SubAdministrativeArea' => [ 'Locality' ],
        'Locality'              => [ 'DependentLocality', 'Thoroughfare' ],
        'DependentLocality'     => [ 'DependentLocality', 'Thoroughfare' ],
        'Thoroughfare'          => [ 'Premise' ],
        'Premise'               => [],
    ];
    
    protected $_data;
    protected $_rawData;
    
    public function __construct(array $rawData)
    {
        $data = [
            'Address' => $rawData[ 'metaDataProperty' ][ 'GeocoderMetaData' ][ 'text' ],
            'Kind' => $rawData[ 'metaDataProperty' ][ 'GeocoderMetaData' ][ 'kind' ],
        ];
        
        array_walk_recursive(
            $rawData,
            function($value, $key) use (&$data) {
                if (in_array(
                    $key,
                    [
                        'CountryName',
                        'CountryNameCode',
                        'AdministrativeAreaName',
                        'SubAdministrativeAreaName',
                        'LocalityName',
                        'DependentLocalityName',
                        'ThoroughfareName',
                        'PremiseNumber',
                    ]
                )) {
                    $data[ $key ] = $value;
                }
            }
        );
        if (isset( $rawData[ 'Point' ][ 'pos' ] )) {
            $pos = explode( ' ', $rawData[ 'Point' ][ 'pos' ] );
            $data[ 'Longitude' ] = (float)$pos[ 0 ];
            $data[ 'Latitude' ] = (float)$pos[ 1 ];
        }
        $this->_data = $data;
        $this->_rawData = $rawData;
    }
    
    public function __sleep()
    {
        return [ '_data' ];
    }
    
    public function getRawData()
    {
        return $this->_rawData;
    }
    
    public function getData()
    {
        return $this->_data;
    }
    
    public function getLatitude()
    {
        return isset( $this->_data[ 'Latitude' ] ) ? $this->_data[ 'Latitude' ] : null;
    }
    
    public function getLongitude()
    {
        return isset( $this->_data[ 'Longitude' ] ) ? $this->_data[ 'Longitude' ] : null;
    }
    
    public function getAddress()
    {
        return isset( $this->_data[ 'Address' ] ) ? $this->_data[ 'Address' ] : null;
    }
    
    public function getKind()
    {
        return isset( $this->_data[ 'Kind' ] ) ? $this->_data[ 'Kind' ] : null;
    }
    
    public function getCountry()
    {
        return isset( $this->_data[ 'CountryName' ] ) ? $this->_data[ 'CountryName' ] : null;
    }
    
    public function getCountryCode()
    {
        return isset( $this->_data[ 'CountryNameCode' ] ) ? $this->_data[ 'CountryNameCode' ] : null;
    }
    
    public function getAdministrativeAreaName()
    {
        return isset( $this->_data[ 'AdministrativeAreaName' ] ) ? $this->_data[ 'AdministrativeAreaName' ] : null;
    }
    
    public function getSubAdministrativeAreaName()
    {
        return isset( $this->_data[ 'SubAdministrativeAreaName' ] ) ? $this->_data[ 'SubAdministrativeAreaName' ] : null;
    }
    
    public function getLocalityName()
    {
        return isset( $this->_data[ 'LocalityName' ] ) ? $this->_data[ 'LocalityName' ] : null;
    }
    
    public function getDependentLocalityName()
    {
        return isset( $this->_data[ 'DependentLocalityName' ] ) ? $this->_data[ 'DependentLocalityName' ] : null;
    }
    
    public function getThoroughfareName()
    {
        return isset( $this->_data[ 'ThoroughfareName' ] ) ? $this->_data[ 'ThoroughfareName' ] : null;
    }
    
    public function getPremiseNumber()
    {
        return isset( $this->_data[ 'PremiseNumber' ] ) ? $this->_data[ 'PremiseNumber' ] : null;
    }
    
    public function getFullAddressParts()
    {
        return array_unique(
            $this->_parseLevel(
                $this->_rawData[ 'metaDataProperty' ][ 'GeocoderMetaData' ][ 'AddressDetails' ][ 'Country' ],
                'Country'
            )
        );
    }
    
    /**
     *
     * @param array $level
     * @param String $levelName
     * @param array &$address
     * @return array
     */
    protected function _parseLevel(array $level, $levelName, &$address = [])
    {
        if (!isset( $this->_addressHierarchy[ $levelName ] )) {
            return $address;
        }
        
        $nameProp = $levelName === 'Premise' ? 'PremiseNumber' : $levelName.'Name';
        if (isset( $level[ $nameProp ] )) {
            $address[] = $level[ $nameProp ];
        }
        
        foreach ($this->_addressHierarchy[ $levelName ] as $child) {
            if (!isset( $level[ $child ] )) {
                continue;
            }
            $this->_parseLevel( $level[ $child ], $child, $address );
        }
        
        return $address;
    }
}

# -eof-
