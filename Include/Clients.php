<?php
    require "../Connection.php";
      
    class Clients {
    
		private $iConnection;
	
		public function __construct( ) {
			$this->iConnection = DatabaseConnection::Create( );
		}

		public function __destruct( ) {
			unset( $this );
		}
		
		public function GetClients( ) {
			return $this->iConnection->FetchArray( $this->iConnection->ExecuteQuery( "SELECT * FROM `Clients`;" ) );
		}
		
		public function AddClient( $szUsername, $szPassword, $szEmail, $szName, $szLastName ) {
			$this->iConnection->ExecuteQuery( "INSERT INTO `Clients` ( `Username`, `Password`, `Email`, `Name`, `LastName` ) VALUES( '" . $szUsername . "', '" . $szPassword . "',  '" . $szEmail . "', '" . $szName . "', '" . $szLastName . "'  );" );
		}
		
		public function DeleteClient( $iId ) {
			$this->iConnection->ExecuteQuery( "DELETE FROM `Clients` WHERE `Id` = '" . $iId . "';" );
		}
		
		public function EditClient( $iId, $szUsername, $szPassword, $szEmail, $szName, $szLastName ) { 
		    $this->iConnection->ExecuteQuery( "UPDATE `Clients` SET `Username` =  '" . $szUsername . "', `Password` =  '" . $szPassword . "', `Email` =  '" . $szEmail . "', `Name` =  '" . $szName . "', `LastName` =  '" . $szLastName . "'   WHERE `Id` = '" . $iId . "';" );
	    }
	}
 ?>