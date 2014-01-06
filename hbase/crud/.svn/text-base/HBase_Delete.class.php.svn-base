<?php

/**
 * @author Revan
 */
class HBase_Delete extends HBase_Crud {
    
    public function removeColumn( iHBase_Key $key, $colFam, $col ){
        $result = $this->client->thrift()->deleteAll( $this->table->getTableName(), $key->getRowKey(), $colFam.':'.$col );
        return $result;
    }
    public function removeRow( iHbase_Key $key ){
        $result = $this->client->thrift()->deleteAllRow( $this->table->getTableName(), $key->getRowKey() );
        return $result;
    }
    
}

?>
