<?php
  require_once "../Include/Ssh.php";
  
  // It is working ! :D
  $iSSH = new Ssh_Connection( "localhost", 22, "root", "Coban123" );
  if( !$iSSH->OpenConnection( ) ) {
  	echo $iSSH->GetLatestError( );
  } else {
	$iSSH->AddCommand( "cd /home/" );
	$iSSH->AddCommand( "mkdir eee" );
	$iSSH->ExecuteCommands( );
  }
  
  $iSSH->Clear( );
?>
