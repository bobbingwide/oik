<?php // (C) Copyright Bobbing Wide 2012-2015
if ( function_exists( "_deprecated_file" ) ) {
	_deprecated_file( __FILE__, "2.6", "bwtrace_boot.php", "Please use libs/bwtrace_boot.php" );  
}
echo "<!-- " ;
print_r( debug_backtrace() );
echo " -->" ;
require_once( __DIR__ . '/libs/bwtrace_boot.php' );


