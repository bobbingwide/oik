<?php // (C) Copyright Bobbing Wide 2012-2017

/**
 * Implements the [bw_iframe] shortcode 
 *
 * @param array $atts - parameters to the shortcode
 * @param string $content - content for the shortcode
 * @param string $tag - shortcode tag
 * @return string - the expanded <iframe> tag
 *
 * for something like this. 
 
<iframe src="https://madmimi.com/signups/67172/iframe_embed" scrolling="no" frameborder="0" height="294" width="245"></iframe>

 */
function bw_iframe( $atts=null, $content=null, $tag=null ) {    
  $url = bw_array_get( $atts, "url", null );
  $src = bw_array_get( $atts, "src", $url );
  $width = bw_array_get( $atts, "width", null );
  $height = bw_array_get( $atts, "height", null );
  $scrolling = bw_array_get( $atts, "scrolling", "no" );
  $frameborder = bw_array_get( $atts, "frameborder", 0 );
  $type = bw_array_get( $atts, "type", "text/html" );
  $class = bw_array_get( $atts, "class", null );
  $id = bw_array_get( $atts, "id", null );
  $parms = kv( "src", $src );
  $parms .= kv( "width", $width );
  $parms .= kv( "height", $height );  
  $parms .= kv( "type", $type );
  $parms .= kv( "scrolling", $scrolling );
  $parms .= kv( "frameborder", $frameborder );
  stag( "iframe", $class, $id, $parms );
  etag( "iframe" );
  return( bw_ret());
}

/**
 * Help for [bw_iframe] shortcode 
 */
function bw_iframe__help() {
  return( __( "Embed a page in an iframe", "oik" ) );
}

/**
 * Syntax for [bw_iframe] shortcode
 */
function bw_iframe__syntax( $shortcode="bw_iframe" ) {
  $syntax = array( "url" => BW_::bw_skv( null, __( "URL", "oik" ), __( "Full URL for the page", "oik" ) )
                 , "src" => BW_::bw_skv( null, __( "URL", "oik" ), __( "Full URL for the page - use as an alternative to the url= parameter", "oik" ) )
                 , "width" => BW_::bw_skv( null, "<i>" . __( "numeric", "oik" ) . "</i>", __( "Width in pixels", "oik" ) )
                 , "height" => BW_::bw_skv( null, "<i>" . __( "numeric", "oik" ) . "</i>", __( "Height in pixels", "oik" ) )
                 , "scrolling" => BW_::bw_skv( "no", "yes", __( "Support vertical scrolling", "oik" ) )
                 , "frameborder" => BW_::bw_skv( "0", "1", __( "Value for frameborder parameter", "oik" ) )
                 , "type" => BW_::bw_skv( "text/html", __( "string", "oik" ), __( "Type of the iframe", "oik" ) ) 
                 );
  // Add the others                 
  $syntax += _sc_classes();                
  return( $syntax );
}

/**
 * Return some values for the bw_iframe example and snippet
 *
 * Note: We add 17 pixels to the width in order to achieve an internal display of 480 pixels
 * 17 is the width we allow for the scrollbar (tested with Chrome) 
 */
function _bw_example_parms( ) {
  $example = kv( "src", site_url() );
  $example .= kv( "width", 497 );
  $example .= kv( "height", 320 );
  $example .= kv( "frameborder", "1" );
  $example .= kv( "scrolling", "yes" );
  return( $example );
}

function bw_iframe__example( $shortcode="bw_iframe" ) {
  $text = "Example: Display the site's home page as if you were viewing it on an iPhone - landscape mode"; 
  bw_invoke_shortcode( $shortcode, _bw_example_parms() , $text );
}

function bw_iframe__snippet( $shortcode="bw_iframe" ) {
  _sc__snippet( $shortcode, _bw_example_parms() );
}

