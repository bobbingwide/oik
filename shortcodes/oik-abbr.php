<?php // (C) Copyright Bobbing Wide 2011-2015

 
/** 
 * Display the company abbreviation using an abbr tag
 *
 */
function bw_abbr( $atts = NULL ) {
  $abbr = bw_get_option( "abbr" );
  $company = bw_get_option( "company" );
  _bw_abbr( $company, $abbr );
  return( bw_ret());
}
