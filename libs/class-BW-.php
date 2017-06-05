<?php // (C) Copyright BobbingWide 2017
if ( !defined( "CLASS_BW__INCLUDED" ) ) {
define( "CLASS_BW__INCLUDED", "3.2.0" );

/**
 * More HTML output library functions
 * 
 * Library: class-BW-
 * Depends: 
 * Provides: BW_ class
 *
 * These functions are clones of functions in libs\bobbfunc.php
 * The original functions may be deprecated in the future.
 * To use the new functions prefix the original function call with BW_::
 * and change any translatable string parameter to a translated string
 * 
 * e.g. 
 * p( "This page left intentionally blank" );
 * becomes
 * BW_::p( __( "This page left intentionally blank", "oik" ) );
 */

class BW_ {

	/**
	 * Output a paragraph of translated text
	 *
	 * @param string $text - translated text - expected to be non-null
	 * @param string $class - CSS class(es)
	 * @param string $id - CSS ID
	 */
	static function p( $text=null, $class=null, $id=null ) {
		sp( $class, $id );
		if ( !is_null( $text ) ) {
			e( $text );
		}
		etag( "p" );
	}
	
	/**
	 * Output a link
	 *
	 * @param string $class optional CSS class(es)
	 * @param string $url URL
	 * @param string $linktori translated link text or image 
	 * @param string $alt translated alternate text	or null
	 * @param string $id optional CSS id
	 * @param string $extra additional HTML
	 */
	static function alink( $class=null, $url, $linktori=null, $alt=null, $id=null, $extra=null ) {
		$link = retlink( $class, $url, $linktori, $alt, $id, $extra );
		e( $link );
	}
} /* end class */

} /* end if !defined */

