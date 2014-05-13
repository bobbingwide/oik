<?php // (C) Copyright Bobbing Wide 2013

/**
 * Return the required JavaScript date or, if it's an adjustment, leave the adjustment as is
 *
 * @param string $value - the JavaScript for the required date
 */
function bw_countdown_date( $value ) {
  $value = trim( $value );
  $offset = substr( $value, 0, 1 ); 
  if ( $offset == '+' || $offset == '-' ) {
    $jsdate = $value;
  } else {
    $jsdate = bw_jsdate( $value );
  }
  return( $jsdate );
}

/**
 * Implement the [bw_countdown] shortcode
 *  
 * @param array $atts - shortcode parameters
 * @param string $content - not expected
 * @param string $tag - shortcode tag - not expected
 * @return string - the required jQuery and HTML
 */
function bw_countdown( $atts=null, $content=null, $tag=null ) {
  oik_require( "includes/bw_jquery.inc" );
  $until = bw_array_get( $atts, "until", null );
  if ( $until ) {
    $atts["until"] = bw_countdown_date( $until );
  } else {
    $since = bw_array_get( $atts, "since", null );
    if ( $since ) {
      $atts['since'] = bw_countdown_date( $since );
    } else {
      $atts['until'] = bw_countdown_date( "+10" );
    }
  } 
  $class = bw_array_get( $atts, "class", null );
  $id = bw_array_get( $atts, "id", "countdown" );
  $debug = bw_array_get( $atts, "debug", false );  
  $script = bw_array_get( $atts, "script", "countdown" ); 
  $style = bw_array_get( $atts, "style", $script );
  $atts = bw_alter_atts( $atts, "class,id,debug,script,style", "expiryText,expiryUrl" );
  $parms = bw_jkv( $atts );
  $parms = bw_allow_js( $parms ); 
  oik_require( "shortcodes/oik-jquery.php" );
  bw_jquery_enqueue_script( $script, $debug );
  bw_jquery_enqueue_style( $style );
  bw_jquery( "div#$id" , "countdown", $parms, false );
  sediv( $class, $id );
  return( bw_ret() );
}

/**
 * Help hook for [bw_countdown] 
 */
function bw_countdown__help( $shortcode="bw_countdown" ) {
  return( "Countdown timer" );
}

/**
 * Implement syntax hook for [bw_countdown]
 *
 * @link http://keith-wood.name/countdownRef.html#expiryText for more jQuery parameters 
 *
 */ 
function bw_countdown__syntax( $shortcode="bw_countdown" ) {
  $syntax = array( "until" => bw_skv( null, "<i>date</i>", "Target date in format yyyy-mm-dd-hh-mm-ss" )
                 , "since" => bw_skv( null, "<i>date</i>", "Start date in format yyyy-mm-dd-hh-mm-ss" )
                 , "description" => bw_skv( null, "<i>text</i>", "Description for countdown" )
                 , "expiryUrl" => bw_skv( null, "<i>URL</i>", "Target URL when countdown reaches zero" )
                 , "expiryText" => bw_skv( null, "<i>text</i>", "Text to replace time when countdown reaches zero" )
                 );
  return( $syntax );                              

}

function bw_countdown__example( $shortcode="bw_countdown" ) {
  $text = "Countdown timer set to expire in 30 seconds";
  $example = "until=+30s expiryText='Timer expired' description='30 second timer'";
  bw_invoke_shortcode( $shortcode, $example, $text );
  
  
  $text = "Countdown timer - counting up" ;
  $example = "since=+0s description='Time you have been looking at this page' format=S id=since ";
  bw_invoke_shortcode( $shortcode, $example, $text );
  
}

function bw_countdown__snippet( $shortcode="bw_countdown" ) {
  p( "No snippet available for this shortcode" );
}
