<?php
    require "../Connection.php";
      
    class Admin {
    
		private $iConnection;
	
		public function __construct( ) {
			$this->iConnection = DatabaseConnection::Create( );
		}

		public function __destruct( ) {
			unset( $this );
		}
		
		public function GetAdmins( ) {
			return $this->iConnection->FetchArray( $this->iConnection->ExecuteQuery( "SELECT * FROM `Admins`;" ) );
		}
		
		public function AddAdmin( $szUsername, $szPassword, $szEmail, $szName, $szLastName, $szRank ) {
			$this->iConnection->ExecuteQuery( "INSERT INTO `Admin` ( `Username`, `Password`, `Email`, `Name`, `LastName`, `Rank` ) VALUES( '" . $szUsername . "', '" . $szPassword . "',  '" . $szEmail . "', '" . $szName . "', '" . $szLastName . "', '" . $szRank . "'  );" );
		}
		
		public function DeleteAdmin( $iId ) {
			$this->iConnection->ExecuteQuery( "DELETE FROM `Admin` WHERE `Id` = '" . $iId . "';" );
		}
		
		public function EditAdmin( $iId, $szUsername, $szPassword, $szEmail, $szName, $szLastName, $szRank ) { 
		    $this->iConnection->ExecuteQuery( "UPDATE `Admins` SET `Username` =  '" . $szUsername . "', `Password` =  '" . $szPassword . "', `Email` =  '" . $szEmail . "', `Name` =  '" . $szName . "', `LastName` =  '" . $szLastName . "', `Rank` =  '" . $szRank . "'   WHERE `Id` = '" . $iId . "';" );
	    }
	}
 ?>