<?php
	include "./Config.php";

	class AutoUpdate {
		// Variables
		private $szRemoteRepositroy = "";
		private $szError = "";
		private $szLatestVersion = "";
		private $szUpdateURL = "";
		private $jsonConfig = null;
		private $localConfig = null;

		// Constructor
		// @return void
		public function __construct( ) {
			$this->localConfig = Config::LoadConfig();
			$this->jsonConfig = json_decode( file_get_contents( $this->szRemoteRepositroy ), true );

			$this->szLatestVersion = $this->jsonConfig->{ "Version" };
			$this->szUpdateURL = $this->jsonConfig->{ "URL" };
		}

		// Function for version comparation
		// @return  boolean - If current version is not greater from remote version
		public function UpdateIsAvaleiable( ) {
			return version_compare( $this->localConfig->gamepanel_version, $this->szLatestVersion, "<" ) ? true : false;
		}

		// Funcition which downloads update
		// @return   String - File path if it is downloaded, Failed otherwise
		public function DownloadUpdate( $szPath ) {
			$iFile = fopen( $szPath, 'w+' );
			$iCURL = curl_init( );
			curl_setopt( $iCURL, CURLOPT_URL, $this->szUpdateURL );
			curl_setopt( $iCURL, CURLOPT_BINARYTRANSFER, true );
			curl_setopt( $iCURL, CURLOPT_RETURNTRANSFER, false );
			curl_setopt( $iCURL, CURLOPT_SSL_VERIFYPEER, false );
			 
			curl_setopt( $iCURL, CURLOPT_CONNECTTIMEOUT, 10 );
			curl_setopt( $iCURL, CURLOPT_FILE, $iFile );
			curl_exec( $iCURL );
			curl_close( $iCURL );
			fclose( $iFile );
			 
			if( filesize( $szPath ) > 0 )
				return $szPath;

			return "Failed";
		}

		// Function for backup of old version files
		// @return  void
		public function BackupPreviousVersion( ) {

			$rootDir = getcwd( );

			$backupDir = $rootDir . DIRECTORY_SEPARATOR . $this->localConfig->backup_directory;

			if( !is_dir( $backupDir ) ) {
				mkdir( $backupDir );
			}

			$timeStamp = time( );

			$szBackupFile = $backupDir . DIRECTORY_SEPARATOR .
								"controlify_" . date( 'd.m.Y', $timeStamp ) . "-" . $timeStamp . ".zip";

			echo $szBackupFile;

			$iZip = new ZipArchive;
			$iFile = $iZip->open( $szBackupFile, ZipArchive::CREATE );


			$listOfFiles = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator( $rootDir ),
				RecursiveIteratorIterator::LEAVES_ONLY
			);

			// Loop through the list of files ($listOfFiles)
			foreach( $listOfFiles as $key => $file ) {

				// skip directories (zip will keep the structure), along with Backups and .git directory
				if( !$file->isDir( ) && strpos( $file, $this->localConfig->backup_directory ) === false && strpos( $file, ".git" ) === false ) {

					$szRealPath = $file->getRealPath( );

					$szShortPath = str_replace( $rootDir, ".", $szRealPath );

					$iZip->addFile( $szRealPath, $szShortPath );
				}
			}

			$iZip->close( );
		}

		// Function which extracts downloaded ZIP file
		public function InstallUpdate( $szPath ) {
			$iZip = new ZipArchive;
			$iFile = $iZip->open( $szPath );

			if( $iFile === TRUE ) {
				$iZip->extractTo( getcwd( ) );
				$iZip->close( );

			
				$this->localConfig->gamepanel_version = $this->szLatestVersion;

				$szNewFileConfig = json_encode( $this->localConfig, JSON_PRETTY_PRINT );

				Config::WriteConfig( $szNewFileConfig );

				return true;
			} else {
			    return false;
			}
		}

		// Function for checking if error occured
		// @return  bolean
		public function IsThereError( ) {
			return strlen( $this->szError ) > 0 ? true : false;
		}

		// Function for getting last error
		// @return  String
		public function GetLastError( ) {
			return $this->szError;
		}
	}
?>
