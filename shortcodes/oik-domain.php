<?php // (C) Copyright Bobbing Wide 2011-2016
/** 
 * Implement [bw_wpadmin] shortcode to display a link to WordPress admin
 *
 * If domain is not set then we default to the admin URL
 *
 * @param array $atts shortcode parameters
 * @param string $content - not expected
 * @param string $tags shortcode name
 * @return Return a link to the site's wp-admin 
 */
function bw_wpadmin( $atts=null, $content=null, $tags=null ) {
  $site = bw_get_option( "domain" );
  e( "Site:&nbsp; ");
	if ( $site ) {
		alink( null, "http://". $site . "/wp-admin", $site, "Website: " . $site );
	} else {
		$site_url = get_option( "siteurl" );
		$site_url = trim_scheme( $site_url );
		alink( null, get_admin_url(), $site_url );
	}
  return( bw_ret() );
}

/**
 * Simplify the URL for link text
 *
 * @param string $url
 * @return string simple link text
 */
function trim_scheme( $url ) {
	$parts = parse_url( $url );
	$url = bw_array_get( $parts, "host", null );
	$url .= bw_array_get( $parts, 'path' );
	return( $url );
	
}

/** 
 * Implement [bw_domain] shortcode
 *  
 */ 
function bw_domain() {
  $site = bw_get_option( "domain" );
	if ( $site ) {
		$domain = bw_output( "domain" );
	} else {
		$site_url = get_option( "siteurl" );
		$site_url = trim_scheme( $site_url );
		span( "domain siteurl" );
		e( $site_url );
		epan();
		$domain = bw_ret();
	}
	return( $domain );
} 


