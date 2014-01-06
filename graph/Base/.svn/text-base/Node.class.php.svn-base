<?php

/**
 * @author Revan
 */
abstract class Node extends GraphEntity {
    
    const NODE = 'node';
    
    public static function generate( HBase_Record $record ){
        $rowkey = $record->getKey()->getRowKey();
        $class = get_called_class();
        
        return new $class( new GraphKey( $record->getKey()->getRowKey() ) );
    }
    
    public static function create(){
        
        try {
            
            return call_user_func_array( array(get_called_class(), 'doCreate'), func_get_args() );
            
        } catch( GRAPH_COMPLIANCE_EXCEPTION $e ){
            throw $e;
        } catch( RECORD_EXISTS $e ){
            throw new GRAPH_ENTITY_EXISTS('Cannot Overwrite '. get_called_class() .' Record',0,$e);
        } catch( KoreException $e ){
            throw new GRAPH_STORAGE_EXCEPTION('Create Failure: '.$e->getMessage(),0,$e);
        }
        
    }
    
    protected static function doCreate(){ throw new GRAPH_EXCEPTION('doCreate has not been implemented'); }
    
}

?>
