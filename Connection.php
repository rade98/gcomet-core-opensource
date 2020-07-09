<?php
	require "Include/DataBase.php";
	include "Config.php";

	// DataBaseConnection class
	// Used for starting new database connection instance
	class DataBaseConnection {
		// Static function for openning new connection to MySQL Database
		// @return DataBase object
		public static function Create( ) {
			$iConfig = Config::LoadConfig( );
			$iMySQLConnection = new DataBase( $iConfig->database_host, $iConfig->database_user, $iConfig->database_pass, $iConfig->database_name, $iConfig->database_port );
			return $iMySQLConnection;
		}
	}
?>
