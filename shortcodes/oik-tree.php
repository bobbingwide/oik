<?php // (C) Copyright Bobbing Wide 2012
if ( defined( 'OIK_TREE_SHORTCODES_INCLUDED' ) ) return;
define( 'OIK_TREE_SHORTCODES_INCLUDED', true );

/*

    Copyright 2012 Bobbing Wide (email : herb@bobbingwide.com )

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
oik_require( "includes/bw_posts.inc" );

/**
 * format the tree - as a nested list
 * these functions are called recursively
 * to create trees of the children for each page
 */
function bw_format_tree( $post, $atts ) {
  stag( "li" );
  $url= get_permalink($post->ID);
  $title = $post->post_title; 
  alink( "bw_tree", $url, $title ); 
  $atts['post_parent'] = $post->ID;
  bw_tree_func( $atts );
  etag( "li" );
} 

/** 
 * build and format a tree
 */
function bw_tree_func( $atts ) {  
  $posts = bw_get_posts( $atts );
  if ( count( $posts ) ) {
    stag( "ul" );
    foreach ( $posts as $post ) {
      bw_format_tree( $post, $atts );
    }
  etag( "ul" );
  }
}

/**
 * Implements [bw_tree] shortcode
 *
 * Create a simple tree of the 'pages' under the selected id
 * We default the ordering to match the menu order of the pages
 * The default tree starts from the current 'post'
 * 
 * If post_parent=" " or post_parent=. then we use the current post's parent
 * If you want to include the current post in the tree then add exclude=-1
 */
function bw_tree( $atts = NULL ) {
  $atts['orderby'] = bw_array_get($atts, "orderby", "menu_order" );
  $atts['order'] = bw_array_get( $atts, "order", "ASC" );
  $atts['post_parent'] = bw_array_get( $atts, "post_parent", null );
  //bw_trace2( "!{$atts['post_parent']}!", "derr" ); 
  switch ( $atts['post_parent'] ) {
    case null:
      $atts['post_parent'] = bw_global_post_id();
      break;
    
    case " ":
    case ".":
      $post = bw_global_post();
      if ( $post ) {
        $atts['post_parent'] = $post->post_parent;
      }
      break;
    default:
      // Assume it's an integer and carry on.
  }     
  bw_tree_func( $atts );
  return( bw_ret() );
}


/** 
 * bw_tree doesn't currently support classes
*/
function bw_tree__syntax( $shortcode="bw_tree" ) {
  $syntax = _sc_posts(); 
  // $syntax = array_merge( $syntax, _sc_classes() );
  return( $syntax );   
}
