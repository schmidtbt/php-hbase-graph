<?php

/**
 * @author Revan
 */
class HBase_Update extends HBase_Crud {
    
    private $changeRows;
    /**
     * Add a new entry in colfamily-column-value coordinates
     * @param type $colFamily
     * @param type $col
     * @param type $value
     * @param type $delete
     * @throws Kore_Storage_Exception 
     */
    public function add( $colFamily, $col, $value = NULL, $delete = false ){
        
        if( !is_null( $value ) ){
            $this->changeRows[] = array( 'colfamily' => $colFamily, 'col' => $col, 'value' 	=> $value );
        } else {
            
            if( is_null($value) && $delete == false ){
                $value = 'null';
                //throw new Kore_Storage_Exception( __CLASS__ . ' | ' . __FUNCTION__ .' | Must provide a value or set for delete. This is caused when you call changeRow mod with two params' );
            }
            
            $delete = $delete ? 'true' : 'false';
            $this->changeRows[] = array( 'colfamily' => $colFamily, 'col' => $col, 'isDelete' 	=> $delete );
        }
    }
    /**
     * Insert the new values at the key
     * 
     * @note Unless using "HBase_Create" this will over-write existing entries
     * 
     * @param iHBase_Key $key
     * @return type
     * @throws UNINITIALIZED 
     */
    public function update(iHBase_Key $key ){
        
		$mutations = array();
		
        if( empty( $this->changeRows ) ){
            throw new UNINITIALIZED('ChangeRows has not been set');
        }
        
		foreach( $this->changeRows as $mutrow ){
            
            if( isset( $mutrow['value'] ) ){
                $input = array(
                    'column' => $mutrow['colfamily'] . ':' . $mutrow['col'],
                    'value' => $mutrow['value']
                );
            } elseif( isset( $mutrow['isDelete'] ) ){
                $input = array(
                    'column' => $mutrow['colfamily'] . ':' . $mutrow['col'],
                    'isDelete' => 'true'
                );
            }
            
			$mut = new Mutation( $input );
			$mutations[] = $mut;
		}
		return $this->client->thrift()->mutateRow( $this->table->getTableName(), $key->getRowKey() , $mutations );
    }
    /**
     * Only attempt to update an existing row
     * @param iHBase_Key $key
     * @throws RECORD_DOES_NOT_EXIST 
     */
    public function updateExisting( iHBase_Key $key ){
        $reader = new HBase_Read( $this->table );
        if( $reader->exists( $key ) ){
            $this->update( $key );
        } else {
            throw new RECORD_DOES_NOT_EXIST('Trying to update existing row key which is not present');
        }
        
    }
    
    /**
     * Increment a colfamily-column-value coordinates using atomic increment
     * @param iHBase_Key $key
     * @param string $colFam
     * @param string $col
     * @param INT $step 
     */
    public function increment( iHBase_Key $key, $colFam, $col, $step = 1 ){
        return $this->client->thrift()->atomicIncrement( $this->table->getTableName(), $key->getRowKey(), $colFam .':'.$col, $step);
    }
    
}

?>
