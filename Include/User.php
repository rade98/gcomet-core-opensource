<?php
	require "./Connection.php";
	
	class User {
		private $iID;
		
		public function __construct( $iId ) {
			$this->iID = $iId;
		}
		
		public function __destruct( ) {
			unset( $this->iID );
		}
		
		public function GetData( ) {
			return GetConnection( )->FetchArray( GetConnection( )->ExecuteQuery( "SELECT * FROM `Clients` WHERE `Id` = '" . $this->iID . "';" ) );
		}
		
		public function DoesExists( ) {
			return GetConnection( )->GetRowCount( GetConnection( )->ExecuteQuery( "SELECT COUNT( `Id` ) FROM `Clients` WHERE `Id` = '" . $this->iID . "';" ) != 0 ? true : false;
		}
		
		public function IsLoggedIn( ) {
			return isset( $_SESSION[ "Id" ] ) ? true : false;
		}
		public function EditProfile( $iId, $szUsername, $szPassword, $szEmail, $szName, $szLastName ) { 
		    $this->iConnection->ExecuteQuery( "UPDATE `Users` SET `Username` =  '" . $szUsername . "', `Password` =  '" . $szPassword . "', `Email` =  '" . $szEmail . "', `Name` =  '" . $szName . "', `LastName` =  '" . $szLastName . "'   WHERE `Id` = '" . $iId . "';" );
	    }
	    public function newRegistration( $szUsername, $szPassword, $szEmail, $szName, $szLastName, $szPin, $szCountry) {
	        $this->iConnection->ExecuteQuery( "INSERT INTO `Users` ( `Username`, `Password`, `Email`, `Name`, `LastName`, `Pin`, `Country` ) VALUES ( '" . $szUsername . "', '" . $szPassword . "', '" . $szEmail . "', '" . $szName . "', '" . $szLastName . "', '" . $szPin . "', '" . $szCountry . " );" );
	    }
	}
?>
