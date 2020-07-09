<?php
	require_once "../Include/Login.php";
	
	// Test Login
	$iLogin = new Login( "milutinke@gmx.com", "test" );
	echo $iLogin->DoLogin( ) ? "Logged in" : "Not logged in";
        echo "<br />" . $iLogin->GetLoggedUserID( );

	// Output:
	// Logged In
	// 1
?>
