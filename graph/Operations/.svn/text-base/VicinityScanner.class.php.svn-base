<?php

/**
 * Scan for nearby relations from a Node
 * 
 * @author Revan
 */
class VicinityScanner {
    
    
    /**
	 * Given start node, scan for $limit edges
	 * 
	 * Obtain multiple edges with the Node as starting point
	 * 
	 * @param Node $node
	 * @param type $limit 
     * @return Edge array
	 */
    public static function vicinity( Node $node, $edgeType, $limit = 10 ){
        $values = $node->gStorage($edgeType)->read()->scanPrefix( new GraphKey( GraphKey::assemble( array( $node->getKeyString(),'' )), true ), $limit );
        return static::parseReturn($values,$edgeType);
    }
	
	public static function partialVicinity( GraphKey $partialKey, $edgeType, $limit = 10 ){
		$values = $node->gStorage($edgeType)->read()->scanPrefix( $partialKey, $limit );
        return static::parseReturn($values,$edgeType);
	}
    
    public static function tablescan( $nodeType, $limit = 10 ){
        
        $rows = $nodeType::gStorage($nodeType)->read()->scanPrefix( new GraphKey(''), $limit);
        $output = array();
        foreach( $rows as $row ){
            $output[] = $nodeType::generate( $row );
        }
        
        return $output;
    }
	
	public static function partialNodeScan( GraphKey $partialKey, $nodeType, $limit = 10 ){
		
		$rows = $nodeType::gStorage($nodeType)->read()->scanPrefix( $partialKey, $limit);
        $output = array();
        foreach( $rows as $row ){
            $output[] = $nodeType::generate( $row );
        }
        return $output;
	}
	
    public static function raw( Node $node, $edgeType, $limit = 10 ){
        $values = $node->gStorage($edgeType)->read()->scanPrefix( new GraphKey( GraphKey::assemble( array( $node->getKeyString(),'' )), true ), $limit );
        return $values;
    }
    
	/**
	 * Given a start node and label, scan for $limit edges
	 * 
	 * Obtain multiple labeled edges with the Node as starting point
	 * 
	 * @param Node $node
	 * @param type $label
	 * @param type $edgeType
	 * @param type $limit 
	 */
    public static function vicinityLabel( Node $node, $label, $edgeType, $limit = 10 ){
        $compoundKey = GraphKey::assemble( array( $node->getKeyString(), $label ) );
        $key = new GraphKey( $compoundKey, true );
        $values = $node->gStorage($edgeType)->read()->scanPrefix( $key, $limit );
        return static::parseReturn($values,$edgeType);
    }
    
    protected static function parseReturn( $values, $edgeType ){
        $output = array();
        if( is_array( $values ) ){
            foreach( $values as $v ){
                
                // Extract from this Key the from and to values
                $gkey   = new GraphKey( $v->getKey() );
                $parts  = $gkey->split();
                
                try {
                if( sizeof( $parts ) == 2 ){
                    // From, To
                    $output[] = $edgeType::generate( $parts[0], $parts[1] );
                } else {
                    // From, To, Label
                    $output[] = $edgeType::generate( $parts[0], $parts[2], $parts[1] );
                }
                } catch( KoreException $e ){
                    echo $e->getMessage();
                }
            }
        }
        return $output;
    }
    
}

?>
