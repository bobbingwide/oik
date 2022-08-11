<?php
/*
    Copyright 2012-2014 Bobbing Wide (email : herb@bobbingwide.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2,
    as published by the Free Software Foundation.

    You may NOT assume that you can use any other version of the GPL.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    The license for this software can likely be found here:
    http://www.gnu.org/licenses/gpl-2.0.html

*/

/** 
 * Implement the [bw_wtf] shortcode 
 * 
 * Return the raw content fully escaped but with unexpanded shortcodes of the current post 
 * The shortcode now supports different events to trigger the display of the "wtf" content
 * with different effects to display it: slideToggle or Toggle
 *
 * Note: In certain situations this shortcode will not be able to determine the correct content.
 * For example the shortcode is being expanded in 'content' being passed directly to do_shortcode().
 * In this case the content of the global $post variable will be displayed rather than the content passed to do_shortcode().
 *
 * @param mixed $atts - parameters to the shortcode 
 * @param string $content - alternative way of getting content 
 * @param string $tag 
 * @return string the "raw" content - that could be put through WP-syntax
 */
function bw_wtf( $atts=null, $content=null, $tag=null ) { 
  if ( $content ) {
      $escaped_content = esc_html( $content );
  } else {     
    global $post;
    bw_trace2( $post, "post" ); 
    if ( $post ) {
      $escaped_content = esc_html( $post->post_content );
    } else {
      $escaped_content = "[bw_wtf] - nothing to see";
    }
  }
  $event = bw_array_get_from( $atts, "event,0", "hover" );
  $effect = bw_array_get_from( $atts, "effect,1", "slideToggle" );
  /* translators: %1 Event eg Hover, %2 Effect eg slideToggle */
  $text = bw_array_get_from( $atts, "text,2", sprintf( __( '%1$s to %2$s source', "oik" ), $event, $effect ) );
  oik_require( "includes/bw_jquery.inc" );
  bw_jquery_af( "div.bw_wtf", $event , "p.bw_wtf", $effect );
  sdiv( "bw_wtf" );
  BW_::p( $text );
  stag( 'p', "bw_wtf", null, 'lang="HTML" escaped="true" style="display:none;"' );
  $escaped_content = str_replace(array( "[", "]" ), array( "&#091;", "&#093;" ), $escaped_content ); 
	//$escaped_content = str_replace( "&#8211;", "&#045;&#045;", $escaped_content );
	$escaped_content = str_replace( "-", "&#045;", $escaped_content );
  $escaped_content = str_replace( "\n", "", $escaped_content );
  $escaped_content = str_replace( "\r", "", $escaped_content );
  e( $escaped_content );
  etag( "p" );
  ediv();
  return( bw_ret() );
}

/**
 * Syntax hook for [bw_wtf] shortcode
 */
function bw_wtf__syntax( $shortcode="bw_wtf" ) {
  $syntax = array( "event" => BW_::bw_skv( "hover", "click", __( "Event to trigger the revealing", "oik" ) )
                 , "effect" => BW_::bw_skv( "slideToggle", "toggle", __( "Method used to reveal the content", "oik" ) )
                 , "text" => BW_::bw_skv( sprintf( __( '%1$s to %2$s source', "oik" ), "hover", "slideToggle" ), "<i>" . __( "text", "oik" ) . "</i>", __( "Text above the hidden content", "oik" ) )
                 );
  return( $syntax );
}
