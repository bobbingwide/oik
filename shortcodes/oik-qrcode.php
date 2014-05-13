<?php // (C) Copyright Bobbing Wide 2011-2013

/**
 * Implement [bw_qrcode] shortcode to display the QR code file with a link if required
 *
 * Notes: the attribute defaulting needs to be improved 
*/ 
function bw_qrcode( $atts ) {
  $link = bw_array_get( $atts, 'link', null );
  $text = bw_array_get_dcb( $atts, 'text', 'company', 'bw_get_option' );
  $width = bw_array_get( $atts, 'width', null );
  $height = bw_array_get( $atts, 'height', null );


  $upload_dir = wp_upload_dir();
  $baseurl = $upload_dir['baseurl'];
  
  $logo_image = bw_get_option( "qrcode-image" );
  $company = bw_get_option( "company" );
  $image_url = $baseurl . $logo_image;
  
  $image = retimage( NULL, $image_url, "QR code for " . $text , $width, $height );
  if ( $link ) {
    alink( NULL, $link, $image, $company );
  }  
  else {
    e( $image );  
  }  
  return( bw_ret());
    
}
