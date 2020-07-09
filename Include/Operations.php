<?php
	require "./Ssh.php";

	class Operations {
		// Variables
		private $szHost;
		private $iPort;
		private $szUser;
		private $szPassword;
		private $szError;

		// Constructor
		// @return void
		public function __construct( $szHost, $iPort, $szUser, $szPassword ) {
			$this->szHost = $szHost;
			$this->iPort = $iPort;
			$this->szUser = $szUser;
			$this->szPassword = $szPassword;
		}

		// Function for starting thieserver
		// @return String - Success on succes, error otherwise
		public function StartServer( $szStartCommand ) {
			$iSSH = new Ssh_Connection( $this->szHost, $this->iPort, $this->szUser, $this->szPassword );
			if( !$iSSH->OpenConnection( ) ) {
				$this->szError = $iSSH->GetLastError( );
				$iSSH->Clear( );

				return $szError;
			} else {
				$iSSH->AddCommand( "screen -A -m -S " . $this->szUser );
				$iSSH->AddCommand( $szStartCommand );
				$iSSH->ExecuteCommands( );
			}
			
			$iSSH->Clear( );
			return "Success";
		}

		// Function for stopping the server
		// @return String - Success on succes, error otherwise
		public function StopServer( ) {
			$iSSH = new Ssh_Connection( $this->szHost, $this->iPort, $this->szUser, $this->szPassword );
			if( !$iSSH->OpenConnection( ) ) {
				$this->szError = $iSSH->GetLastError( );
				$iSSH->Clear( );

				return $szError;
			} else {
				$iSSH->AddCommand( 'kill -9 `screen -list | grep "' . $this->szUser . '" | awk {\'print $1\'} | cut -d . -f1`' );
				$iSSH->AddCommand( "screen -wipe" );
				$iSSH->ExecuteCommands( );
			}
			
			$iSSH->Clear( );
			return "Success";
		}
		
		// Function for restarting server
		// @return void
		public function RestartServer( ) {
			StopServer( );
			sleep( 5 );
			StartServer( );
		}
	}
?>
