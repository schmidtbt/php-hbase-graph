<?php

// Could be a Trait in PHP 5.4
/**
 * @author Revan
 */
interface iAtomic {
    
    public function getExpiration();
    public function currentIntervalIndex();
    public function counterOne();
    public function counterTwo();
    public function incrementAtomicCounter( $idx, $step = 1 );
    public function setExpiration( $unixTime );
    public function setIntervalIndex( $index );
    
}

?>
