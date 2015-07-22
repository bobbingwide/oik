<?php // (C) Copyright Bobbing Wide 2012-2015
if ( function_exists( "_deprecated_file" ) ) {
	_deprecated_file( __FILE__, "2.6", "libs/oik_boot.php", "Please use libs/oik_boot.php" ); 
}
echo "<!-- " ;
var_dump( debug_backtrace() );
echo " -->";
require_once( __DIR__ . '/libs/oik_boot.php' );

