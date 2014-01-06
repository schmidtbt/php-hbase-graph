<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LabeledEdge
 *
 * @author Revan
 */
abstract class LabeledEdge extends Edge {
    
    protected $_label;
    
    public function __construct(  Node $from, Node $to, $label ){
        $key = static::assembleKey($from, $to, $label);
        $this->from( $from );
        $this->to( $to );
        parent::__construct($key);
    }
    
    /**
     *
     * @param Node $from
     * @param Node $to
     * @param type $label 
     * @return \GraphKey
     */
    public static function assembleKey( Node $from, Node $to, $label ){
        return GraphKey::assemble( array( $from->_key->getRowKey(), $label, $to->_key->getRowKey() ) );
    }
    
}

?>
