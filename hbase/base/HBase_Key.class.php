<?php

/**
 * @author Revan
 */
class HBase_Key implements iHBase_Key {
    
    protected $rowKey;
    public function __construct( $rowKey ){
        $this->rowKey = $rowKey;
    }
    public function getRowKey(){
        return $this->rowKey;
    }
    public function __toString() {
        return $this->getRowKey();
    }
}

?>
