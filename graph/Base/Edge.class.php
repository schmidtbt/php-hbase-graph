<?php

/**
 * @author Revan
 */
abstract class Edge extends GraphEntity {
    
    const EDGE = 'edge';
    const FROMNODE = 'from';
    const TONODE = 'to';
    
    /**
     *
     * @var Node
     */
    protected $_fromNode;
    /**
     *
     * @var Node
     */
    protected $_toNode;
    
    /**
     * @param Node $from
     * @param Node $to 
     */
    public function __construct( Node $from, Node $to, $label = '' ){
        $key = static::assembleKey($from, $to, $label);
        $this->from( $from );
        $this->to( $to );
        parent::__construct($key);
    }
    
    public static function generate( $fromKey, $toKey, $label = '' ){
        throw new KoreException('Generate not implemented for: '.  get_called_class());
    }
    
    public function from( Node $from = NULL ){
        if( is_null( $from ) ){
            return $this->_fromNode;
        } else {
            $this->setFrom($from);
        }
    }
    public function to( Node $to = NULL ){
        if( is_null( $to ) ){
            return $this->_toNode;
        } else {
            $this->setTo($to);
        }
    }
    
    protected function setFrom( Node $from ){
        $this->_fromNode = $from;
    }
    protected function setTo( Node $to ){
        $this->_toNode = $to;
    }
    
    /**
     *
     * @param Node $from
     * @param Node $to 
     * @return \GraphKey
     */
    public static function assembleKey( Node $from, Node $to, $label = '' ){
        if( $label === '' ){
            return GraphKey::assemble( array( $from->_key->getRowKey(), $to->_key->getRowKey() ) );
        } else {
            return GraphKey::assemble( array( $from->_key->getRowKey(), $label, $to->_key->getRowKey() ) );
        }
    }
    public static function create( Node $from, Node $to, $label = '' ){
        try {
            static::executeCreate($from, $to, $label);
        } catch( GRAPH_COMPLIANCE_EXCEPTION $e ){
            throw $e;
        } catch( RECORD_EXISTS $e ){
            throw new GRAPH_ENTITY_EXISTS('Cannot Overwrite '.get_parent_class().' Record',0,$e);
        } catch( KoreException $e ){
            throw new GRAPH_STORAGE_EXCEPTION('Edge Create Failure: '.$e->getMessage(),0,$e);
        }    
    }
    
    protected static function executeCreate( Node $from, Node $to, $label = '' ){
        throw new KoreException('ExecuteCreate method not overridden');
    }
}

?>
