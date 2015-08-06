<?php // (C) Copyright Bobbing Wide 2015

/**
 * Dummy implementation of lazy trace config startup
 *
 * Caters for the situation that oik-bwtrace has been deactivated/deleted but the config file still contains
 * an include for oik/bwtrace.inc or oik/bwtrace.php
 * AND BW_TRACE_CONFIG_STARTUP is true.
 * 
 * Note: There was a symbiotic relationship between the oik-bwtrace and oik plugins in that
 * it was the oik base plugin that provided the raw trace APIs but it's oik-bwtrace that implements the actual functions.
 * The shared library concept has now extended to the oik-lib plugin, which also provides the trace wrappers.
 */
if ( !function_exists( "bw_lazy_trace_config_startup" ) ) {
	function bw_lazy_trace_config_startup() {
	  if ( defined( "WP_DEBUG" ) && true == WP_DEBUG ) {
		  echo "<!-- oik-bwtrace inactive. See notes in " . __FILE__ . "-->";
		}
	}
}	
