<?php
    class Config {
        public static function LoadConfig( ) {
            $iFile = fopen( "Config.json", "r" );
            $szData = null;

            if( $iFile ) {
                while( $szText = fgets( $iFile ) ) {
                    $szData .= $szText;
                }

                fclose( $iFile );
            }

            return json_decode( $szData );
        }

        public static function WriteConfig( $config ) {
            file_put_contents("Config.json", $config);
        }
    }
?>
