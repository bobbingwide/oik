<?php // (C) Copyright Bobbing Wide 2015

/** 
 * Implement [bw_blockquote] shortcode
 * 
 * Display a blockquote unaffected by wpautop
 * 
 */
function bw_blockquote( $atts=null, $content=null, $tag=null ) {
	$text = bw_array_get( $atts, "text", $content ); 
	$class = bw_array_get( $atts, "class", NULL );
	_bw_blockquote( $text, $class );
	return( bw_ret());
}

if ( false ) {
/**
 * Display a blockquote
 *
 */
function _bw_blockquote( $text, $class=NULL, $id=NULL ) {
	stag( "blockquote", $class, $id ) ;
	e( $text );
	etag( "blockquote" );    
}
}


                   
function bw_blockquote__syntax( $shortcode="bw_blockquote" ) {
  $syntax = array( "text" => bw_skv( "", "<i>text</i>", "Text for the blockquote" )
                 ,  "class"=> bw_skv( "", "<i>class names</i>", "CSS class names" )
                 );
  return( $syntax );
}
  
