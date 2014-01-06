<?php

/**
 * @author Revan
 */
class HBase_Create extends HBase_Crud {
    
    /**
     * Creates a unique Row, and returns an Updater to use ->add()
     * @param iHBase_Key $key
     * @return \HBase_Update
     * @throws RECORD_EXISTS 
     */
    public function createRow(iHBase_Key $key ){
        
        $reader = new HBase_Read( $this->table );
        $result = $reader->read( $key );
        
        if(!is_null($result)){
            throw new RECORD_EXISTS('Cannot create a new record. Record already exists');
        }
        
        return new HBase_Update( $this->table );
    }
    
    /**
     * Create a unique Entry
     * @param iHBase_Key $key
     * @param type $colFam
     * @param type $col
     * @param type $value
     * @throws RECORD_DOES_NOT_EXIST
     * @throws ENTRY_EXISTS
     */
    public function createEntry(iHBase_Key $key, $colFam, $col, $value ){
        
        $reader = new HBase_Read( $this->table );
        $result = $reader->read( $key );
        
        if(is_null($result)){
            throw new RECORD_DOES_NOT_EXIST('Key does not exist');
        }
        
        if( !is_null($result->getColumn()->$colFam->$col->value ) ){
            throw new ENTRY_EXISTS('Entry already exists');
        }
        
        $updater = new HBase_Update( $this->table );
        $updater->add( $colFam, $col, $value );
        $updater->update($key);
    }
    
}

?>
