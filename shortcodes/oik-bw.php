<?php // (C) Copyright Bobbing Wide 2010-2017
/**
 * Implements [bw] shortcode
 *
 * @param array $atts shortcode parameters
 * @param string $content - not expected
 * @param string $tag 
 * @return string generated HTML
 */
function bw_bw( $atts=null, $content=null, $tag=null ) {
	oik_require( "bobbfunc.inc" );
	e( bw( $atts ) );
	return( bw_ret() );
}

/**
 * Help for [bw] shortcode
 * @param string $shortcode
 * @return string translated help for shortcode
 */
function bw__help( $shortcode="bw" ) {
	if ( $shortcode == "bw" ) {
		return( __( "Expand to the logo for Bobbing Wide", "oik" ) );
	}
}

/**
 * Syntax for [bw] shortcode
 *
 * @param string $shortcode
 * @return array
 */
function bw__syntax( $shortcode="bw" ) {
	$syntax = array( "cp" => BW_::bw_skv( null, "h", __( "Class name prefix", "oik") ) );
	return $syntax ;
}

/**
 * Show an example of the [bw] shortcode
 * 
 * Note: This requires bobbfunc.inc - not the bobbfunc library 
 * 
 * @param string $shortcode
 */
function bw__example( $shortcode="bw" ) {
	if ( $shortcode == "bw" ) {
		oik_require( "bobbfunc.inc" );
		br( __( "e.g. ", "oik" ) );
		e( bw() );
	}
}
 
