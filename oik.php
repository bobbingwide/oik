<?php
/*
Plugin Name: oik
Plugin URI: http://www.oik-plugins.com/oik-plugins/oik
Description: OIK Information Kit - Over 80 lazy smart shortcodes for displaying WordPress content
Version: 3.0.0-RC3
Author: bobbingwide
Author URI: http://www.oik-plugins.com/author/bobbingwide
Text Domain: oik
Domain Path: /languages/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

    Copyright 2010-2016 Bobbing Wide (email : herb@bobbingwide.com )

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

/**
 * Return the oik_version
 * 
 * @returns string oik base plugin version number - from the WordPress plugin template
 */  
function oik_version() {
  return bw_oik_version();
}

/**
 * Function to invoke when the file has been loaded
 *
 * All of the oik plugins and many of the common functions, include calls to bw_trace(), bw_trace2() or bw_backtrace() so we need to include bwtrace.inc
 * As of oik v2.3 the "init" action is invoked after most other plugins.
 * This allows oik-fields and oik-types to define overrides to registered post types and taxonomies. 
 * In oik 2.4 I tried not calling oik_main_init() for AJAX requests but this was more troublesome than beneficial since
 * there are many vanilla WordPress functions that can fail if post types are not registered.
 *z   
 */
function oik_plugin_file_loaded() {
  require_once( "libs/oik_boot.php" );
	oik_lib_fallback( __DIR__ . '/libs' );
	add_filter( "oik_query_libs", "oik_query_libs_query_libs" );
	add_action( "oik_lib_loaded", "oik_oik_lib_loaded" );
	oik_require_lib( "bwtrace" );
	oik_require_lib( "bobbfunc" );
  require_once( "oik-add-shortcodes.php" );
  require_once( "bobbcomp.inc" );
   
  if ( defined('DOING_AJAX') && DOING_AJAX ) {
    oik_require( "includes/oik-ajax.php" );
    oik_ajax_lazy_init();
  } else {
    add_action('wp_enqueue_scripts', 'oik_enqueue_stylesheets', 11);
  }
  add_action( 'init', 'oik_main_init', 11 );
    
  add_filter( "attachment_fields_to_edit", "oik_attachment_fields_to_edit", null, 2 ); 
  add_filter( "attachment_fields_to_save", "oik_attachment_fields_to_save", null, 2 );
}

/** 
 * Implement 'wp_enqueue_scripts' action enqueue the oik.css and $customCSS stylesheets as required
 *
 * oik.css contains styles for oik shortcodes. It is embedded if not specifically excluded.
 * $customCSS is embedded only if selected on oik options
 * If you want some of oik.css then copy the contents into custom.css and exclude oik.css
 *
 * Note: bwlink.css contains styles for the bobbingwide and oik branding colours
 * It is now only enqueued by the oik-bob-bing-wide plugin
 * 
 */
function oik_enqueue_stylesheets() {
  $oikCSS = bw_get_option( 'oikCSS' );
  // bw_trace2( $oikCSS, "oikCSS" );
  if ( !$oikCSS ) {
    wp_enqueue_style( 'oikCSS', WP_PLUGIN_URL . '/oik/oik.css' );
  }
  $customCSS =  bw_get_option( 'customCSS' );
  if ( !empty( $customCSS) ) {
    $customCSSurl = get_stylesheet_directory_uri() . '/' .  $customCSS;
    // bw_trace( $customCSSurl, __FUNCTION__, __LINE__, __FILE__, "customCSSurl");
    wp_register_style( 'customCSS', $customCSSurl );
    wp_enqueue_style( 'customCSS' );
  }
} 

/** 
 * Implement the 'init' action
 * 
 * start oik and let oik dependent plugins know it's OK to use the oik API
 */
function oik_main_init() {
  add_action( 'admin_menu', 'oik_admin_menu' );
  add_action( "activate_plugin", "oik_load_plugins" );
  add_action( 'network_admin_menu', "oik_network_admin_menu" );
  add_action( "network_admin_notices", "oik_network_admin_menu" );
  add_action( "admin_bar_menu", "oik_admin_bar_menu", 20 );
  add_action( 'login_head', 'oik_login_head');
	add_action( 'admin_notices', "oik_admin_notices", 9 );
	$bobbfunc = oik_require_lib( "bobbfunc" );
	if ( $bobbfunc && !is_wp_error( $bobbfunc ) ) {
    bw_load_plugin_textdomain();
		/**
		 * Tell plugins that oik has been loaded.
		*
		* This allows plugins which are dependent upon oik to start using the oik APIs
		* It doesn't mean that all the APIs are available.
		*/
		do_action( 'oik_loaded' );
	}	else {
		bw_trace2( $bobbfunc, "bobbfunc?", false, BW_TRACE_ERROR );
	}
}

/**
 * Implement the 'admin_menu' action
 *
 * Note: This action comes before 'admin_init' and after '_admin_menu'
 */ 
function oik_admin_menu() {
	oik_require_lib( "bobbforms" );
	oik_require_lib( "oik-admin" );
	require_once( 'admin/oik-admin.inc' );
  oik_options_add_page();
  add_action( 'admin_init', 'oik_admin_init' );
  do_action( 'oik_admin_menu' );
  add_action('admin_enqueue_scripts', 'oik_enqueue_stylesheets', 11 );
}

/** 
 * Implement the 'network_admin_menu'/'network_admin_notices' action for multisite
 * 
 * network_admin_menu is used to determine if plugins need updating
 * network_admin_notices is used in plugin dependency checking
 */
function oik_network_admin_menu() {
  static $actioned = null;
  if ( !$actioned ) { 
    $actioned = current_filter();
		oik_require_lib( "bobbforms" );
		oik_require_lib( "oik-admin" );
    require_once( 'admin/oik-admin.inc' );
    oik_options_add_page();
    add_action('admin_init', 'oik_admin_init' );
    do_action( 'oik_admin_menu' );
  } else {
    bw_trace2( $actioned, "actioned", false, BW_TRACE_INFO );
  }   
}
 
/**
 * Implement 'admin_init' action
 */
function oik_admin_init() {
  oik_options_init();
}

/**
 * Add the custom image link using the same method as the Portfolio slideshow plugin which used the method documented here:
 * @link http://wpengineer.com/2076/add-custom-field-attachment-in-wordpress/
 *
 * This is the method that adds fields to the form. Paired with 'attachment_fields_to_save'
 *
 * Note: Although this filter is only invoked during image editing
 * ( in admin processing ) it can't be registered during admin_init / admin_menu
 * since it's not invoked that way
 * i.e. Don't move the add_filter() logic for this filter.
 */
function oik_attachment_fields_to_edit( $form_fields, $post) { 
  $form_fields['bw_image_link'] = array(  
			"label" => __( "oik custom image link URL", "oik" ),  
			"input" => "text",
			"value" => get_post_meta( $post->ID, "_bw_image_link", true )  
		); 
  // This doesn't work since the url uses the [html] field instead of [value]
  // $form_fields['url']['value'] = get_post_meta( $post->ID, "_oik_nivo_image_link", true );   
  return $form_fields;  
}

/**
 * Save the "oik custom image link URL"
 *
 * We save the value even if it's blanked out.
 * Note: The custom meta field is prefixed with an underscore but the field name is not.
 * Paired with 'attachment_fields_to_edit'
 *
 * See also comments for oik_attachment_fields_to_edit()
 */ 
function oik_attachment_fields_to_save( $post, $attachment) { 
  $link = bw_array_get( $attachment, "bw_image_link", null ) ;
  update_post_meta( $post['ID'], '_bw_image_link', $link );  
  return $post;  
}

/**
 * Implement "admin_bar_menu" action for oik
 *
 * We implement this first for the whole site...
 * @TODO but allow each user to override it in oik-user? 
 *
 * This action hook runs after other action hooks to alter the "Howdy," prefix for 'my-account'
 * Most howdy replace plugins look for the string "Howdy," so the code won't work if the string has already been translated
 * We look for the translated version, which includes the user's display name.
 * So this logic should work localized versions.
 *  
 * The structure we're changing is the node for 'my-account'
 * e.g.
    [id] => my-account
    [parent] => top-secondary
    [title] => Howdy, vsgloik<img alt='' onerror='this.src="http://qw/wordpress/wp-content/themes/rngs0721/images/no-avatar.jpg"' src='http://1.gravatar.com/avatar/1c32865f0cfb495334dacb5680181f2d?s=26&amp;d=http%3A%2F%2F1.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536%3Fs%3D26&amp;r=G' class='avatar avatar-26 photo' height='26' width='26' />
    [href] => http://qw/wordpress/wp-admin/profile.php
    [meta] => Array
        (
            [class] => with-avatar
            [title] => My Account
        )
 *
 * @param WP_Admin_Bar $wp_admin_bar - the WP_Admin_Bar object
 * 
 */
function oik_admin_bar_menu( &$wp_admin_bar ) {
  $replace = bw_get_option( "howdy" );
  if ( $replace ) {
    $node = $wp_admin_bar->get_node( 'my-account' );
    $current_user = wp_get_current_user();
  	$howdy = sprintf( __('Howdy, %1$s'), $current_user->display_name );
    //bw_trace2( $node, "node" );
    $replace = $replace . " " . $current_user->display_name; 
    if ( $node && $node->title ) {
      $node->title = str_replace( $howdy, $replace, $node->title );
      $wp_admin_bar->add_node( $node );
    }  
  }
}

/**
 * Implement "login_head" action for oik
 * 
 * Will use the user defined logo image as the login logo, if required
 * 
 */
function oik_login_head() {
  oik_require( "shortcodes/oik-logo.php" );
  oik_lazy_login_head();
}

/**
 * Implement "oik_query_libs" for oik
 *
 * When the oik-lib shared library plugin is active we can register the libraries
 * that we 'Provide' by responding to this filter.
 * 
 * The libraries should be defined with their dependencies.
 *  
 * The libraries are NOT loaded at this time, just checked and registered
 * using oik_lib_check_libs() to build the OIK_lib objects for each library that actually exists.
 
 * 
 * @param array $libraries array of OIK_libs
 * @return array updated array of OIK_libs
 */
function oik_query_libs_query_libs( $libraries ) {
	$libs = array( "bobbforms" => "bobbfunc"
						, "oik-admin" => "bobbforms"
						, "oik-sc-help" => null
						, "oik-activation" => "oik-depends"
						, "oik-depends" => null 
						, "oik_plugins" => null
						, "bobbfunc" => null
						, "oik-autoload" => null
						);
	$new_libraries = oik_lib_check_libs( $libraries, $libs, "oik" );
	
	// @TODO Replace this temporary fiddle of the version of bobbfunc with something more acceptable
	$last = end( $new_libraries );
	$last->version = "3.0.0";
	
	//$versions = array( "bobbfunc" => "3.0.0" );
	//$new_libraries = oik_lib_set_lib_versions( $libraries, $libs, $versions, "oik" );
	bw_trace2( $new_libraries, "new libraries", true, BW_TRACE_VERBOSE );
	
	return( $new_libraries );
}

/**
 * Implement "oik_lib_loaded" for oik
 *
 * We might decide that we want to register some more libraries
 * when a particular one has been loaded.
 *
 * Note: Each new library is added to the list of  registered libraries
 * It doesn't replace any entry that's already there.
 */	
function oik_oik_lib_loaded( $lib ) {
	//bw_trace2();
	//if ( $lib->library == "bwtrace" ) {
	//	oik_register_lib( "bobbfunc", oik_path( "libs/bobbfunc.php" ), null, "3.0.0" );
	//}
}

/**
 * 
 */	
if ( !function_exists( "oik_lib_set_lib_versions" ) ) { 
function oik_lib_set_lib_versions( $libraries, $libs, $versions, $plugin ) {
	$lib_args = array();
	foreach ( $libs as $library => $depends ) {
		$src = oik_path( "libs/$library.php", $plugin ); 
		//if ( file_exists( $src ) ) {
		$lib_args['library'] = $library;
    $lib_args['src'] = $src;
		$lib_args['deps'] = $depends;
		$lib_args['version'] = bw_array_get( $versions, $library, null );
		$lib = new OIK_lib( $lib_args );
		$libraries[] = $lib;
	}
	return( $libraries );
}
}		

/**
 * Implement "admin_notices" for oik 
 *
 * Load admin/oik-activation.php before any other plugins which may be dependent upon it.
 * This saves them from loading their own version of the same code.
 *
 * Note: If oik-lib is not loaded then the dependency checking will not be performed
 * so we need to ensure "oik-depends" is loaded prior to "oik-activation".
 *
 *
 * 
 */
function oik_admin_notices() {
	oik_require_lib( "oik-depends" );
  $loaded = oik_require_lib( "oik-activation" );
	bw_trace2( $loaded, "oik-activation loaded?", false, BW_TRACE_DEBUG );
	
}
	
/**
 * Initiate oik processing 
 */
oik_plugin_file_loaded();
        






