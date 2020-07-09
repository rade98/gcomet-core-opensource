<?php
	require "../Connection.php";
	
	class Login {
		// Variables
		private $szUser;
		private $szPassword;
		
		// Constructor
		// @return void
		public function __construct( $szUser, $szPassword ) {
			$this->szUser = $szUser;
			$this->szPassword = $this->ByMCrypt( $szPassword );
		}
		
		// Destructor
		// @return void
		public function __destruct( ) {
			unset( $this->szUser );
			unset( $this->szPassword );
		}
		
		// Function for validating user data
		// @return boolean - True if user is logged in, false otherwise
		public function DoLogin( ) {
			$connection = DataBaseConnection::Create( );
			return $connection->GetRowCount( $connection->ExecuteQuery( "SELECT * FROM `Clients` WHERE `Email` = '" . $this->szUser . "' AND `Password` = '" . $this->szPassword ."';" ) ) > 0 ? true : false;
		}

		// Function for getting logged user id
		// @return Integer - User id
		public function GetLoggedUserID( ) {
			$connection = DataBaseConnection::Create( );
			return $connection->FetchField( $connection->ExecuteQuery( "SELECT * FROM `Clients` WHERE `Email` = '" . $this->szUser . "' AND `Password` = '" . $this->szPassword ."';" ), "Id" );
		}

		// Function for checking if user is admin
		// @return boolean - True if it is, false otherwise
		public function IsUserAdmin( ) {
			$conn = DataBaseConnection::Create( );
			return $connection->FetchField( $connection->ExecuteQuery( "SELECT * FROM `Clients` WHERE `Email` = '" . $this->szUser . "' AND `Password` = '" . $this->szPassword ."';" ), "Admin" ) == 1 ? true : false;
		}

		// Function for encrypting user password
		// @return String - Encrypted password
		public function ByMCrypt( $szPassword ) {
			return password_hash( $szPassword, PASSWORD_BCRYPT );
		}
	}
?>
