<?php // (C) Copyright Bobbing Wide 2012
if ( defined( 'OIK_FILTERS_INCLUDES_INCLUDED' ) ) return;
define( 'OIK_FILTERS_INCLUDES_INCLUDED', true );


/**
 * Replacement filter for bw_trace_action_start
 * 
 * Replace the bw_trace_action_start() filter with our own that always calls bw_trace2() to print the parameters before calling the real bw_trace_action_start() 
 * What happens if we disable action tracing? 
 */
function bw_trace_action_start_replaced() {
  $args = func_get_args();
  bw_trace2( $args );
  $value = call_user_func_array( "bw_trace_action_start", $args );
  return( $value ); 
}

/**
 * Trace the $merged_filters and $wp_filter global variables
 *
 * Just to see if we need to do anything with merged_filters **?**
 */
function bw_merged_filters() {
  global $merged_filters, $wp_filter;
  bw_trace2( $merged_filters, "merged_filters", false );
  bw_trace2( $wp_filter, "wp_filter", false );
}

/**
 * Dummy filter used for disabled filters
 *
 * Instead of unsetting the function for a disabled filter we replace it with a simple function that returns the value first thought of
 * Question: Is this safe? Are there filters that don't return the first arg? 
 */
function bw_disabled_filter( $arg ) {
  return( $arg );
} 

/**
 * Dummy filter to trace the parameters being passed
 * 
 * @param string $arg at least one parameter is required, this is what we'll return
 * Do we need to use func_num_args() or func_get_args() here? 
 */
function bw_trace_filter( $arg ) {
  $args = func_get_args(); 
  bw_trace2( $args );
  return( $arg );
}

/**
 * Replace a filter function with one of our own
 *
 * @param string $tag - the filter name e.g. 'the_content'
 * @param string $function_to_replace - the name of the filter function to remove (well, replace)
 * @param integer $priority - the priority of the function. This has to match
 * @param string $new_function - the replacement function
 *
 * @notes This code is based on remove_filter() from wp-includes/plugin.php
 * It does not deal with $merged_filters since it's not adding or deleting
 */
function bw_replace_filter( $tag, $function_to_replace, $priority=10, $new_function="bw_disabled_filter" ) {
  global $wp_filter;
  $r = isset( $wp_filter[$tag][$priority][$function_to_replace] );
  if ( $r ) { 
    $saved =& $wp_filter[$tag][$priority][$function_to_replace];
    if ( $saved['function'] == $new_function ) {
      bw_trace2( $saved, "already replaced", true, BW_TRACE_VERBOSE );
    } else {
      $saved['replaced'] = $saved['function'];
      $saved['function'] = $new_function;
    }  
    // bw_trace2( $saved, "saved" );
    // bw_trace2(  $wp_filter[$tag][$priority][$function_to_replace], "unchanged?" );
  }
}   

/**
 * Disable a filter but leave it in place
 * 
 * @param string $tag - the filter name e.g. 'the_content'
 * @param string $function_to_replace - the name of the filter function to remove (well, replace)
 * @param integer $priority - the priority of the function. This has to match
 * 
 * @uses bw_replace_filter()
 *
 */
function bw_disable_filter( $tag, $function_to_replace, $priority=10 ) {
  bw_replace_filter( $tag, $function_to_replace, $priority );
}

/**
 * Restore a filter
 * 
 * @param string $tag - the filter name e.g. 'the_content'
 * @param string $function_to_restore - the name of the filter function to be restored
 * @param integer $priority - the priority of the function. This has to match
 *  
 */
function bw_restore_filter( $tag, $function_to_restore, $priority= 10 ) {
  global $wp_filter;
  $r = isset( $wp_filter[$tag][$priority][$function_to_restore]['replaced'] );
  if ( $r ) { 
    $saved =& $wp_filter[$tag][$priority][$function_to_restore];
    if ( isset( $saved['replaced'] ) ) {
      $saved['function'] = $saved['replaced'];
    } else {  
      bw_trace2( $saved, "not replaced" );
    }  
  } 
}

