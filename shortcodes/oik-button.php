<?php
if ( defined( 'OIK_BUTTON_SHORTCODES_INCLUDED' ) ) return;
define( 'OIK_BUTTON_SHORTCODES_INCLUDED', true );

/*
    Copyright 2011,2017 Bobbing Wide (email : herb@bobbingwide.com )

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

oik_require( "includes/oik-sc-help.php" );

/**
 * Create a "button" style link
 *
 *  [bw_button link="" text="" title="" class="" ]
 *  bw_button_shortcodes() calls art_button()
 *  art_button includes redundant classes for Artisteer 2.x and 3
 *
 */
function bw_button_shortcodes( $atts=NULL ) {
  $link = bw_array_get( $atts, 'link', NULL );
  $text = bw_array_get( $atts, 'text', __( "dummy", "oik" ) );
  $title = bw_array_get( $atts, 'title', $text ); 
  $class = bw_array_get( $atts, 'class', NULL );
  //bw_trace( $atts, __FUNCTION__, __LINE__, __FILE__, "atts" );
  art_button( $link, $text, $title, $class ); 
  return( bw_ret());  
}

/**
 * Syntax for [bw_button] shortcode
 */
function bw_button__syntax( $shortcode='bw_button' ) {
  $syntax = array( "link" => BW_::bw_skv( "", __( "URL", "oik" ), __( "URL for the link", "oik" ) ) 
                 , "text" => BW_::bw_skv( __( "dummy", "oik" ), "", __( "text for the button", "oik" ) )
                 , "title" => BW_::bw_skv( __( "as text", "oik" ), "", __( "title for the tooltip", "oik" ) )
                 , "class" => BW_::bw_skv( "", "", __( "CSS classes for the button", "oik" ) )
                 );
  return( $syntax ); 
}

/**
 * Example for [bw_button]
 */
function bw_button__example( $shortcode='bw_button' ) {
  $text = __( "To create a button suggesting that the user tries the bbboing plugin", "oik" ) ;
  $example = 'link="https://www.oik-plugins.com/oik-plugins/bbboing/" text="'. __( "Give the bbboing plugin a test drive", "oik" ) . '"';
  bw_invoke_shortcode( $shortcode, $example, $text );
}
  
/**
 * Create a Contact me button
 *
   
  Create a contact me button which links to the contact form page.
  Parameters are:
    [bw_contact_button link='URL' text='button text' title='button tooltip text' class='classes' ]
  
  Defaults:
  Field      option field used    hardcoded default
  link       bw_contact_link      /contact/
  text       bw_contact_text      Contact
  title      bw_contact_title     Contact $contact
  
  
*/  
function bw_contact_button( $atts=NULL ) {
  bw_trace( $atts, __FUNCTION__, __LINE__, __FILE__, "atts" );
  $contact = bw_default_empty_att( NULL, "contact", __( "me", "oik" ) );
  

  $link = bw_default_empty_arr( $atts, 'link', "contact-link", "/contact/" ); 
  
  bw_trace( $link, __FUNCTION__, __LINE__, __FILE__, "link" );
  $text = bw_default_empty_arr( $atts, 'text', "contact-text", __( "Contact", "oik" ) );
  /* translators: %s Name of the contact */
  $title = bw_default_empty_arr( $atts, 'title', "contact-title", sprintf( __( 'Contact %1$s', "oik" ) , $contact ) );
   
  $class = bw_array_get( $atts, 'class', NULL ) . "bw_contact" ;
  art_button( $link, $text, $title, $class ); 
  
  return( bw_ret());  
}

/**
 * Syntax for [bw_contact_button]
 */
function bw_contact_button__syntax( $shortcode='bw_contact_button' ) {
	$atts = array();
  $contact = bw_default_empty_att( NULL, "contact", __( "me", "oik" ) );
  $text = bw_default_empty_arr( $atts, 'text', "contact-text", __( "Contact", "oik" ) );
  $title = bw_default_empty_arr( $atts, 'title', "contact-title", sprintf( __( 'Contact %1$s', "oik" ) , $contact ) );
  $syntax = array( "contact" => BW_::bw_skv( $contact , __( "contact name", "oik" ), __( "who to contact", "oik" ) ) 
                 , "link" => BW_::bw_skv( "/contact/", __( "URL", "oik" ), __( "URL for the link", "oik" ) ) 
                 , "text" => BW_::bw_skv( $text , __( "contact-text", "oik" ), __( "text for the button", "oik" ) )
                 , "title" => BW_::bw_skv( $title, __( "contact-title", "oik" ), __( "title for the tooltip", "oik" ) )
                 , "class" => BW_::bw_skv( "bw_contact", "", __( "CSS classes for the button", "oik" ) )
                 );
  return( $syntax ); 
}


function bw_contact_button__example( $shortcode='bw_contact_button' ) {
  bw_invoke_shortcode( $shortcode, null, "To create a <b>Contact me</b> button linking to your contact form" );
}


