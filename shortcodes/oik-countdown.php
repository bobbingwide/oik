<?php // (C) Copyright Bobbing Wide 2013-2020

/**
 * Returns the next selector for [bw_countdown]
 *
 * $inc  | action | return
 * ----  | ------ | ------
 * true  | $countdown_id++ | next value
 * false | nop    | current value
 * null  | 0    | current value	= 0
 * 
 * @param bool|null $inc - increment the id?
 * @return string - tab selector ID
 */
function bw_countdown_id( $inc=true ) { 
  static $countdown_id = 0;
	if ( $inc ) {
		$countdown_id++;
	} elseif ( null === $inc ) {
		$countdown_id = 0;
	}	
  return( "countdown-$countdown_id" );
}

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
  $id = bw_array_get( $atts, "id", null );
  if ( null === $id ) { 
	$id = bw_countdown_id();
  }
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
  return( __( "Countdown timer", "oik" ) );
}

/**
 * Implement syntax hook for [bw_countdown]
 *
 * @link http://keith-wood.name/countdownRef.html#expiryText for more jQuery parameters 
 *
 */ 
function bw_countdown__syntax( $shortcode="bw_countdown" ) {
  $syntax = array( "until" => BW_::bw_skv( null, "<i>" . __( "date", "oik" ) . "</i>", __( "Target date in format yyyy-mm-dd-hh-mm-ss", "oik" ) )
                 , "since" => BW_::bw_skv( null, "<i>" . __( "date", "oik" ) . "</i>", __( "Start date in format yyyy-mm-dd-hh-mm-ss", "oik" ) )
                 , "description" => BW_::bw_skv( null, "<i>" . __( "text", "oik" ) . "</i>", __( "Description for countdown", "oik" ) )
                 , "expiryUrl" => BW_::bw_skv( null, "<i>" . __( "URL", "oik" ) . "</i>", __( "Target URL when countdown reaches zero", "oik" ) )
                 , "expiryText" => BW_::bw_skv( null, "<i>" . __( "text", "oik" ) . "</i>", __( "Text to replace time when countdown reaches zero", "oik" ) )
                 );
  return( $syntax );                              

}

/**
 * Examples for [bw_countdown] shortcode
 */
function bw_countdown__example( $shortcode="bw_countdown" ) {
  $text = __( "Countdown timer set to expire in 30 seconds", "oik" );
	$expired = __( "Timer expired", "oik" );
	$description = __( '30 second timer', "oik" );
  $example = "until=+30s expiryText='" . $expired . "' description='" . $description . "'";
  bw_invoke_shortcode( $shortcode, $example, $text );
  
  
  $text = __( "Countdown timer - counting up", "oik" ) ;
	$description = __( "Time you have been looking at this page", "oik" );
  $example = "since=+0s description='" . $description . "' format=S id=since ";
  bw_invoke_shortcode( $shortcode, $example, $text );
  
}

/**
 * Snippet for [bw_countdown] shortcode
 */
function bw_countdown__snippet( $shortcode="bw_countdown" ) {
  BW_::p( __( "No snippet available for this shortcode", "oik" ) );
}

/**
 *
 */
function bw_countdown_attributes( $attributes ) {
	$attributes = \oik\oik_blocks\oik_blocks_attribute_unset_or_trim( $attributes, 'since');
	$attributes = \oik\oik_blocks\oik_blocks_attribute_unset_or_trim( $attributes, 'until');
	$attributes = \oik\oik_blocks\oik_blocks_attribute_unset_or_trim( $attributes, 'format');
	return $attributes;

}
