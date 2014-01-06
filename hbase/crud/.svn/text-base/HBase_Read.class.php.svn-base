<?php

/**
 * @author Revan
 */
class HBase_Read extends HBase_Crud {
    
    
    /**
     * Read a single key value from the data-store
     * @param iHBase_Table $table
     * @param iHBase_Key $key
     * @return \HBase_Record or NULL
     */
    public function read( iHBase_Key $key ){
        $result = $this->client->thrift()->getRow( $this->table->getTableName(),  $key->getRowKey() );
        if( empty( $result ) ){
            return null;
        }
        $record = new HBase_Record( $this->table, $key, new HBase_Column( $result[0]->columns ) );
        return $record;
    }
    
    public function scanPrefix( iHBase_Key $key, $numRows = 10, array $colFams = null ){
        
        if( is_null( $colFams ) ){
            $colFams = array();
        }
        
        $scanner = $this->client->thrift()->scannerOpenWithPrefix( $this->table->getTableName(),  $key->getRowKey(), $colFams );
        $values = $this->client->thrift()->scannerGetList($scanner, $numRows );
        $this->client->thrift()->scannerClose($scanner);
        
        if( is_array( $values ) ){
            $records = array();
            foreach( $values as $v ){
                $records[] = new HBase_Record( $this->table, new HBase_Key( $v->row ), new HBase_Column( $v->columns ) );
            }
        }
        
        return $records;
        
    }
    
    /**
     * Test whether a Key exists
     * 
     * @param iHBase_Key $key
     * @return type 
     */
    public function exists( iHBase_Key $key ){
        $output = $this->read($key);
        return is_null( $output ) ? false : true;
    }
}

?>
