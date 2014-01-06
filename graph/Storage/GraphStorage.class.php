<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GraphStorage
 *
 * @author Revan
 */
class GraphStorage {
    
    protected $_table;
    protected $_crudType;
    
    public function __construct( $entity = NULL ) {
        if( !is_null( $entity ) ){
            $this->swtichEntity($entity);
        }
    }
    
    /**
     *
     * @param GraphEntity $entity = NULL
     * @param type $type
     * @return \HBase_Crud
     */
    public function CRUD( GraphEntity $entity = NULL, $type ){
        $this->swtichEntity($entity);
        $this->switchType($type);
        $tableObj = HBase_Table_Builder::build( $this->_table );
        $crud = new $this->_crudType( $tableObj );
        return $crud;
    }
    /**
     *
     * @param GraphEntity $entity = NULL
     * @return \HBase_Create
     */
    public function create( GraphEntity $entity = NULL ){
        return $this->CRUD($entity, CRUD::CREATE );
    }
    /**
     *
     * @param GraphEntity $entity = NULL
     * @return \HBase_Read
     */
    public function read( GraphEntity $entity = NULL ){
        return $this->CRUD($entity, CRUD::READ );
    }
    /**
     *
     * @param GraphEntity $entity = NULL
     * @return \HBase_Delete
     */
    public function delete( GraphEntity $entity = NULL ){
        return $this->CRUD($entity, CRUD::DELETE );
    }
    /**
     *
     * @param GraphEntity $entity = NULL
     * @return \HBase_Update
     */
    public function update( GraphEntity $entity = NULL ){
        return $this->CRUD($entity, CRUD::UPDATE );
    }
    
    /**
     * Maps between Class names of GraphEntity and Tables in HBase
     * @param $entity = NULL Either a GraphEntity or a String of GraphEntity class name
     */
    protected function swtichEntity( $entity = NULL ){
        
        if( $entity == null ){
            return;
        }
        
        if( $entity instanceof GraphEntity ){
            $class = get_class( $entity );
        } elseif(class_exists($entity)) {
            $class = $entity;
        } else {
            throw new GRAPH_EXCEPTION( 'Entity is not an Object of Valid object entity name' );
        }
        $this->_table = HbaseStorage::map($class);
    }
    
    protected function switchType( $type ){
        
        switch( $type ){
            
            case CRUD::READ:
                $this->_crudType = 'HBase_Read';
                break;
            case CRUD::CREATE:
                $this->_crudType = 'HBase_Create';
                break;
            case CRUD::UPDATE:
                $this->_crudType = 'HBase_Update';
                break;
            case CRUD::DELETE:
                $this->_crudType = 'HBase_Delete';
                break;
            default:
                throw new GRAPH_EXCEPTION( 'Invalid CRUD Type' );
        }
        
    }
    
}

?>
