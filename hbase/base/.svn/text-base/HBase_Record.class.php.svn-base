<?php

/**
 * @author Revan
 */
class HBase_Record {
    
    private $table;
    private $key;
    private $column;
    
    public function __construct(iHBase_Table $table, iHBase_Key $key, HBase_Column $column ){
        $this->table = $table;
        $this->key = $key;
        $this->column = $column;
    }
    
    /**
     *
     * @return \iHBase_Key
     */
    public function getKey(){
        return $this->key;
    }
    /**
     *
     * @return \HBase_Column
     */
    public function getColumn(){
        return $this->column;
    }
    /**
     *
     * @return \iHBase_Table
     */
    public function getTable(){
        return $this->table;
    }
    
}

?>
