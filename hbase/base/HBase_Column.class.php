<?php

/**
 * @author Revan
 */
class HBase_Column {
    
    protected $colFamily;
    
    public function __construct( array $cols = NULL ){
        
        if( ! is_null( $cols ) ){
            
            foreach( $cols as $col => $Tcell ){
                $colInfo = static::stripFamilyAndCol( $col );
                if( ! isset( $this->colFamily[ $colInfo[0] ] ) ){
                    $this->colFamily[ $colInfo[0] ] = new HbaseColFam();
                }
                $this->colFamily[ $colInfo[0] ]->add( $colInfo[1], $Tcell );
            }
            
        }
        
    }
    public function __get( $name ){
        if( ! isset( $this->colFamily ) ){
            throw new Kore_Domain_Exception(__CLASS__ . ' | ' . __FUNCTION__ .' | ColFamily data not present.' );
        }
        if( array_key_exists( $name, $this->colFamily ) ){
            return $this->colFamily[ $name ];
        } else {
            return new HbaseColFam();
        }
    }
    
    /**
     *
     * @return array \ColFam
     */
    public function getFamilies(){
        return $this->colFamily;
    }
    
    public function isPopulated(){
        if( isset( $this->colFamily ) ){
            return true;
        } else {
            return false;
        }
    }
    
    protected static function stripFamilyAndCol( $fullName ){
        $colonPosition = strpos( $fullName, ":" );
		if( $colonPosition ){
			return array( substr( $fullName, 0, $colonPosition ),
                          substr( $fullName, $colonPosition+1 ) );
		} else {
			throw new Kore_Hbase_Exception( __CLASS__ . ' |' . __FUNCTION__ . '| Failed to find a Family/Column Pair' );
		}
    }
}

class HbaseColFam {
    
    protected $familyData;
    
    public function __construct(){
        $this->familyData = array();
    }
    public function add( $colName, TCell $tcell ){
        $this->familyData[ $colName ] = new HBaseCellValue( $tcell );
    }
    public function __get( $name ){
        if( array_key_exists( $name, $this->familyData ) ){
            return $this->familyData[ $name ];
        } else {
            return new HBaseCellValue( new TCell() );
        }
    }
    
    public function getValues(){
        return $this->familyData;
    }
    
    public function cellValueAsStringArray(){
        
        $out = array();
        foreach( $this->familyData as $key => $cell ){
            $out[$key] = $cell->asString();
        }
        return $out;
        
    }
    
    
}

class HBaseCellValue {
    
    public $timestamp;
    public $value;
    
    public function __construct( TCell $cell ){
        $this->timestamp = $cell->timestamp;
        $this->value = $cell->value;
    }
    
    public function asInt(){
        $value = $this->value;
        return is_numeric( $value ) ? (int)$value : 0;
    }
    public function asString(){
        return $this->value;
    }
    /**
     *
     * @return INT, 0 is returned for all other queries (null etc)
     */
    public function asCounter(){
        $value = static::btyeArrayToInt( $this->value );
        return is_null( $value ) ? 0 : $value;
    }
    public function tsAsUnix(){
        return $this->timestamp / 1000;
    }
    
    public function __toString() {
        return $this->asString();
    }
    
    /**
	 * @brief Convert Counter Columns to (LONG)INT values
	 * 
	 * Hbase stores incremented values as 8x Hex values with 0 indexing. This function
	 * converts those 8 Hex values into a single LONG value.
	 * 
	 * Use to convert increment columns to INT
	 * @todo Improve to pass unit tests
	 * 
	 */
	public static function btyeArrayToInt( $byteArray ){
        
        if( !is_string( $byteArray ) ){
            return null;
        }
        
		$temp = unpack( 'N*', $byteArray );
		return isset( $temp[2] ) ? $temp[2] : NULL;
	}
	
}

?>
