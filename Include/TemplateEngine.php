<?php
	require "Smarty/libs/Smarty.class.php";

	class TemplateEngine extends Smarty {
		public function Configure( ) {
			$iSmarty = new Smarty( );

			$iSmarty->setTemplateDir( "../Template" );
			$iSmarty->setCompileDir( "../Cached" );

			return $iSmarty;
		}
	}
?>