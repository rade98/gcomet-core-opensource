<?php
	require "../Connection.php";
	
	class Box {
		// Variables
		private $iConnection;
		
		// Constructor
		// @params void
		// @return void
		public function __construct( ) {
			$this->iConnection = DatabaseConnection::Create( );
		}
		
		// Destructor
		// @params void
		// @return void
		public function __destruct( ) {
			unset( $this );
		}
		
		// Method for getting list of boxes
		// @params void
		// @return Array - List of boxes
		public function GetBoxes( ) {
			return $this->iConnection->FetchArray( $this->iConnection->ExecuteQuery( "SELECT * FROM `Box` ORDERED BY `Id` DESC;" ) );
		}
		
		// Method for getting info of box
		// @params:
		// $iBox - Integer - Box id
		// @return Array - Box info
		public function GetBox( $iBox ) {
			return $this->iConnection->FetchArray( $this->iConnection->ExecuteQuery( "SELECT * FROM `Box` WHERE `Id` = '" . $iBox . "';" ) );
		}
		
		// Method for adding boxes
		// @params:
		// $szName - String - Box name
		// $szHost - Strinng - Ip
		// $szUser - String - User
		// $szPassword - String - Password
		// $iPort - Integer - Port
		// $szLocation - String - Country, Town
		// @return void
		public function AddBox( $szName, $szHost, $szUser, $szPassword, $iPort, $szLocation ) {
			$this->iConnection->ExecuteQuery( "INSERT INTO `Box` ( `Name`, `Host`, `User`, `Password`, `Port`, `Location` ) VALUES( '" . $szName . "', '" . $szHost . "', '" . $szUser . "', '" . $szPassword .  "', " . $iPort . ", '" . $szLocation . "' );" );
		}
		
		// Method for deleting box
		// @params:
		// $iBox - Integer - Box id
		// @return void
		public function DeleteBox( $iBox ) {
			$this->iConnection->ExecuteQuery( "DELETE FROM `Box` WHERE `Id` = '" . $iBox . "';" );
		}
	}
?>