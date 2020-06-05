<?php
require_once( "config.php") ;
shell_exec( "cd ".settings::$marker_tools." && echo emulator | ./start_avd.sh") ;
?>
