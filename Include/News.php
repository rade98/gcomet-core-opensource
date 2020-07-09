<?php
	require "../Connection.php";
	
	class News {
		// Variables
		private $iConnection;
		
		// Constructor
		// @params void
		// @return vois
		public function __construct( ) {
			$this->iConnection = DatabaseConnection::Create( );
		}
		
		// Destructor
		// @params void
		// @return vois
		public function __destruct( ) {
			unset( $this );
		}
		
		// Method for getting news
		// @params void
		// @return Array - News
		public function GetNews( ) {
			return $this->iConnection->FetchArray( $this->iConnection->ExecuteQuery( "SELECT * FROM `News`;" ) );
		}
		
		// Method for adding news
		// @params:
		// $szTitle - String - Title
		// $szContent - String - Content
		// @return void
		public function AddNews( $szTitle, $szContent ) {
			$this->iConnection->ExecuteQuery( "INSERT INTO `News` ( `Title`, `Content`, `Date` ) VALUES( '" . $szTitle . "', '" . $szContent . "', '" . date( '%d.%m.%Y %H:%i', time( ) ) . "' );" );
		}
		
		// Method for deleting news
		// @params void
		// @return void
		public function DeleteNews( $iId ) {
			$this->iConnection->ExecuteQuery( "DELETE FROM `News` WHERE `Id` = '" . $iId . "';" );
		}
		
		public function EditNews( $iId, $szTitle, $szContent) { 
		    $this->iConnection->ExecuteQuery( "UPDATE `News` SET `Title` =  '" . $szTitle . "', `Content` = '" . $szContent . "'  WHERE `Id` = '" . $iId . "';" );
	    }
	}
 ?>