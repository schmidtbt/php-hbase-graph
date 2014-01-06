<?php

/**
 * 
 * @author Revan
 */
abstract class GraphEntity {
    
    const LOCKOUT = 'lockout';
    const ACTION = 'action';
    
    /**
     * @var GraphKey
     */
    protected $_key;
    /**
     *
     * @var \HBase_Record
     */
    protected $_recordData;
    /**
     *
     * @var GraphStorage
     */
    protected $_storage;
    
    public function __construct( GraphKey $key ){
        $this->setKey($key);
        
        $reader = $this->graphStorage()->read();
        $output = $reader->read( $this->_key );
        
        if( ! $output instanceof HBase_Record ){
            throw new GRAPH_ENTITY_DOES_NOT_EXIST( 'Could not find record, while Attempting to Initialize: ' . get_called_class(). ' '. $key->getRowKey() );
        }
        
        $this->setRecordData( $output );
    }
    public function setKey( GraphKey $key ){
        $this->_key = $key;
    }
    /**
     * @return GraphKey
     */
    public function getKey(){
        return $this->_key;
    }
    public function getKeyString(){
        return $this->_key->getRowKey();
    }
    
    public static function gStorage( $className = NULL ){
        if( is_null( $className ) ){
            $className = __CLASS__;
        }
        return new GraphStorage( $className );
    }
    
    public function getRecordData(){
        return $this->_recordData;
    }
    protected function graphStorage(){
        if( !isset( $this->_storage ) ){
            $this->_storage = new GraphStorage( $this );
        }
        return $this->_storage;
    }
    protected function setRecordData( HBase_Record $record ){
        $this->_recordData = $record;
    }
    
    protected function addAttribute( $colFam, $col, $newValue ){
        $create = $this->graphStorage()->create();
        $create->createEntry( $this->_key, $colFam, $col, $newValue );
    }
    protected function modAttribute( $colFam, $col, $newValue ){
        $updater = $this->graphStorage()->update();
        $updater->add( $colFam, $col, $newValue );
        $updater->update( $this->_key );
    }
    protected function incrementAttribute( $colFam, $col, $step = 1 ){
        $updater = $this->graphStorage()->update();
        return $updater->increment( $this->_key, $colFam, $col, $step );
    }
    /**
     * 
     * @param type $colFam
     * @param type $col
     * @return HBaseCellValue 
     */
    protected function getAttribute( $colFam, $col ){
        return $this->_recordData->getColumn()->$colFam->$col;
    }
    protected function getAttributeValue( $colFam, $col ){
        return $this->getAttribute($colFam, $col)->value;
    }
    protected function getAttributeTS( $colFam, $col ){
        $this->getAttribute($colFam, $col)->timestamp;
    }
    protected function removeAttribute( $colFam, $col ){
        return $this->graphStorage()->delete()->removeColumn( $this->_key, $colFam, $col );
    }
    protected function getAttributeColFamilyValues( $colFam, $startSearch = '' ){
        $fam = $this->_recordData->getColumn()->$colFam;
        if( $startSearch === '' ){
            return $fam->cellValueAsStringArray();
        } else {
            $return = array();
            $valArray = $fam->cellValueAsStringArray();
            foreach( $valArray as $key=>$val ){
                if( strpos( $key, $startSearch ) === 0 ){
                    $return[$key] = $val;
                }
            }
            return $return;
        }
    }
    protected function getColFamily( $colFam ){
        return $this->_recordData->getColumn()->$colFam;
    }
    protected function removeRow(){
        return $this->graphStorage()->delete()->removeRow( $this->_key );
        
    }
}

?>
