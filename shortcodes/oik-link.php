<?php // (C) Copyright Bobbing Wide 2013,2014
/**
 * Implement [bw_link] shortcode for a link to a post or an external URL
 * 
 * @param array $atts - array of shortcode parameters
 * @param string $content - 
 * @param string $tag -
 * @return string - the expanded shortcode
 */
function bw_link( $atts=null, $content=null, $tag=null ) {
  $id = bw_array_get_from( $atts, "id,0", null );
  $class = bw_array_get( $atts, "class", "bw_link" );
  $text = bw_array_get( $atts, "text", null );
  $title = bw_array_get( $atts, "title", null );
  if ( $id && is_numeric( $id ) ) {
    $url = get_permalink( $id );
    if ( !$text ) {
      $text = get_the_title( $id );
    }
  } else { 
    $url = bw_array_get_from( $atts, "href,url,src,link", $id );
  }  
  alink( $class, $url, $text, $title );
  return( bw_ret()); 
}

/**
 * Help hook for [bw_link] shortcode 
 */
function bw_link__help( $shortcode="bw_link" ) {
  return( "Display a link to a post." );
}

/**
 * Syntax hook for [bw_link] shortcode
 */
function bw_link__syntax( $shortcode="bw_link" ) {
  $syntax = array( "id" => bw_skv( "id", "<i>ID</i>|<i>URL</i>", "ID of the post to link to or external URL" )
                 , "text" => bw_skv( "<i>post title</i>", "<i>text</i>", "Text for the link" )
                 , "title" => bw_skv( "<i>post title</i>", "<i>tool tip string</i>", "Tool tip text" )
                 , "href,url,src,link" => bw_skv( null, "<i>href</i>", "URL to link to" )
                 );
  $syntax += _sc_classes();
  return( $syntax );
}

/**
 * Return a post ID for bw_link example
 * 
 * If the global post ID is not set try for a recently published post ID#
 *
 */  
function _bw_get_an_id() {
  oik_require( "includes/bw_posts.inc" );
  $id = bw_global_post_id();
  if ( !$id ) {
    $posts = wp_get_recent_posts( array( "numberposts" => 1, "post_status" => "published" ) );
    $post = bw_array_get( $posts, 0, null );
    if ( $post )
      $id = $post['ID'];
      
    // bw_trace2( $posts );  
  } 
  return( $id );
}

/**
 * Example hook for [bw_link] shortcode
 */
function bw_link__example( $shortcode="bw_link" ) {
  $id = _bw_get_an_id();
  $text = "Example: Display a link to post with ID=$id";
  $example = $id;
  bw_invoke_shortcode( $shortcode, $example, $text );
} 

/**
 * Snippet hook for [bw_link] shortcode
 */                 
function bw_link__snippet( $shortcode="bw_link" ) {
  _sc__snippet( $shortcode, _bw_get_an_id() );
}                   
