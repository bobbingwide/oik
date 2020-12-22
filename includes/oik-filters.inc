<?php // (C) Copyright Bobbing Wide 2012-2016

/**
 * Dummy filter used for disabled filters
 *
 * Instead of unsetting the function for a disabled filter we replace it with a simple function that returns the value first thought of.
 * Question: Is this safe? Are there filters that don't return the first arg?
 * 
 * @param mixed $arg
 * @return mixed whatever we got passed in the first arg 
 */
function bw_disabled_filter( $arg ) {
	return( $arg );
} 

/**
 * Dummy filter to trace the parameters being passed
 * 
 * @param mixed $arg at least one parameter is required, this is what we'll return
 * @return mixed whatever we got passed in the first arg
 */
function bw_trace_filter( $arg ) {
	$args = func_get_args(); 
	bw_trace2( $args );
	return( $arg );
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

/*
 * WP_Hook is new for WordPress 4.7
 */
if ( class_exists( "WP_Hook" ) ) {


/**
 * Replace a filter function with one of our own ( WordPress 4.7 )
 *
 * @param string $tag - the filter name e.g. 'the_content'
 * @param callable $function_to_replace - the name of the filter function to remove (well, replace)
 * @param integer $priority - the priority of the function. This has to match
 * @param string $new_function - the replacement function
 *
 * @notes This code is based on remove_filter() from wp-includes/plugin.php
 * It does not deal with $merged_filters since it's not adding or deleting
 * 
 * It now works for filters implemented as object's methods.
 */
function bw_replace_filter( $tag, $function_to_replace, $priority=10, $new_function="bw_disabled_filter" ) {
	global $wp_filter;
	$function_key = _wp_filter_build_unique_id( $tag, $function_to_replace, false );
	$r = isset( $wp_filter[$tag][$priority][$function_key] );
	if ( $r ) { 
		$wp_hook_object = $wp_filter[$tag];
		$wp_hook_object->callbacks[$priority][$function_key]['replaced'] = $function_to_replace;
		$wp_hook_object->callbacks[$priority][$function_key]['function'] = $new_function;
	}	
}

/**
 * Restore a filter ( WordPress 4.7 )
 *
 * Currently not used. Herb 2016/11/19
 * 
 * @param string $tag - the filter name e.g. 'the_content'
 * @param string $function_to_restore - the name of the filter function to be restored
 * @param integer $priority - the priority of the function. This has to match
 *  
 */
function bw_restore_filter( $tag, $function_to_restore, $priority= 10 ) {
	global $wp_filter;
	$function_key = _wp_filter_build_unique_id( $tag, $function_to_restore, false );
	$r = isset( $wp_filter[$tag][$priority][$function_key]['replaced'] );
	if ( $r ) { 
		$wp_hook_object = $wp_filter[$tag];
		$wp_hook_object->callbacks[$priority][$function_key]['function'] = 
			$wp_hook_object->callbacks[$priority][$function_key]['replaced']; 
		//bw_trace2( $wp_hook_object, "arrgh" );
	}
}

} else {

/* 
 * Pre WordPress 4.7 code. 
 *
 * Functions removed:
 * 
 * * bw_trace_action_start_replaced
 * * bw_merged_filters
 */
 

/**
 * Replace a filter function with one of our own ( pre WordPress 4.7 )
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
	//bw_trace2( $wp_filter, "wp_filter" );
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
 * Restore a filter	( pre WordPress 4.7 )
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

}

