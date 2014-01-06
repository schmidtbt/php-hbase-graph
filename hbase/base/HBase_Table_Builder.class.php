<?php

/**
 * @author Revan
 */
class HBase_Table_Builder {
    
    
    const USERS = 'users';
    const TAGS = 'tags';
    
    
    public static function build( $table ){
        return new HBase_Table( $table );
    }
    
    
}

?>
