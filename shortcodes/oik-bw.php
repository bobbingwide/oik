<?php // (C) Copyright Bobbing Wide 2010-2015
/**
 * Implement [bw] shortcode
 *
 * 
 */
function bw_bw( $atts=null, $content=null, $tag=null ) {
	oik_require( "bobbfunc.inc" );
	e( bw() );
	return( bw_ret() );
}
 
