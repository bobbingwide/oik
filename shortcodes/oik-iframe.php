<?php // (C) Copyright Bobbing Wide 2012, 2013

/**
 * Implement the [bw_iframe] shortcode 
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

function bw_iframe__help() {
  return( "Embed a page in an iframe" );
}

function bw_iframe__syntax( $shortcode="bw_iframe" ) {
  $syntax = array( "url" => bw_skv( null, "URL", "Full URL for the page" )
                 , "src" => bw_skv( null, "URL", "Full URL for the page - use as an alternative to the url= parameter" )
                 , "width" => bw_skv( null, "<i>numeric</i>", "Width in pixels" )
                 , "height" => bw_skv( null, "<i>numeric</i>", "Height in pixels" )
                 , "scrolling" => bw_skv( "no", "|yes", "Support vertical scrolling" )
                 , "frameborder" => bw_skv( "0", "1", "Value for frameborder parameter" )
                 , "type" => bw_skv( "text/html", "string", "Type of the iframe" ) 
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

