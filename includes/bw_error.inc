<?php // (C) Copyright Bobbing Wide 2012 

/**
 * Wrapper to WP_error
 *
 * @param string $code - an error code
 * @param string $text - translatable error text - default null
 * @param string $data - default null
 * @return $error - a new WP_error instance
 *
 * Would it be better to use exception handling than error handling?
 * @link http://journal.ryanmccue.info/165/why-wp_error-sucks/comment-page-1/#comment-2471 
 *
 * Note: The WP_error object needs to be serialized before returning it to a remote client
 */
function bw_lazy_wp_error( $code, $text, $data ) {
  $error = new WP_error( $code, $text, $data );
  //status_header( 404 );
  bw_trace2( $error );
  return( $error );
}                                 

