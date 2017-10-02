<?php // (C) Copyright Bobbing Wide 2011-2017


/**
 * Display a cite tag unaffected by wpautop 
 */
 
function bw_cite( $atts = NULL ) {
  $text = bw_array_get( $atts, "text", NULL ); 
  $class = bw_array_get( $atts, "class", NULL );
  _bw_cite( $text, $class );
  return( bw_ret());
}

/**
 * Syntax for [bw_cite] shortcode
 */
function bw_cite__syntax( $shortcode="bw_cite" ) {
  $syntax = array( "text" => BW_::bw_skv( "", "<i>" . __( "text", "oik" ) . "</i>", __( "Text for the citation", "oik" ) )
                 ,  "class"=> BW_::bw_skv( "", "<i>" . __( "class names", "oik" ) . "</i>", __( "CSS class names", "oik" ) )
                 );
  return( $syntax );
}

