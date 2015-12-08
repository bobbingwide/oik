<?php // (C) Copyright Bobbing Wide 2015
if ( !defined( "OIK_AUTOLOAD_LOADED" ) ) {
define( "OIK_AUTOLOAD_LOADED", "0.0.0" );



/**
 * Autoload library functions
 *
 * Library: oik-autoload
 * Provides: oik-autload
 * Type: 
 *
 * Implements logic to enable PHP classes to be autoloaded
 * taking into account the libraries that are being used.
 * 
 */
 
function oik_require_class( $class, $args=null ) {
	bw_trace2();
	$oik_autoload = oik_autoload();
	bw_trace2( $oik_autoload );
	$oik_autoload->autoload( $class );
	bw_trace2( "done?" );


}

/**
 *
 
 * The fact that you invoke oik_require_lib( "oik_autoload" ); 
 * should be enough to tell the autoload library that you'll be using autoloading for your classes
 * but I think it's better to implicitely invoke oik_autoload() to instantiate the logic
 */

function oik_autoload() {

	if ( !class_exists( "OIK_Autoload" ) ) {
		//echo "Loading OIK_Autoload" ;
		oik_require_file( "class-oik-autoload.php", "oik-autoload" );
	}
	if ( class_exists( "OIK_Autoload" ) ) {
		$oik_autoload = OIK_Autoload::instance();
	} else {
		bw_trace2( "Class OIK_Autoload does not exist" );
		die();
	}
	return( $oik_autoload );

}


 
 
 
 
 

} /* end !defined */
 
