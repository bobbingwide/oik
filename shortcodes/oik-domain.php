<?php // (C) Copyright Bobbing Wide 2011-2017
/** 
 * Implement [bw_wpadmin] shortcode to display a link to WordPress admin
 *
 * If domain is not set then we default to the admin URL.
 * If it is then we need to set the scheme.
 *
 * @param array $atts shortcode parameters
 * @param string $content - not expected
 * @param string $tags shortcode name
 * @return Return a link to the site's wp-admin 
 */
function bw_wpadmin( $atts=null, $content=null, $tags=null ) {
  $site = bw_get_option( "domain" );
  e( __( "Site:", "oik" ) . "&nbsp; ");
	if ( $site ) {
		$site = trim_scheme( $site );
		$site_url = set_url_scheme( "http://" . $site . "/wp-admin" );
		BW_::alink( null, $site_url, $site, sprintf( __( 'Website: %1$s', "oik" ), $site ) );
	} else {
		$site_url = get_option( "siteurl" );
		$site_url = trim_scheme( $site_url );
		BW_::alink( null, get_admin_url(), $site_url );
	}
  return( bw_ret() );
}

/**
 * Simplify the URL for link text
 *
 * @param string $url
 * @return string simple link text
 */
if ( !function_exists( "trim_scheme" ) ) { 
function trim_scheme( $url ) {
	$parts = parse_url( $url );
	$url = bw_array_get( $parts, "host", null );
	$url .= bw_array_get( $parts, 'path' );
	return( $url );
}	
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


