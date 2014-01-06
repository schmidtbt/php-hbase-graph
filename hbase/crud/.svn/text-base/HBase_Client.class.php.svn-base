<?php

/**
 * @author Revan
 */
class HBase_Client {
    
	protected $socket;
	protected $transport;
	protected $protocol;
    /**
     *
     * @var \HbaseClient
     */
	protected $client;
    
    public function __construct(iHBase_Table $table ){
        $this->initConnection( $table->getTableName() );
    }
    
	/**
	 * @name Hbase Connection
	 */
	//@{
    public      function initConnection( $tableName = NULL ){
        if( is_null( $tableName ) && ! isset( $this->table ) ){
            throw new Kore_Storage_Exception( __CLASS__ . '|' . __FUNCTION__ . '| Table Name Not Provided.' );
        }
        
        if( !is_null( $tableName ) ){
            $this->table = $tableName;
        }
        $this->init();
		$this->connect();
    }
	protected   function init(){
		$this->socket = new TSocket( self::roundRobinIP(), 9090 );
		$this->socket->setSendTimeout( 10000 ); // Ten seconds (too long for production, but this is just a demo ;)
		$this->socket->setRecvTimeout( 20000 ); // Twenty seconds
		$this->transport = new TBufferedTransport( $this->socket );
		$this->protocol = new TBinaryProtocol( $this->transport );
		$this->client = new HbaseClient( $this->protocol );
	}
	/**
	 * @TODO use as a on-demand connection (remove from Kore_Storage_Hbase::__construct() )
	 */
	protected   function connect(){
		$this->transport->open();
	}
    public      function close(){
        $this->transport->close();
    }
	//@}
    
    public function thrift(){
        return $this->client;
    }
    
    protected static function roundRobinIP(){
        
        
        $IPs = array(
            //'10.10.0.13',
            '10.10.0.14'
        );
        
        $val = rand(0, sizeof($IPs) - 1);
        
        
        return $IPs[ $val ];
    }
	
}

?>
