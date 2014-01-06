<?php

/**
 * Attempt to lockout a Node/Edge for exclusive processing
 * @author Revan
 */
class AtmoicLockout {
	
    protected $_atomic;
    protected $_locked;
    protected $_counterUse;
    
    public function __construct( iAtomic $atomic ){
        $this->_atomic = $atomic;
        $this->_locked = null;
        $this->_counterUse = null;
    }
    
    public function getLock(){
        if( $this->isExpired() ){
            return $this->attemptLockout();
        } else {
            return false;
        }
    }
    
    /**
     *
     * @param Time(in seconds) $future 
     */
    public function releaseLock( $future = 500 ){
        
        if( ! $this->_locked ){ return; }
        
        // Set new expires
        $this->_atomic->setExpiration( time() + $future );
        // Set new index as opposite of current
        $newIndex   = $this->_counterUse == 0 ? 1 : 0;
        $this->_atomic->setIntervalIndex( $newIndex );
        // Now update old Counter and reset it to zero to restart race next time
        $value      = $this->_atomic->incrementAtomicCounter( $newIndex );
        $zeroValue  = $this->_atomic->incrementAtomicCounter( $newIndex, -$value );
        if( $zeroValue !== 0 ){ throw new GRAPH_EXCEPTION('Resetting the Atomic Counter Failed'); }
        
        $this->_locked = false;
    }
    
    /**
     * Destroy this object and reset the lockout if forgotten to do so upon destruction 
     */
    public function __destruct() {
        if( $this->_locked ){
            $this->releaseLock();
        }
    }
    
    protected function attemptLockout(){
        
        $index = $this->currentIndex();
        $this->_counterUse = $index;
        $value = $this->_atomic->incrementAtomicCounter( $index );
        
        if( $value === 1 ){
            // Lockout achieved
            $this->_locked = true;
            return true;
        } else {
            // Failed to acquire lockout
            return false;
        }
        
    }
    
    
    /**
     *
     * @note When the index is not set in storage, a zero is returned (casting of null to int)
     * 
     * @return INT
     */
    protected function currentIndex(){
        return (int)$this->_atomic->currentIntervalIndex();
    }
    protected function isExpired(){
        // NO pre-existing value, it's expired
        if( is_null( $this->_atomic->getExpiration() ) ){ return true; }
        return time() > $this->_atomic->getExpiration();
    }
    
}

?>
