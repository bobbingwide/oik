<?php 
/*

    Copyright 2014-2019 Bobbing Wide (email : herb@bobbingwide.com )

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
function bw_count( $atts=null, $content=null, $tag=null ) {
  $post_type = bw_array_get_from( $atts, "post_type,0", "post" );
  $count = wp_count_posts( $post_type );
  $status = bw_array_get_from( $atts, "status,1", "publish" );
  span( "bw_count $post_type $status");
  if ( property_exists( $count, $status ) ) { 
    e( $count->$status );
  } elseif ( property_exists( $count, "publish" ) ) { 
    e( $count->publish );
  } else {
    e( "No count available for $post_type $status" );
  }
  epan();
  bw_trace2( $count, "count", false, BW_TRACE_VERBOSE );
  return( bw_ret() );
}

/**
 * Help for [bw_count] shortcode
 */
function bw_count__help( $shortcode="bw_count" ) {
  return( __( "Count posts for the selected post type", "oik" ) );
}

/**
 * Syntax for [bw_count] shortcode
 */
function bw_count__syntax( $shortcode="bw_count" ) {
  $syntax = array( "post_type,0" => BW_::bw_skv( "post", "<i>" . __( "post type", "oik" ) . "</i>", __( "Post type to count", "oik" ) )
                 , "status,1" => BW_::bw_skv( "publish", "pending|draft|auto-draft|future|private|trash|inherit", __( "Post status", "oik" ) )
                 );
  return( $syntax );
} 
	

