<?php
  // TO DO: Add comments (I'm lazy right now lol)
  function __autoload( $szClass ) {
    include( getcwd( ) . "/Plugins/" . ucfirst( $szClass ) . ".php" ); 
  }

  class Plugins {
    private static $aPlugins = array( );
    private static $aHooks = array( );
    private static $iNumberOfPlugins = 0;
    private static $iNumberOfHooks = 0;

    public function __construct( ) {
      $iDir = opendir( getcwd( ) . '/Plugins' );

      while( false !== ( $szFileName = readdir( $iDir ) ) ) {
        if( stripos( $szFileName, '.php' ) === false || strcmp( $szFileName, 'RegisterPlugin.php' ) == 0 )
          continue;

        $szName = str_replace( '.php', '', $szFileName );
        array_push( self::$aPlugins, array( 
          'Name' => $szName,
          'FileName' => $szFileName,
          'FilePath' => getcwd( ) . '/Plugins/' . $szFileName
         ) );

        self::$iNumberOfPlugins ++;
        self::AddHook( $szName, true );
      }
    }
    
    public static function AddHook( $szName, $szActivate ) {
      $aHooks = self::$aHooks;
      $iLength = count( $aHooks );

      for( $Iterator = 0; $Iterator < $iLength; $Iterator++ ) {
        if( in_array( $szName, $aHooks[ $Iterator ] ) == true ) {
          if( $aHooks[ $Iterator ][ 'Name' ] !== $szName )
            continue;

          self::$aHooks[ $Iterator ][ 'Activate' ] = 1;
          self::$iNumberOfHooks++;
          return;
        }
      }
      
      array_push( self::$aHooks, array( 
        'Name' => $szName,
        'Activate' => $szActivate
       ) ); 

      self::$iNumberOfHooks ++;
    }
    
    public static function RemoveHook( $szName ) {
      $aHooks = self::$aHooks;
      $iLength = count( $aHooks );
      $szName = str_replace( '.php', '', $szName );

      for( $Iterator = 0; $Iterator < $iLength; $Iterator ++ ) {
        if( $aHooks[ $Iterator ][ 'Name' ] !== $szName )
          continue;

        self::$iNumberOfHooks --;
        self::$aHooks[ $Iterator ][ 'Activate' ] = 0;
      }
    }
    
    public static function RunHook( $szHook ) {
      $aPlugins = self::$aPlugins;
      $aHooks = self::$aHooks;

      for( $Iterator = 0; $Iterator < count( $aHooks ); $Iterator ++ ) {
        $oPlugin = self::GetPluginObject( $aHooks[ $Iterator ][ 'Name' ] );

        if( $aHooks[ $Iterator ][ 'Activate' ] == 0 ) {
          $szHookDefunction = 'Deactivate_' . $szHook;

          $func = is_callable( array( 
            $oPlugin,
            $szHookDefunction
          ) );

          if( !$func ) $oPlugin->Deactivate( );
          else $oPlugin->$szHookDefunction( );

          continue;
        }
        
        $oPlugin_hooks = count( $oPlugin->SetHooks( ) );
        for( $Iterator2 = 0; $Iterator2 < $oPlugin_hooks; $Iterator2 ++ )
          if( strcasecmp( $oPlugin_hooks[ $Iterator2 ], $szHook ) != 0 )
            continue;

          $szHookFunction = 'Activate_' . $szHook;
          $func = is_callable( array( 
            $oPlugin,
            $szHookFunction
          ) );

          if( !$func ) $oPlugin->Deactivate( );
          else $oPlugin->$szHookFunction( );
      }
    }
    
    public static function GetPluginObject( $szName ) {
      $aPlugins = self::$aPlugins;
      $iNumberOfPlugins = count( $aPlugins );

      for( $iIterator = 0; $iIterator < $iNumberOfPlugins; $iIterator ++ ) {
        if( $aPlugins[ $iIterator ][ 'Name' ] !== $szName )
          continue;

        return new $aPlugins[ $iIterator ][ 'Name' ]( );
      }
      
      return null;
    }
    
    public static function GetPluginCount( ) {
      return self::$iNumberOfPlugins;
    }
    
    public static function GetHookCount( ) {
      return self::$iNumberOfHooks;
    }
    
    public static function GetPlugins( ) {
      return self::$aPlugins;
    }
    
    public static function GetHooks( ) {
      return self::$aHooks;
    }
  }
?>
