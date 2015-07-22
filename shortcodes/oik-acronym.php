<?php // (C) Copyright Bobbing Wide 2011-2015
  
/** 
 * Display the company abbreviation using an acronym tag
 * 
 * there is a subtle difference between the two: abbr and acronym
 * see (for example) http://www.benmeadowcroft.com/webdev/articles/abbr-vs-acronym/   
 */
function bw_acronym( $atts = NULL ) {
  $abbr = bw_get_option( "abbr" );
  $company = bw_get_option( "company" );
  _bw_acronym( $company, $abbr );
  return( bw_ret());
}

