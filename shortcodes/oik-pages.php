<?php // (C) Copyright Bobbing Wide 2012-2017
if ( defined( 'OIK_PAGES_SHORTCODES_INCLUDED' ) ) return;
define( 'OIK_PAGES_SHORTCODES_INCLUDED', true );
/*

    Copyright 2012-2017 Bobbing Wide (email : herb@bobbingwide.com )

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
oik_require( "includes/bw_posts.php" );
oik_require( "includes/bw_images.inc" );

/** 
 * Return the function to be used to format posts 
 *
 * If the format parameter is specified it uses the dynamically loaded bw_format_as_required() function
 * else it uses the original function bw_format_post()
 * 
 * @param array $atts - shortcode parameters which may include format=specification
 * @return string - function name to be used to format posts
 */
function bw_query_post_formatter( $atts ) {
  $format = bw_array_get( $atts, "format", null );
  if ( $format ) {
    oik_require( "includes/bw_formatter.php" );
    $bw_post_formatter = "bw_format_as_required";
  } else {
    $bw_post_formatter = "bw_format_post";
  }
  return( $bw_post_formatter );
}
 
/**
 * Implement [bw_pages] shortcode 
 * 
 * Possibly the most advanced of the shortcodes in the oik base plugin.
 * This shortcode will list items of your choosing with powerful selection and formatting criteria.
 * 
 * This documentation doesn't do the shortcode justice! **?** 2013/06/17 
 
 * [bw_pages class="classes for bw_block" 
 *   post_type='page'
 *   post_parent 
 *   orderby='title'
 *   order='ASC'
 *   posts_per_page=-1
 *   block=true or false
 *   thumbnail=specification - see bw_thumbnail
 *   customcategoryname=custom category value 
 *   format=formatting string 
 * @param array $atts - shortcode parameters
 * @return string - the generated HTML output 
 */
function bw_pages( $atts = NULL ) {
  $atts['numberposts'] = bw_array_get( $atts, 'numberposts', 10 );
  $posts = bw_get_posts( $atts );
  bw_trace( $posts, __FUNCTION__, __LINE__, __FILE__, "posts" );
  if ( $posts ) {
    $cp = bw_current_post_id();
    $bw_post_formatter = bw_query_post_formatter( $atts );
    foreach ( $posts as $post ) {
      bw_current_post_id( $post->ID );
      $bw_post_formatter( $post, $atts );
    }
    bw_current_post_id( $cp );
    //bw_current_post_id();
    
    bw_clear_processed_posts();
  }
  return( bw_ret() );
}

/** 
 * Syntax hook for [bw_pages]
 * 
 * @see http://codex.wordpress.org/Template_Tags/get_posts
 * Default usage copied on 2012/02/27
*/    
function bw_pages__syntax( $shortcode="bw_pages" ) {
  $syntax = _sc_posts(); 
  $syntax = array_merge( $syntax, _sc_classes() );
  $syntax["format"] = BW_::bw_skv( null, "<i>" . __( "format" , "oik" ) . "</i>", __( "field format string", "oik" ) );
  $syntax["read_more"] = BW_::bw_skv( null, "''|<i>" . __( "string", "oik" ) . "</i>", __( "text for read more button", "oik" ) ); 
  return( $syntax );   
}

/** 
 * Help hook for [bw_pages] shortcode
 */
function bw_pages__help( $shortcode="bw_pages" ) {
  return( __( "Display page thumbnails and excerpts as links", "oik" ) );
}

/**
 * Example hook for [bw_pages] shortcode
 */
function bw_pages__example( $shortcode="bw_pages" ) {
  e( __( "Display sub-pages of the current or selected item.", "oik" ) );
	e( ' ' );
  e( __( "The item may be a page, post or custom post type.", "oik" ) );
	e( ' ' );
  e( __( "The default display is formatted with a featured image, excerpt and a read more link.", "oik" ) );
	e( ' ' );
  $link = "https://www.oik-plugins.com/oik-shortcodes/$shortcode/$shortcode";
  $link = retlink( NULL, $link, sprintf( __( '%1$s help', "oik" ), $shortcode ) );   
  e( sprintf( __( 'For examples visit %1$s', "oik" ), $link ) );  
}

/**
 * Snippet hook for [bw_pages] shortcode
 */
function bw_pages__snippet( $shortcode="bw_pages" ) {
  e( __( "No snippet available", "oik" ) );
} 

