<?php

/**
 * @author Revan
 */
class GraphKey implements iHBase_Key {
    
    /**
     *
     * @var iHBase_Key
     */
    protected $_keyValue;
    
    protected static $delimit        = "\036";
    protected static $delimitNumber  = 30;       // ord( $delimit )
    
    public function __construct( $keyString, $isAuthoratative = false ){
        if( $isAuthoratative ){
            $this->_keyValue = HBase_Key_Builder::build( $keyString );
        } else {
            $this->_keyValue = HBase_Key_Builder::build( static::keyNormalize($keyString) );
        }
        
    }
    /**
     * @return String
     */
    public function getRowKey() {
        return $this->_keyValue->getRowKey();
    }
    
    public static function keyNormalize( $keyString ){
        return strtolower( $keyString );
    }
    /**
     * Implode Parts using the delimeter
     * @param array $rowKeyParts An array of strings which will be concated together
     * @return GraphKey
     */
    public static function assemble( array $rowKeyParts ){
        $key = implode( static::$delimit, $rowKeyParts );
        return new GraphKey( $key );
    }
    
    public function getLabel(){
        $splits = $this->split();
        if( sizeof( $splits) !== 3 && isset( $splits[1] ) ){
            throw new GRAPH_EXCEPTION('No Label for this Key Instance');
        } else {
            return $splits[1];
        }
    }
    
    public function split(){
        return explode( static::$delimit, $this->getRowKey() );
    }
    /**
     * Get an inverted UNIX timestamp. As real-time increases, the return value decreases
     * Use for sorting from most recent to less recent
     * @return String Reversed Unix Timestamp 
     */
    public static function invertTime( $unixTime ){
        if( ! is_numeric( $unixTime ) || strlen( $unixTime ) !== 10 ){
            throw new GRAPH_COMPLIANCE_EXCEPTION( __CLASS__ . ' | ' . __FUNCTION__ .' | UNIX time input required. Number with 10 digits since epoch' );
        }
        $entry = 9999999999;
		return $entry - $unixTime;
    }
    public static function stringHash( $string ){
        return sha1( $string );
    }
    
    public function __toString() {
        return $this->getRowKey();
    }
}

?>
