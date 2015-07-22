<?php // (C) Copyright Bobbing Wide 2011-2015


/**
 * Display a cite tag unaffected by wpautop 
 */
 
function bw_cite( $atts = NULL ) {
  $text = bw_array_get( $atts, "text", NULL ); 
  $class = bw_array_get( $atts, "class", NULL );
  _bw_cite( $text, $class );
  return( bw_ret());
}



function bw_cite__syntax( $shortcode="bw_cite" ) {
  $syntax = array( "text" => bw_skv( "", "<i>text</i>", "Text for the citation" )
                 ,  "class"=> bw_skv( "", "<i>class names</i>", "CSS class names" )
                 );
  return( $syntax );
}

