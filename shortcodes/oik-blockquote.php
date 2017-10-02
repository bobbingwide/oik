<?php // (C) Copyright Bobbing Wide 2011, 2015

/** 
 * Implement [bw_blockquote] shortcode
 * 
 * Display a blockquote unaffected by wpautop
 * If the text comes from the $content then we allow shortcode expansion of embedded shortcodes.
 *
 * @param array $atts shortcode attributes
 * @param string $content optional content overriding text= or positional parameter
 * @param string $tag shortcode used
 * @return string the generated blockquote
 */
function bw_blockquote( $atts=null, $content=null, $tag=null ) {
	if ( $content ) {
		$text = bw_do_shortcode( $content );
	} else {
		$text = bw_array_get_from( $atts, "text,0", null ); 
	}
	$class = bw_array_get( $atts, "class", NULL );
	_bw_blockquote( $text, $class );
	return( bw_ret());
}

/**
 * Syntax hook for [bw_blockquote]
 *
 */
function bw_blockquote__syntax( $shortcode="bw_blockquote" ) {
  $syntax = array( "text,0" => BW_::bw_skv( "", "<i>" . __( "text", "oik" ) . "</i>", __( "Text for the blockquote", "oik" ) )
                 ,  "class"=> BW_::bw_skv( "", "<i>" . __( "class names", "oik" ) . "</i>", __( "CSS class names", "oik" ) )
                 );
  return( $syntax );
}
  
