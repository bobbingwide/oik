<?php // (C) Copyright Bobbing Wide 2010-2017
/**
 * Implement [bw] shortcode
 *
 * 
 */
function bw_bw( $atts=null, $content=null, $tag=null ) {
	oik_require( "bobbfunc.inc" );
	e( bw( $atts ) );
	return( bw_ret() );
}



function bw__help( $shortcode="bw" ) {
	if ( $shortcode == "bw" ) {
		return( __( "Expand to the logo for Bobbing Wide", "oik" ) );
		
	}
}


function bw__syntax( $shortcode="bw" ) {
	$syntax = array( "cp" => bw_skv( null, "h", __( "Class name prefix", "oik") ) );
	return( $syntax );
}



/**
 * Show an example of the [bw] shortcode
 * 
 * Note: This requires bobbfunc.inc - not the bobbfunc library 
 */
function bw__example( $shortcode="bw" ) {
  if ( $shortcode == "bw" ) {
	
	  oik_require( "bobbfunc.inc" );
    br( "e.g. " );
    e( bw() );
  }
}
 
