<?php // (C) Copyright Bobbing Wide 2012,2013
if ( defined( 'OIK_BWTRACE_BOOT_INCLUDED' ) ) return;
define( 'OIK_BWTRACE_BOOT_INCLUDED', true );


/**
 * Initialise tracing and action logging when WordPress is not yet loaded
 *
 * This file should only be loaded when 'BW_TRACE_CONFIG_STARTUP' is true
 * 
 * When this is the case trace will be initialised using default values for trace logging and action logging
 * If the other defines are true then the trace and action log files will be reset
 *
 * Note: This can cause problems when Ajax requests are coming in
 * and we also have to deal with the UI for summarising the action log...
 * we have to take a copy of the previous log BEFORE anything else is done.
 * 
 * 
 * define( 'BW_TRACE_ON', true );
 * define( 'BW_ACTIONS_ON', true );
 * define( 'BW_TRACE_RESET', true );
 * define( 'BW_ACTIONS_RESET', true );
*/ 
if ( defined( 'BW_TRACE_CONFIG_STARTUP' ) && BW_TRACE_CONFIG_STARTUP ) {
  function bw_trace_config_startup() {
    // Should this assume the same dir as __FILE__ - ie. always load from "oik" **?**
    require_once( ABSPATH . '/wp-content/plugins/oik/oik_boot.inc' );
    // Once oik_boot is loaded we can use oik_require2()
    // We load this up regardless of the defined values... assuming at least one of the values is set.
    oik_require2( "includes/bwtrace.inc", "oik-bwtrace" );
    bw_lazy_trace_config_startup();
  }
  
  bw_trace_config_startup();
} 

