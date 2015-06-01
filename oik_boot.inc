<?php // (C) Copyright Bobbing Wide 2012-2015
if ( function_exists( "_deprecated_file" ) ) {
        _deprecated_file( __FILE__, "2.6", "oik_boot.inc", "Please use oik_boot.php" ); 
         
}
var_dump( debug_backtrace() );
require_once( dirname( __FILE__ ) . '/oik_boot.php' );

