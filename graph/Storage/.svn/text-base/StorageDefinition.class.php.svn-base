<?php

/**
 * 
 * @author Revan
 */
abstract class StorageDefinition {
    
    /**
     * Map between Entities and their HBase representation (which table)
     * @var Array 
     */
    public static $map;
    
    public static function map( $EntityName ){
        
        if( !isset( static::$map ) || empty( static::$map ) ){
            throw new GRAPH_EXCEPTION('Map has not been configured');
        }
        
        if( isset( static::$map[ $EntityName ] ) ){
            return static::$map[ $EntityName ];
        } else {
            throw new GRAPH_EXCEPTION('Map not found between entity and storage: '.$EntityName );
        }
        
    }
    
}

?>
