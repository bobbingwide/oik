<?php // (C) Copyright Bobbing Wide 2012-2015
if ( function_exists( "_deprecated_file" ) ) {
	_deprecated_file( __FILE__, "2.6", "libs/bwtrace_boot.php", "Please use libs/bwtrace_boot.php" );  
}
echo "<!-- " ;
print_r( debug_backtrace() );
echo " -->" ;
require_once( dirname( __FILE__ ) . '/libs/bwtrace_boot.php' );
