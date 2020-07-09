<?php
	require "../Include/TemplateEngine.php";

	$iTemplate = new TemplateEngine( );
    $iTemplate->setTemplateDir( "../Template" );
	$iTemplate->setCompileDir( "../Cached" );
	
	$iTemplate->assign( "Test", "eeee" );
	$iTemplate->display( "Test.tpl" );
?>