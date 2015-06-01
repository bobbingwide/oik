<?php // (C) Copyright Bobbing Wide 2011-2015
if ( function_exists( "_deprecated_file" ) ) {
        _deprecated_file( __FILE__, "2.6", "bwtrace.php", "Please use bwtrace.php" );  
}
print_r( debug_backtrace() );
require_once( dirname( __FILE__ ) . '/bwtrace.php' );



