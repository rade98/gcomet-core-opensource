<?php
    require "../Connection.php";
    require "SSH.php";
    require "Box.php";
    require "Ftp.inc";
     
    class ServerPlugins {
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
         
        public function AddPlugin( $szName, $szDescription, $szVersion, $szGame, $szPath ) {
            if( DoesPluginExists( $szName, $szVersion ) )
                return false;
                 
            $this->iConnection->ExecuteQuery( "INSERT INTO `GamePlugins` ( `Name`, `Description`, `Version`, `Game`, `Path` ) VALUES( '" . $szName . "', '" . $szDescription . "', '" . $szVersion . "', '" . $szGame . "', '" . $szPath . "' );" );
            return true;
        }
         
        //To do: Add Edit plugin function
         
        public function GetPluginData( $iPlugin ) {
            return $this->iConnection->FetchArray( $this->iConnection->ExecuteQuery( "SELECT * FROM `GamePlugins` WHERE `Id` = '" . $iPlugin . "';" ) );
        }
         
        private function DoesPluginExists( $szName, $szVersion ) {
            return $this->iConnection->FetchField( $this->iConnection->ExecuteQuery( "SELECT `Id` FROM `GamePlugins` WHERE `Name` = '" . $szName . "' AND `Version` = '" . $szVersion . "';" ), "Id" ) ? true : false;
        }
         
        public function GetAllPlugins( ) {          
            return $this->iConnection->FetchArray( $this->iConnection->ExecuteQuery( "SELECT * FROM `GamePlugins`;" ) );
        }
         
        public function GetGamePlugins( $iServer ) {
            $aServerData = new Server( $iServer )->GetData( );
             
            return $this->iConnection->FetchArray( $this->iConnection->ExecuteQuery( "SELECT * FROM `GamePlugins` WHERE `Game` = '" . $aServerInfo[ 'Game' ] . "';" ) );
        }
         
        public function IsPluginInstalled( $iServer, $iPlugin ) {
            return $this->iConnection->FetchField( $this->iConnection->ExecuteQuery( "SELECT `Id` FROM `ServerPlugins` WHERE `Server` = '" . $iServer . "' AND `PluginId` = '" . $iPlugin . "';" ), "Id" ) ? true : false;
        }
        
        public function EditPlugin( $iId, $szName, $szDescription, $szVersion, $szGame, $szPath ){
            return $this->iConnection->FetchArray( $this->iConnection->ExecuteQuery( "UPDATE `GamePlugins` SET `Name` = '" . $szName . "', `Description` = '" . $szDescription . "', `Version` = '" . $szVersion . "', `Game` = '" . $szGame . "', `Patch` = '" . $szPatch . "' WHERE `Id` = '" . $iId . "';" ) );
        }
         
        public function InstallPlugin( $iServer, $iPlugin ) {
            if( IsPluginInstalled( $iServer, $iPlugin ) ) 
                return false;           
                 
            $aServerData = new Server( $iServer )->GetData( );
            $aBoxData = new Box( $aServerData[ "Box" ] )->GetData( );
            $iSSH = new Ssh_Connection( $aBoxData[ "Host" ], $aBoxData[ "Port" ], $aBoxData[ "User" ], $aBoxData[ "Password" ] );
            if( !$iSSH->OpenConnection( ) ) {
                return false;
            } else {
                $aPluginData = $this->GetPluginData( $iPlugin );
                $iSSH->AddCommand( "cp -rf " . $aPluginData[ 'Path' ] . " /home/srv_" . $iServer . "/" );
                $iSSH->ExecuteCommands( );
                 
                $this->iConnection->ExecuteQuery( "INSERT INTO `ServerPlugins` ( `Server`, `PluginId` ) VALUES( '" . $iServer . "', '" . $iPlugin . "' );" );
            }
             
            $iSSH->Clear( );
            return true;
        }
         
        public function UninstallPlugin( $iServer, $iPlugin ) {
            if( !IsPluginInstalled( $iServer, $iPlugin ) )
                return false;
                      
            $aServerData = new Server( $iServer )->GetData( );
            $iFtp = new Ftp( $aServerData[ "Ip" ], "srv_" . $iServer, $aServerData[ "FtpPassword" ], $aServerData[ "FtpPort" ] );
            $aPluginData = $this->GetPluginData( $iPlugin );
             
            /* TO DO: Get list of plugin files from CF_INFO/{PluginName}_Resources and remove them from server ftp
             
            $szContent = $iFtp->GetFileContent( "CF_INFO/" . $aPluginData[ 'Name' ] . "_Resources" ); // To do
             
            for each line { //To do
                $iFtp->Delete( $szLine );
            }
            */
             
            $iFtp->Clear( );
             
            this->iConnection->ExecuteQuery( "DELETE FROM `ServerPlugins` WHERE `Server` = '" . $iServer . "' AND `PluginId` = '" . $iPlugin . ";" );
                 
            return true;
        }
         
        public function IsCorrupted( $iServer, $iPlugin ) {
            $aServerData = new Server( $iServer )->GetData( );
            $iFtp = new Ftp( $aServerData[ "Ip" ], "srv_" . $iServer, $aServerData[ "FtpPassword" ], $aServerData[ "FtpPort" ] );
            $aPluginData = $this->GetPluginData( $iPlugin );
            $bYes = false;
             
            /* TO DO: Get list of plugin files from CF_INFO/{PluginName}_Resources and check if every file  from that file exists
             
            $szContent = $iFtp->GetFileContent( "CF_INFO/" . $aPluginData[ 'Name' ] . "_Resources" ); // To do
             
            for each line { //To do
                if( !$iFtp->DoesFileExists( $szLine ) ) //To do
                    $bYes = true;
            }
            */
             
            $iFtp->Clear( );
            return $bYes;
        }
         
        public function ReapairPlugin( $iServer, $iPlugin ) {
            UninstallPlugin( $iServer, $iPlugin );
            sleep( 1 );
            InstallPlugin( $iServer, $iPlugin );
        }
 ?>