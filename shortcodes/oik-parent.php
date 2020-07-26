<?php
if ( defined( 'OIK_PARENT_SHORTCODES_INCLUDED' ) ) return;
define( 'OIK_PARENT_SHORTCODES_INCLUDED', true );

/*
    Copyright 2012-2017, 2020 Bobbing Wide (email : herb@bobbingwide.com )

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
 * Display a link to a post
 *
 * Note: get_the_title() may invoke shortcodes so we need to call bw_push() and bw_pop()
 *
 * If the $link is an integer then we format the link ourselves
 * otherwise just bung it out. 
 * 
 * @param ID/string $link - post_ID OR the actual link
 * @param string $class - list of classes to apply to the link
 * 
 */
function bw_post_link( $link=null, $class="bw_post" ) {  
  if ( is_numeric( $link ) ) {
    $url = get_permalink( $link );
    bw_push();
    $title = get_the_title( $link );
    bw_pop();
    if ( empty( $title ) ) {
      $title = sprintf( __( 'Post: %1$s', "oik" ) , $link );
    }
    $link = retlink( $class, $url, $title, null, "id-$link" );
  }
  e( $link );
}  

/**
 * Display a link to the post->parent
 * 
 * Now the question on everyone's mind is - which $post ID do we get when we're working on nested shortcodes? 
 * 
 * @param array $atts - shortcode parameters
 * @param string $content - shortcode content - not expected
 * @param string $tag - shortcode
 * @return string - generated HTML
 */
function bw_parent( $atts=null, $content=null, $tag=null ) {
  $id = bw_array_get( $atts, "id", null );
  if ( $id && ( $id === bw_global_post_id() ) ) {
    $post = bw_global_post();
  } else {
    $post = get_post( $id ); 
  }
  if ( $post && $post->post_parent ) {
    bw_post_link( $post->post_parent, "bw_parent" );   
  }  
  return( bw_ret());
}

/**
 * Help for [bw_parent] shortcode
 */
function bw_parent__help() {
  return( __( "Display a link back to the parent page", "oik" ) );
}

/**
 * Syntax for [bw_parent] shortcode
 */
function bw_parent__syntax( $shortcode="bw_parent" ) {
  $syntax = array( "id" => BW_::bw_skv( null, "<i>". __( "ID", "oik" ) . "</i>", __( "ID from which to find the parent", "oik" ) ) );
  return( $syntax );
}

/** 
 * Returns the correct inner tag given the outer
 * 
 * Note: When formatting a list, if you give it the right class then it can be "de-listed" and appear as a normal list quite easily
 * You can even put commas between items. 
 * But sometimes it's easier to generate simpler HTML
 *
 * @param string $outer - the outer tag for the 'list'
 * @return string - the inner tag - without any <>'s
 */
function bw_inner_tag( $outer ) {
  $inner = bw_array_get( bw_inner_tags(), $outer, null );
  return( $inner );
}
   
/**
 * Return an array of inner tags for selected outer tags
 *
 * Special tags include "o" and "u" - which are abbreviations for "ol" and "ul" respectively.
 *
 */
function bw_inner_tags() {
  $inner_tags = array( "ol" => "li"
                     , "o" => "li"
                     , "ul" => "li"
                     , "u" => "li"
                     , "div" => "div" 
                     , "p" => "span"
                     );
  return( $inner_tags );                     
}

/** 
 * Display a list of links given the post IDs 
 *
 * 
 */ 
function bw_links( $atts=null, $content=null, $tag=null ) {
  $ids = bw_array_get( $atts, "id", null );
  $outer = bw_array_get( $atts, "outer", "ul" ); 
  $inner = bw_array_get( bw_inner_tags(), $outer, null );
  $class = bw_array_get( $atts, "class", "bw_links" );
  
  if ( $ids ) {
     $ids = bw_array( $ids );
     stag( $outer );
     foreach ( $ids as $id ) {
       $post = get_post( $id ); 
       if ( $post ) {
         stag( $inner );
         bw_post_link( $id, $class );
         etag( $inner );
       }  
     }
     etag( $outer );
  } else {
    //bw_parent();
  }  
}                 



