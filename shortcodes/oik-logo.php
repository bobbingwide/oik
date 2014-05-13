<?php 
/*
    Copyright 2011-2014 Bobbing Wide (email : herb@bobbingwide.com )

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
 * Display the company logo with a link if required
 *
 * Notes: the attribute defaulting needs to be improved
 *
 * @param array $atts - shortcode parameters
 * @return string HTML for the company logo image
 */ 
function bw_logo( $atts=null ) {
  wp_register_script( "oik_bw_logo", plugin_dir_url( __FILE__). "oik_bw_logo.js", array( 'jquery') );  
  wp_enqueue_script( "oik_bw_logo" );
  $link = bw_array_get( $atts, 'link', null );
  $text = bw_array_get( $atts, 'text', null );
  $width = bw_array_get( $atts, 'width', null );
  $height = bw_array_get( $atts, 'height', null );
  $upload_dir = wp_upload_dir();
  $baseurl = $upload_dir['baseurl'];
  $logo_image = bw_get_option( "logo-image" );
  if ( $text )
    $company = $text;
  else   
    $company = bw_get_option( "company" );
  $image_url = $baseurl . $logo_image;
  $image = retimage( "bw_logo", $image_url, $company, $width, $height );
  if ( $link ) {
    alink( "bw_logo", $link, $image, $company );
  }  
  else {
    e( $image );  
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



