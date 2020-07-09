<?php
class Language {
	private static $szLanguage;
	
	public static function Set( $szLanguage ) {
		self::$szLanguage = $szLanguage;
	}
	
	public function __destruct( ) {
		unset( self::$szLanguage );
	}
	
	private static function LoadPhrases( $szFile ) {
		$iFile = fopen( $szFile, "r" );
		$szData = null;
		
		if( $iFile ) {
			while( $szText = fgets( $iFile ) ) {
				$szData .= $szText;
			}
			
			fclose( $iFile );
		}
		
		return $szData;
	}
	
	public static function GetPhrase( $szPhrase ) {
		$jJson = json_decode( self::LoadPhrases( "Language/" . self::$szLanguage . ".json" ), true );
		if( strlen( $jJson[ $szPhrase ] ) )
			return $jJson[ $szPhrase ];
			
		return "Unknown phrase: " . $szPhrase;
	}
	
	public static function Format( $szText, $szVariable, $szValue ) {
		return str_replace( "{" . $szVariable . "}", $szValue, $szText );
	}
	
	public static function FormatMultiple( $szText, $aArray ) {
		if( is_array( $aArray ) ) {		
			foreach( $aArray as $szKey => $szValue ) {
				$szText = self::Format( $szText, $szKey, $szValue );			
			}
			
			return $szText;
		}
		
		return "Array is not provided!";
	}
}
?>