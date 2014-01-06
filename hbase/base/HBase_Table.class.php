<?php

/**
 * @author Revan
 */
class HBase_Table implements iHBase_Table {
    
    protected $tableName;
    public function __construct( $tableName ){
        $this->tableName = $tableName;
    }
    public function getTableName(){
        return $this->tableName;
    }
    
}

?>
