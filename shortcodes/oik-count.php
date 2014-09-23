<?php 
/*

    Copyright 2014 Bobbing Wide (email : herb@bobbingwide.com )

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
  //if ( $post_type ) {
    $count = wp_count_posts( $post_type );
  //}
  $status = bw_array_get_from( $atts, "status,1", "publish" );
  
  if ( property_exists( $count, $status ) ) { 
    e( $count->$status );
  } elseif ( property_exists( $count, "publish" ) ) { 
    e( $count->publish );
  } else {
    e( "No count available for $post_type $status" );
  } 
  bw_trace2( $count, "count", false );
  
  

  return( bw_ret() );
}
