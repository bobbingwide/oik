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
 * 

 */

class BW_ {

	/**
	 * Outputs a paragraph of translated text
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
	 * Outputs a link
	 * 
	 * _alink() and alink() both map to BW_::alink()
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

	/**
	 * Outputs a menu header
	 * 
	 * Note: Completely removed the link to oik  
	 *
	 * @param string $title - title for the box
	 * @param string $class - class for the box 
	 */
	static function oik_menu_header( $title="Overview", $class="w70pc" ) {
		oik_enqueue_scripts();
		sdiv( "wrap" ); 
		h2( $title ); 
		scolumn( $class );
	}

	/**
	 * Outputs a postbox widget on the admin pages 
	 *
	 * @param string $class additional CSS classes for the postbox
	 * @param string $id Unique CSS ID
	 * @param string $title Translated title
	 * @param string $callback Callable function implementing the post box contents
	 */
	static function oik_box( $class=null, $id=null, $title=null, $callback='oik_callback' ) {
		if ( $id == null ) {
			$id = $callback;
		}  
		sdiv( "postbox $class", $id );
		sdiv( "handlediv", null, kv( 'title', __( "Click to toggle" ) ) );
		br();
		ediv();
		h3( $title, "hndle" );
		sdiv( "inside" );
		call_user_func( $callback );
		ediv( "inside" );
		ediv( "postbox" );
	}

	/**
	 * Appends some non-translatable text to translated text
	 *
	 * This is similar to using sprintf( __( "translatable_text %1$s", $non_translatable_text ) );
	 * BUT it doesn't require the translator to have to worry about the position of the variable
	 * AS this isn't in the text they translate.
	 *
	 * Note: The non-translatable text is expected to begin with a space character.
	 * It is also possible to append two translated strings.
	 * 
	 * @param string $translated_text - text that's been translated
	 * @param string $non_translatable_text - text that's not translated
	 * @return string concatenated strings
	 */
	static function bwtnt( $translated_text, $non_translatable_text ) {
		$tnt = $translated_text;
		$tnt .= $non_translatable_text;
		return( $tnt );
	}

	/**
	 * Create a field label
	 *
	 * Expects the text to have already been translated. 
	 * 
	 * @param string $name - field name
	 * @param string $text - label to display
	 * @return string - the HTML label tag
	 */
	static function label( $name, $text ) {
		$lab = "<label for=\"";
		$lab.= $name;
		$lab.= "\">";
		$lab.= $text;
		$lab.= "</label>";
		return $lab;
	}

	/**
	 *
	  
	/**
	 * Form a text field
	 *
	 * @param string $name - the name of the field
	 * @param integer $len - the length of the field
	 * @param string $text - the label for the field
	 * @param string $value - the value of the field
	 * @param string $class - CSS class name
	 * @param string $extras - the extras passed to itext() is expected to be a string
	 * @param array $args - even more options
	 * @TODO accept array so that #hint can be applied 2014/11/28  
	 * 
	 */
	static function bw_textfield( $name, $len, $text, $value, $class=null, $extras=null, $args=null ) {
		$lab = self::label( $name, $text );
		if ( $value === null ) {
			$value = bw_array_get( $_REQUEST, $name, null );
		}
		$itext = itext( $name, $len, $value, $class, $extras, $args ); 
		bw_tablerow( array( $lab, $itext ) );
		return;
	}


	/**
	 * Create a textfield for an array options field 
	 *
	 * @param string $name field name
	 * @param string $text field label
	 * @param array $array 
	 * @param integer $index
	 * @param integer $len
	 * @param string $class
	 * @param string $extras
	 */
	static function bw_textfield_arr( $name, $text, $array, $index, $len, $class=null, $extras=null ) {
		$name_index = $name.'['.$index.']';
		$value = bw_array_get( $array, $index, NULL );
		self::bw_textfield( $name_index, $len, $text, $value, $class, $extras );
	}
	
	

	/**
	 * Form an "email" field
	 *
	 * @param string $name - the name of the field
	 * @param integer $len - the length of the field
	 * @param string $text - the label for the field
	 * @param string $value - the value of the field
	 * @param string $class - CSS class name
	 * @param string $extras - the extras passed to itext() is expected to be a string
	 */
	static function bw_emailfield( $name, $len, $text, $value, $class=null, $extras=null ) {
		$lab = self::label( $name, $text );
		if ( $value === null ) {
			$value = bw_array_get( $_REQUEST, $name, null );
		}
		$itext = iemail( $name, $len, $value, $class, $extras ); 
		bw_tablerow( array( $lab, $itext ) );
		return;
}

	/**
	 * Create an emailfield for an array options field 
	 *
	 * @param string $name field name
	 * @param string $text field label
	 * @param array $array 
	 * @param integer $index
	 * @param integer $len
	 * @param string $class
	 * @param string $extras
	 */
	static function bw_emailfield_arr( $name, $text, $array, $index, $len, $class=null, $extras=null ) {
		$name_index = $name.'['.$index.']';
		$value = bw_array_get( $array, $index, NULL );
		self::bw_emailfield( $name_index, $len, $text, $value, $class, $extras );
	}

} /* end class */

} /* end if !defined */

