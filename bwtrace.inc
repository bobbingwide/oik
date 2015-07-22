<?php // (C) Copyright Bobbing Wide 2011-2015
if ( function_exists( "_deprecated_file" ) ) {
	_deprecated_file( __FILE__, "2.6", "libs/bwtrace.php", "Please use libs/bwtrace.php" );  
}
echo "<!-- " ;
print_r( debug_backtrace() );
echo " -->" ;
require_once( dirname( __FILE__ ) . '/libs/bwtrace.php' );



