<?php

/**
 * @author Revan
 */
class HBase_Crud {
    
    /**
     *
     * @var \iHBase_Table
     */
    protected $table;
    /**
     *
     * @var \HBase_Client
     */
    protected $client;
    
    public function __construct( iHBase_Table $table ){
        $this->table = $table;
        $this->client = new HBase_Client( $this->table );
    }
    
    public function getTable(){
        return $this->table;
    }
    public function getClient(){
        return $this->client;
    }
    
    
}

?>
