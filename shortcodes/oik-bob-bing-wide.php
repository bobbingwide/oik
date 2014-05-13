<?php // (C) Copyright Bobbing Wide 2010-2014
if ( !defined( 'OIK_BOB_BING_WIDE_SHORTCODES_INCLUDED' ) ) {
define( 'OIK_BOB_BING_WIDE_SHORTCODES_INCLUDED', true );

/*
    Copyright 2010-2014 Bobbing Wide (email : herb@bobbingwide.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2,
    as published by the Free Software Foundation.

    You may NOT assume that you can use any other version of the GPL.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    The license for this software can likely be found here:
    http://www.gnu.org/licenses/gpl-2.0.html

*/

oik_require( "bobbcomp.inc" );

/**
 * functions moved to oik-bob-bing-wide plugin
 * <pre>
 * bw_bob()      shortcodes/oik-bob-bing-wide.php
 * bw_fob()      "
 * bw_bing()     "
 * bw_bong()     "
 * bw_wide()     "
 * bw_hide()     "
 * bw_wow()      shortcodes/oik-wow.php
 * bw_wow_long() "
 * </pre>
 */
 
  
/**
 * functions for [bw_plug] moved to oik-bob-bing-wide shortcodes/oik-plug.php
 * bw_plug()
 * bw_get_notes_page_url()
 * bw_plug_table()
 * bw_plug_etable()
 * bw_get_plugin_info_cache2()
 * bw_get_oik_plugins_info()
 * bw_get_defined_plugin_server()
 * bw_get_plugin_info2()
 * bw_get_local_plugin_xml()
 * bw_get_plugin_data()
 * bw_get_readme_data()
 * bw_add_xml_child()
 * bw_analyze_response_xml2()
 * _bw_tidy_response_xml()
 * bw_format_link()
 * bw_link_plugin_banner()
 * bw_link_plugin_download()
 * bw_link_notes_page()
 * bw_format_plug_table()
 * bw_plug_list_plugins()
 * bw_get_unique_plugin_names()
 * bw_format_default()
 * bw_get_banner_file_URL()
 */
 
/** 
 * functions moved within oik
 * bw_power  shortcodes/oik-power.php
 */
 
/**
 * These functions are used by oik AND are also defined as shortcodes in the oik-bob-bing-wide plugin
 * bw_oik() - in bobbcomp.inc
 * bw_lbw()
 * bw_loik()
 * bw_wp()
 */  

/**
 * Create a simple link to a website
 * 
 * @param array $atts - shortcode attributes
 *  site = prefix of the site. e.g. www.cwiccer or twenty-tens
 *  s = suffix = des for webdesign and dev for webdevelopment
 *  t = tld = defaults to .com
 *
 * The primary purpose of this is to produce the fancy text for Bobbing Wide
 * but it can be used for other sites if you specify site='your domain'
 */
if ( !function_exists( "bw_lbw" ) ) {
function bw_lbw( $atts=NULL ) {
  $sites = array( 'bw' => "" ,
                  'des' => "webdesign",
                  'dev' => "webdevelopment"
                  
                );   
  $s = bw_array_get( $atts, 's', "bw" );
  $tld = bw_array_get( $atts, 't', ".com" );
  $site = bw_array_get( $atts, 'site', "www.bobbingwide" );
  if ( $site == 'www.bobbingwide' ) {
     $text = $site;
     $title = 'Visit the Bobbing Wide website: ';
  } else {
    $text = $site; 
    $title = 'Visit the website: ';
  }     
  $site_s = bw_array_get( $sites, $s, NULL );
  $site .= $site_s;
  $site .= $tld;
  
  if ( $site_s ) 
    $text .= '<b>' . $site_s. '</b>';
  $text .= $tld;  
   
  $link = retlink( 'url', "http://" . $site, $text, $title . $site ) ;
  return( $link );
}
}

/**
 * Implement [loik] shortcode - a link to the oik plugin
 */
if ( !function_exists( "bw_loik" ) ) {
function bw_loik( $atts=null) {
  return( retlink( "bw_loik", "http://www.oik-plugins.com/oik", bw_oik(), "Link to the oik plugin")) ;
}
} 

/**
 * functions moved to oik-bob-bing-wide
 * bw_bp        shortcodes/oik-wp.php
 * bw_lwp()     "
 * bw_lwpms()   "
 * bw_lbp()     "
 * bw_ldrupal() "
 * bw_lart()    "
 * bw_wpms()    "
 * bw_drupal()  "
 * bw_art()     "
 */
 

/**
 * Implement [wp] shortcode for WordPress
 */
if ( !function_exists( "bw_wp" ) ) {
function bw_wp( $suffix=false ) {
  $bw = nullretstag( "span", "wordpress"); 
  $bw .= '<span class="bw_word">Word</span>';
  $bw .= '<span class="bw_press">Press</span>';
  if ( $suffix ) {
    $bw .= '<span class="bw_dotorg">.org</span>';
  }
  $bw .= nullretetag( "span", "wordpress" ); 
  return( $bw );
}
}

/** 
 * Implement [bw_editcss] shortcode to create an Edit (custom) CSS button. 
 */
function bw_editcss( $atts=null ) {
  oik_require( "admin/oik-admin.inc" );
  $theme = bw_get_theme();
  oik_custom_css( $theme );
  return( bw_ret());
}


/**
 * No longer needed - ticket #17657 has been fixed 
function wp1( $atts=NULL) {
  return( 'wp1 done');
} 
function wp2( $atts=NULL) {
  return( 'wp2 done');
}  
function wp3( $atts=NULL) {
  return( 'wp3 done');
}  
*/

} /* End if !defined() */

bw_trace2( "oik-bob-bing-wide-loaded" );
bw_backtrace();
