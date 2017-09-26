<?php // (C) Copyright Bobbing Wide 2011-2017
/**
 * Generate a mailto: link with optional subject= parameter
 * 
 * Note: subject= parameter doesn't default to true since it makes it hard to turn it off. ie. subject=n doesn't work.
 * Perhaps this could become a site wide option **?**
 * 
 * @param string $email - email address
 * @param array $atts - shortcode parameters - possibly containing subject= parameter
 * @return string mailto: link with optional subject
 *
 */
function _bw_mailto_link( $email, $atts ) {
  $emailasb = antispambot( $email, 1 ); 
  $email_link = "mailto:". $emailasb;
  $subject = bw_array_get( $atts, "subject", false );
  if ( $subject ) {
    if ( bw_validate_torf( $subject ) ) {
      $subject = get_the_title();
    }
    $subject = rawurlencode( $subject );
    $email_link .= "?Subject=$subject";
  }   
  return( $email_link );
} 

/** 
 * "at dot" an email address
 * @param string $email - original email address
 * @param string $at - default value for replacing '@'
 * @param string $dot - default value for replacing '.' 
 * @return string - email address with @'s replaced by  " at " and .'s replaced by " dot "
 */
function _atdot( $email, $at=" at ", $dot=" dot " ) {
  $atdot = str_replace( "@", $at, $email );
  $atdot = str_replace( ".", $dot, $atdot );
  return( $atdot ); 
}

/**
 * Apply _atdot logic if required
 * @param string $email - email address 
 * @param mixed $atts - array of parameters - possibly containing atdot=y or atdot=required email or at= and/or dot=
 * @return string - the required result
 */
function _bw_atdot( $email, $atts ) {
  $atdot = bw_array_get( $atts, "atdot", false  );
  if ( $atdot ) { 
    if ( bw_validate_torf( $atdot ) ) {
      $email = _atdot( $email );
    } else {
      $email = $atdot; 
    }
  } else {
    $at = bw_array_get( $atts, "at", "@" );
    $dot = bw_array_get( $atts, "dot", "." );
    $email = _atdot( $email, $at, $dot ); 
  }
  return( $email );
}

/**
 * Internal function for bw_email and bw_mailto
 *
 * @param array $atts - array of shortcode parameters
 *
 *  use [bw_email] - for an inline mailto link 
 *  or [bw_mailto] for a more formal mailto link
 * 
 * Notes: Using class=email for Microformat
 */   
function _bw_email( $atts=null ) {
  $prefix = bw_array_get_dcb( $atts, "prefix", "Email" );
  $sep = bw_array_get( $atts, "sep", ": " );
  $title = bw_array_get_dcb( $atts, "title", "Send email to: " );
  $tag = bw_array_get( $atts, "tag", "span" );
  stag( $tag, "email");
  e( $prefix );
  e( $sep );
  $email = bw_array_get( $atts, "email", null );
  if ( !$email ) {
    $index = bw_array_get( $atts, "index", 'email' );
    $email = bw_get_option_arr( $index, null, $atts ); 
  }  
  $email_link = _bw_mailto_link( $email, $atts ); 
  $email = _bw_atdot( $email, $atts ); 
  alink( NULL, $email_link, $email, esc_attr( $title . $email) );
  etag( $tag );
  return( bw_ret() );
}

/**
 * Implement [bw_email] shortcode for an inline mailto: link
 * 
 * @param array $atts - shortcode parameters
 * @param string $content - not expected
 * @param string $tag - not expected
 * @return string expanded shortcode
 */
function bw_email( $atts=null, $content=null, $tag=null ) {
  $atts['tag'] = bw_array_get( $atts, "tag", "span" );
  return( _bw_email( $atts ) ); 
}  

/**
 * Implement [bw_mailto] shortcode for a mailto: link
 *
 * @param array $atts - shortcode parameters
 * @param string $content - not expected
 * @param string $tag - not expected
 * @return string expanded shortcode
 * 
 * Use the tag parameter to control the formatting. e.g. div
*/
function bw_mailto( $atts=null, $content=null, $tag=null ) {
  $atts['tag'] = bw_array_get( $atts, "tag", "p" );
  return( _bw_email( $atts ) );
}  

/**
 * Common parameters for bw_email, bw_mailto, etc. 
 */ 
function _sc_email() {
  $syntax = array( "prefix" => BW_::bw_skv( __( "Email", "oik" ), "<i>" . __( "string", "oik" ) . "</i>", __( "Prefix string", "oik" ) )
                 , "sep" => BW_::bw_skv( ": ", "<i>". __( "string", "oik" ) . "</i>", __( "Separator string", "oik" ) )
                 , "alt" => BW_::bw_skv( null, "1", __( "Use alternative value", "oik" ) )
                 , "title" => BW_::bw_skv( __( "Send email to: ",  "oik" ), "<i>" . __( "title string", "oik" ) . "</i>", __( "Tool tip text", "oik" ) )
                 );
  return( $syntax );
}                   
