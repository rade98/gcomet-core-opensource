<?php
	class DataBase {
		// Variables
		private $iConnection = null;
		
		// Constructor
		// @return void
		function __construct( $szHost, $szUsername, $szPassword, $szDataBase, $iPort ) {
			$this->iConnection = mysqli_connect( $szHost, $szUsername, $szPassword, $szDataBase, $iPort );
			
			if( $this->iConnection->connect_errno ) {
				die( "MySQL Error (" . $this->iConnection->connect_errno . "): " . $this->iConnection->connect_error );
			}
		}
		
		// Function for closing the connection
		// @return void
		function ClearConnection( ) {
			$iThread = $this->iConnection->thread_id;
			$this->iConnection->close( );
			$this->iConnection->kill( $iThread );
		}
		
		// Function for getting connection
		// @return Integer - Connection id
		public function GetConnection( ) {
			return $this->iConnection;
		}
		
		// Function for executing query
		// @return mysqli_result object
		public function ExecuteQuery( $szQuery ) {
			if( !$this->iConnection ) {
				die( "Connection with MySQL base is not established!" );
			}
			
			return $this->iConnection->query( $this->iConnection->real_escape_string( $szQuery ) );
		}

		// Function for fetching array from query
		// @return String Array
		// on query error, returns null
		public function FetchArray( $iQuery ) {
			if( !$this->iConnection ) {
				die( "Connection with MySQL base is not established!" );
			}
			if( $iQuery === false )
				return null;
			
			return $iQuery->fetch_assoc( );
		}
		
		// Function for Fetching filed
		// @return String
		// on query error, returns null
		public function FetchField( $iQuery, $szField ) {
			if( !$this->iConnection ) {
				die( "Connection with MySQL base is not established!" );
			}
			if( $iQuery === false )
				return null;
			
			$aResult = $this->FetchArray( $iQuery );
			return $aResult[ $szField ];
		}
		
		// Function for getting row
		// @return Integer
		public function GetRowCount( $iQuery ) {
			if( !$this->iConnection ) {
				die( "Connection with MySQL base is not established!" );
			}
			if( $iQuery === false )
				return 0;
			
			$aResult = $iQuery->fetch_row( );
			return $aResult[ 0 ];
		}
	}
?>
