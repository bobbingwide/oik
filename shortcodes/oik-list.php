<?php 
/*

    Copyright 2012-2016 Bobbing Wide (email : herb@bobbingwide.com )

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
 * Start a list
 *
 * Defaulting to "uo=u" for an unordered list
 * the other options are for an ordered list  or a comma separated
 *
 * @TODO For an ordered list, if the optional start= parameter is included then the list numbering starts from the value given
 * @TODO Document how to use comma separated - which makes use of the span tag
 
 * BTW. This function has nothing to do with http://en.wikipedia.org/wiki/Bandra%E2%80%93Worli_Sea_Link
 *
 * @param array $atts - shortcode parameters including the optional uo= parameter
 * @return string - list type
 */
function bw_sl( $atts=null, $start=1 ) { 
  $uo = strtolower( bw_array_get( $atts, "uo", "u" ) ) ;  
  $class = bw_array_get( $atts, 'class', 'bw_list' );
  switch ( $uo ) {
    case "u":
    case "ul":
      sul( $class );
      break;
    case "o":
    case "ol":
			//bw_trace2();
			//bw_backtrace();
			$extra = kv( "start", $start );
      sol( $class, null, $extra);
      break;
    case "d":
    case "dl":
      stag( "dl", $class );
      break;
    default:
      span( $class );
  }  
  return( $uo );
}

/** 
 * End a list 
 *
 * Matching function for bw_sl()
 *
 * @param string - the "list" type
 */  
function bw_el( $uo ) {
  switch ( $uo ) {
    case "u":
    case "ul":
      eul();
      break;
    case "o":
    case "ol":
      eol();
      break;
    case "d":
    case "dl":
      etag( "dl" );
      break;
    default:
      epan();
  }  
}

/**
 * Display a link to a post in a list item
 *
 * @param ID $id - the post ID, which is expected to exist
 *
 */
function bw_list_id( $id ) {
  stag( "li" );
  oik_require( "shortcodes/oik-parent.php" );
  bw_post_link( $id );
  //$title = get_the_title( $id );
  //alink( null, get_permalink( $id ), $title );
  etag( "li");
} 

/**
 * Display a "simple" list
 * 
 * Display a simple list of posts taking into account the preferences in the $atts array.
 * 
 * List types supported are:
 * 
 * Type | Means
 * ---- | -------------------
 *  ul | unordered list ( default )
 *  ol | ordered list
 *  ,  | comma separated - ie. a really simple list
 * 
 * 
 * @param array $posts - array of posts
 * @param array $atts - shortcode parameters 
 * 
 */
function bw_simple_list( $posts, $atts ) {  
  oik_require( "shortcodes/oik-parent.php" );
  $uo = bw_sl( $atts );
  $inner = bw_inner_tag( $uo );
  $count = 0;
  foreach ( $posts as $post ) {
    ///bw_format_list( $post, $atts );
    if ( $inner ) {
      stag( $inner );
    } elseif ( $count ) {
      e( "," );
      e( "&nbsp;" );
    }
    bw_post_link( $post->ID );
    if ( $inner ) {
      etag( $inner );
    }  
    $count++;
  }
  bw_el( $uo );
}

/**
 *
 * Implement [bw_list] shortcode
 * 
 * List sub-pages of the current or selected page - in a simple list 
 * This is similar to [bw_pages] but it produces a simple list of links to the content type
 *
 *
 * [bw_list class="classes for the list" 
 *   post_type='page'
 *   post_parent=0 
 *   orderby='title'
 *   order='ASC'
 *   posts_per_page=-1

 *   thumbnail=specification - see bw_thumbnail()
 *   customcategoryname=custom category value 
 * 
 * You can also use all of the other parameters to get_post
 * such as meta_key= meta_value= post_type= etcetera
 * If you want to list the current item then remember to exclude=-1 
 */
function bw_list( $atts=null, $content=null, $tag=null ) {
  oik_require( "includes/bw_posts.inc" );
  $posts = bw_get_posts( $atts );
  $atts['thumbnail'] = bw_array_get( $atts, "thumbnail", "none" );
  $ol = bw_sl( $atts );
  foreach ( $posts as $post ) {
    bw_format_list( $post, $atts );
  }
  bw_el( $ol );
  return( bw_ret() );
} 

/**
 * Syntax hook for [bw_list] shortcode
 */
function bw_list__syntax( $shortcode="bw_list" ) {
  $syntax = _sc_posts(); 
  $syntax['numberposts'] = bw_skv( "-1", "numeric", "number of items to list. -1=list all" );
  $syntax['thumbnail'] = bw_skv( "none", "thumbnail|medium|large|full|nnn|wxh", "image size" ); 
  $syntax['uo'] = bw_skv( "u", "o", "Display unordered or ordered list" );
  return( $syntax );
}


