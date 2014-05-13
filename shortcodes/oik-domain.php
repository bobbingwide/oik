<?php // (C) Copyright Bobbing Wide 2011-2013
/** 
 * Implement [bw_wpadmin] shortcode to display a link to WordPress admin
 *
 * @return Return a link to the site's wp-admin 
 */
function bw_wpadmin( $atts=null, $content=null, $tags=null ) {
  $site = bw_get_option( "domain" );
  e( "Site:&nbsp; ");
  alink( NULL, "http://". $site . "/wp-admin", $site, "Website: " . $site );
  return( bw_ret() );
  
}

/** 
 * Implement [bw_domain} shortcode 
 */ 
function bw_domain() {
  return( bw_output( "domain" ));
} 

