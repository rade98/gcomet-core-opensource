<?php
	require "./Connection.php";
	
	class Server {
		private $iID;
		
		public function __construct( $iServerID ) {
			$this->iID = $iServerID;
		}
		
		public function __destruct( ) {
			unset( $this->iID );
		}
		
		public function GetData( ) {
			return GetConnection( )->FetchArray( GetConnection( )->ExecuteQuery( "SELECT * FROM `Servers` WHERE `Id` = '" . $this->iID . "';" ) );
		}
	}
?>
