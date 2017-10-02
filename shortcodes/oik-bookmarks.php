<?php
/*
    Copyright 2011-2017 Bobbing Wide (email : herb@bobbingwide.com )

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
 * Wrapper to wp_list_bookmarks() 
 *
 * which replaces get_links()
 */
function bw_bookmarks( $atts = NULL ) {
  // Copy the atts from the shortcode to create the array for the query
  // removing the class and title parameter that gets passed to bw_block()
 
  $attr = $atts;
  //bw_trace( $atts, __FUNCTION__, __LINE__, __FILE__, "atts" );
  //bw_trace( $attr, __FUNCTION__, __LINE__, __FILE__, "attr" );
  /* Set default values if not already set */
  
  $attr['limit'] = bw_array_get( $attr, "numberposts", -1 );
  $attr['orderby'] = bw_array_get( $attr, "orderby", "name" );
  $attr['order'] = bw_array_get( $attr, "order", "ASC" );
  $attr['category_name'] = bw_array_get( $attr, "category_name", NULL );
  // $attr['exclude'] = bw_array_get( $attr, "exclude", $GLOBALS['post']->ID );
  $attr['echo'] = 0;
  
  //bw_trace( $attr, __FUNCTION__, __LINE__, __FILE__, "attr" );
  $posts = wp_list_bookmarks( $attr );
  //bw_trace2( $posts, "bw_bm_posts");
  return( $posts );
}

/**
 * 
 * Syntax help for [bw_bookmarks] shortcode
 *
 * Note: The parameters to wp_list_bookmarks are different to _sc_posts 
 * And what are we doing with numberposts? 
 *
 */
function bw_bookmarks__syntax( $shortcode="bw_bookmarks" ) {
  $syntax = array( "numberposts" => BW_::bw_skv( -1, __( "number", "oik" ), __( "Number of bookmarks to return. -1=unlimited", "oik" ) )
                 , "orderby" => BW_::bw_skv( "name", "", __( "Sort by field", "oik" ) )
                 , "order" => BW_::bw_skv( "ASC", "DESC", __( "Sort order", "oik" ) )
                 , "category_name" => BW_::bw_skv( "", "<i>" . __( "category name", "oik" ) . "</i>", __( "Category name", "oik" ) )
                 );
  // $syntax = array_merge( _sc_posts(), $syntax );
  return( $syntax );
}


function bw_bookmarks__example( $shortcode="bw_bookmarks" ) {
  bw_invoke_shortcode( $shortcode, 'category_name=blogroll', "To display the links in the Blogroll" );
}  
