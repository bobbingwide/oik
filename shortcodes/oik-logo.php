<?php 
/*
    Copyright 2011-2015 Bobbing Wide (email : herb@bobbingwide.com )

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
 * Generate CSS3 definitions for a given rule
 *
 * Note: The unprefixed version is defined first. 
 * 
 * @param string $rule - the base CSS property name
 * @param string $value - the value to be set
 */
function oik_logo_css3( $rule, $value ) {
  foreach ( array( '', '-o-', '-webkit-', '-khtml-', '-moz-', '-ms-' ) as $prefix ) {
	  e( $prefix . $rule . ': ' . $value . '; ' );
  }
}

/**
 * Display the oik logo image as the login logo
 *
 * Currently uses simpler logic than Mark Jaquith's login-logo plugin, but plagiarises the CSS3 function
 * Images larger than a certain size may not display well.
 * It's a good idea to use a transparent .png file.
 *
 * If we're displaying our own logo then we will also filter the header URL and header title
 * to link to the current site.  
 *   
 */
function oik_lazy_login_head() {
  $logo_image = bw_get_option( "logo-image" );
  bw_trace2( $logo_image, "logo-image" );
  if ( $logo_image ) {
    $login_logo = bw_get_option( "login-logo" );
    if ( $login_logo ) {
      $image_url = bw_get_logo_image_url( $logo_image );
      //$upload_dir = wp_upload_dir();
      //$baseurl = $upload_dir['baseurl'];
      //$image_url = $baseurl . $logo_image;
      stag( "style", null, null, kv( "type", "text/css" ) );
      e( ".login h1 a { background-image: url( $image_url ) !important; " );
      oik_logo_css3( "background-size", "contain;" );
      e( "width: auto; }" );
      etag( "style" );
      bw_flush();
      add_filter( "login_headerurl", "oik_login_headerurl" );
      add_filter( "login_headertitle", "oik_login_headertitle" );
    }  
  } 
}

/**
 * Implement "login_headerurl" for oik
 *
 * Replace "https://wordpress.org" with our URL
 *
 * @param string $login_header_url
 * @return string - whatever network_site_url() returns
 *
 */ 
function oik_login_headerurl( $login_header_url ) {
  $login_header_url = network_site_url();
  return( $login_header_url );
}
  
/**
 * Implement "login_headertitle" for oik
 *
 * Replace "Powered by WordPress" with our Site name
 *
 * @param string $login_header_title - probably "Powered by WordPress"
 * @return string - whatever get_bloginfo() returns
 */  
function oik_login_headertitle( $login_header_title ) {
  $login_header_title = get_bloginfo( "name" );
  return( $login_header_title );
}

/**
 * Get logo image URL 
 *
 * Return the URL for the logo image allowing for
 * partial name within the uploads directory 
 * a fully qualified URL
 * a post ID
 * 
 * @param string $logo_image 
 * @return string - logo image URL ( not verified )
 *
 */
function bw_get_logo_image_url( $logo_image ) {
  $logo_image_url = null;
  if ( $logo_image ) {
    if ( is_numeric( $logo_image ) )  {
      //$logo_image_url = get_attached_file( $logo_image, true );
      $file = get_post_meta( $logo_image, "_wp_attached_file", true );
      $upload_dir = wp_upload_dir();
      $logo_image_url = $upload_dir['baseurl'] . '/' . $file;
    } elseif ( $logo_image[0] == '/' ) {
      $upload_dir = wp_upload_dir();
      $baseurl = $upload_dir['baseurl'];
      $logo_image_url = $baseurl . $logo_image;
    } else {
      $logo_image_url = $logo_image;
    }
  }
  bw_trace2( $logo_image_url, "logo_image_url" );
  return( $logo_image_url );
}  
 
/**
 * Display the company logo with a link if required
 *
 * Notes: the attribute defaulting needs to be improved
 *
 * @param array $atts - shortcode parameters
 * @return string HTML for the company logo image
 */ 
function bw_logo( $atts=null ) {
  wp_register_script( "oik_bw_logo", oik_url( "shortcodes/oik_bw_logo.js" ), array( 'jquery') );  
  wp_enqueue_script( "oik_bw_logo" );
  $link = bw_array_get( $atts, 'link', null );
  $text = bw_array_get( $atts, 'text', null );
  $width = bw_array_get( $atts, 'width', null );
  $height = bw_array_get( $atts, 'height', null );
  //$upload_dir = wp_upload_dir();
  //$baseurl = $upload_dir['baseurl'];
  $logo_image = bw_get_option( "logo-image" );
  if ( $text ) {
    $company = $text;
  } else {   
    $company = bw_get_option( "company" );
  }
  //$image_url = $baseurl . $logo_image;
  $image_url = bw_get_logo_image_url( $logo_image );
  if ( $image_url ) {
    $image = retimage( "bw_logo", $image_url, $company, $width, $height );
    if ( $link ) {
      alink( "bw_logo", $link, $image, $company );
    }  
    else {
      e( $image );  
    } 
  } 
  return( bw_ret());
}

/**
 * Syntax hook for [bw_logo] shortcode
 */
function bw_logo__syntax( $shortcode="bw_logo" ) {
  $syntax = array( "link" => bw_skv( "", ".|<i>URL</i>", "Link when clicked" )
                 , "text" => bw_skv( "", "<i>company</i>", "Text for tooltip" )
                 , "width" => bw_skv( "", "<i>width</i>", "Width in pixels" )
                 , "height" => bw_skv( "", "<i>height</i>", "Height in pixels" )
                 );  
  return( $syntax );
}



