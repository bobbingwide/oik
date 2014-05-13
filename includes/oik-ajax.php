<?php // (C) Copyright Bobbing Wide 2013-2014


/**
 * Ajax shortcode list
 */
function oik_ajax_list_shortcodes() {
  do_action( "oik_add_shortcodes" );
  oik_require( 'shortcodes/oik-codes.php' );
  $sc_list = bw_shortcode_list();
  $sc_json = json_encode( $sc_list );   
  bw_trace2( $sc_json );
  echo $sc_json;
  die(); 
}

/**
 * Ajax shortcode syntax
 */
function oik_ajax_load_shortcode_syntax() {
  do_action( "oik_add_shortcodes" );
  oik_require( "includes/oik-sc-help.inc" );
  $shortcode = bw_array_get( $_REQUEST, 'shortcode', 'oik' );
  $sc_syntax = _bw_lazy_sc_syntax( $shortcode );
  $sc_json = json_encode( $sc_syntax );   
  bw_trace2( $sc_json, "sc_json" );
  echo $sc_json;
  die();
}

/**
 * Ajax shortcode help information
 */
function oik_ajax_load_shortcode_help() {
  do_action( "oik_add_shortcodes" );
  oik_require( "includes/oik-sc-help.inc" );
  $shortcode = bw_array_get( $_REQUEST, 'shortcode', 'oik' );
  bw_trace2( $shortcode, "shortcode" );
  $sc_help = bw_lazy_sc_example( $shortcode );
  bw_trace2( $sc_help, "sc_help" );
  echo $sc_help;
  bw_flush();
  die();
}

 
/**
 * Register AJAX actions
 */
function oik_ajax_lazy_init() {
  add_action( 'wp_ajax_oik_ajax_list_shortcodes', 'oik_ajax_list_shortcodes' );
  add_action( 'wp_ajax_oik_ajax_load_shortcode_syntax', 'oik_ajax_load_shortcode_syntax' );
  add_action( 'wp_ajax_oik_ajax_load_shortcode_help', 'oik_ajax_load_shortcode_help' );
}




