<?php
	class Ftp {
		// Variables
		private $iConnection;
		private $szLastError = "";
		
		// Constructor: Connects and logs to FTP server
		// @return void
		public function __construct( $szHost, $szUser, $szPassword, $iPort = 21 ) {
			// Establish connection with remote FTP server
			$this->iConnection = ftp_connect( $szHost, $iPort );
			
			// Check if connection is not established
			if( !$this->iConnection ) {
				$this->szLastError = "Connection failed!";
				return;
			}
			
			// Login to remote FTP server
			$this->iLogin = ftp_login( $this->iConnection, $szUser, $szPassword );
			
			// Check if login was succesfull
			if( !$this->iLogin )
				$this->szLastError = "Login failed!";
		}

		// Destructor
		// @return void
		public function __destructor( ) {
			$this->Clear( );
		}

		// Function for memory freeing
		// @return void
		public function Clear( ) {
			ftp_close( $this->iConnection );
			unset( $this );
		}
		
		// Function for getting files/folders
		// @return Files and Folders list soreted by alphabetic order
		public function GetListOfFiles( ) {
			return sort( ftp_nlist( $this->iConnection, "." ) );
		}
		
		// Function for checking if file is folder
		// @return boolean
		public function IsDir( $szFile ) {
			if( ftp_chdir( $this->iConnection, $szDir ) ) {
				ftp_chdir( $this->iConnection, ".." );
				return true;
			}
			
			return false;
		}
		
		// Function for changing current directorium
		// @return void
		public function ChangeDir( $szDir ) {
			ftp_chdir( $this->iConnection, $szDir );
		}
		
		// Function for uploading files to remote FTP server
		// @return bolean - Ture in success, false otherwise
		public function Upload( $szPath, $szFile ) {
			if( ftp_put( $this->iConnection, $szPath, $szFile, FTP_BINARY ) )
				return true;
			
			return false;
		}

		// Function for deleting files/folders
		// @return void
		public function Delete( $szPath ) {
			if( $this->IsDir( $szPath ) ) {
				ftp_rmdir( $this->iConnection, $szPath );
			} else {
				ftp_delete( $this->iConnection, $szPath );
			}
		}
		
		// Function for renaming files on remote FTP server
		// @return bolean - Ture in success, false otherwise
		public function Rename( $szOld, $szNew ) {
			if( ftp_put( $this->iConnection, $szOld, $szNew ) )
				return true;
			
			return false;
		}
		
		// Function for checking is there error occured
		// @return bolean - Ture in success, false otherwise
		public function IsThereError( ) {
			return strlen( $this->szLastError ) > 0 ? true : false;
		}
		
		// Function for getting latest error
		// @return String - Latest error
		public function GetLatestError( ) {
			return $this->szLastError;
		}
	}
?>