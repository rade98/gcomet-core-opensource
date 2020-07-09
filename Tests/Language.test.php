<?php
	require_once "../Include/Language.php";
	$aArray = array(
		"name" => "Dusan",
			"date" => date( "d.m.Y" )
		);
		
		Language::Set( "English" );
		echo Language::FormatMultiple( Language::GetPhrase( "Hello" ), $aArray );
?>