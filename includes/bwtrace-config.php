<?php // (C) Copyright Bobbing Wide 2015

/**
 * Dummy implementation of lazy trace config startup
 *
 * Caters for the situation that oik-bwtrace has been deactivated/deleted but the config file still contains
 * an include for oik/bwtrace.inc or oik/bwtrace.php
 * 
 * Note: There's a symbiotic relationship between the oik-bwtrace and oik plugins in that
 * it's the oik base plugin that provides the raw trace APIs and 
 * it's oik-bwtrace that implements the actual functions.
 * 
 * 
 * AND BW_TRACE_CONFIG_STARTUP is true.
 */
if ( !function_exists( "bw_lazy_trace_config_startup" ) ) {

	function bw_lazy_trace_config_startup() {
	  if ( defined( "WP_DEBUG" ) && true == WP_DEBUG ) {
		  echo "<!-- oik-bwtrace inactive. See notes in " . __FILE__ . "-->";
		}
	}
}	
