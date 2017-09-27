<?php // (C) Copyright Bobbing Wide 2010-2017

/**
 * Implement [bw_contact] shortcode to display the primary contact name
 * 
 * @return string Formatted contact name using hCard Microformat
 * @uses - bw_get_me() to retreive the "contact" or fall back to the user display_name 
 */ 
function bw_contact( $atts=null, $content=null, $tag=null ) {
  $contact = bw_get_me( $atts );
  span( "vcard" );
  span( "fn" );
  e( $contact );
  epan();
  epan();
  return( bw_ret() );
} 

/**
 * Implement [bw_company] shortcode to display the company name
 */
function bw_company() {
  return( bw_output( "company" ));
} 

/**
 * Implement [bw_business] shortcode to display the company's business
 */
function bw_business() {
  return( bw_output( "business" ));
} 

/**
 * Implement [bw_company] shortcode to display the company's formal name
 */
function bw_formal() {
  return( bw_output( "formal" ));
} 

/** 
 * Implement [bw_slogan] shortcode to display the company's main slogan
 */
function bw_slogan() {
  return( bw_output( "main-slogan" ));
}
 
/**
 * Implement [bw_alt_slogan] shortcode to display the company's alternate slogan
 */
function bw_alt_slogan() {
  return( bw_output( "alt-slogan" ));
}
 
/**
 * Implement [bw_admin] shortcode to display the name of the "administrator" 
 *
 * Function renamed since there was a conflict with "BookingWizz" 
 */
function bw_admin_sc() {
  return( bw_output( "admin" ));
}

/**
 * Syntax for [bw_contact] shortcode
 */
function bw_contact__syntax( $shortcode="bw_contact" ) {
  $syntax = array( "alt" => BW_::bw_skv( null, "1", __( "Use alternative value", "oik" ) ) );
  return( $syntax );
}
  
