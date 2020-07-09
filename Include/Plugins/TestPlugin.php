<?php
  class TestPlugin implements RegisterPlugin {
    public function SetHooks( ) {
      return array(
        'IndexHeader', 'IndexContent', 'IndexFooter'
      );
    }

    // Plugin info
    public function PluginInfo( ) {
     return array(
      'Name' => 'Test Plugin',
      'Version' => 1.0
     );
    }
   
    // header
    public function Activate_IndexHeader( ) {
      echo '<h1>This is a Header</h1>';
    }
   
    public function Deactivate_IndexHeader( ) {
      echo 'DEACTIVATE HEADER';
    }
   
    // Content
    public function Activate_IndexContent( ) {
      echo '<p>This is body content</p>';
    }
   
    public function Deactivate_IndexContent( ) {
      echo 'DEACTIVATE CONTENT';
    }

    // Footer
    public function Activate_IndexFooter( ) {
      echo '<h3>This is a Footer</h3>';
    }
   
    public function Deactivate_IndexFooter( ) {
      echo 'DEACTIVATE FOOTER';
    }
   
    public function Deactivate( ) {
      echo '<h1>Plugin system broken!!?!?!?</h1>';
    }
   }
?>