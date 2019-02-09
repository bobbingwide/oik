<?php
/*
Plugin Name: oik
Plugin URI: https://www.oik-plugins.com/oik-plugins/oik
Description: OIK Information Kit - Over 80 lazy smart shortcodes for displaying WordPress content
Version: 3.3.0
Author: bobbingwide
Author URI: https://www.oik-plugins.com/author/bobbingwide
Text Domain: oik
Domain Path: /languages/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

    Copyright 2010-2019 Bobbing Wide (email : herb@bobbingwide.com )

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
 * 
 * In oik 2.4 I tried not calling oik_main_init() for AJAX requests but this was more troublesome than beneficial since
 * there are many vanilla WordPress functions that can fail if post types are not registered.
 * 
 * In oik 3.0.3 I increased the priority to 20, to allow oik-types to apply overrides to the Genesis theme framework's definitions.
 */
function oik_plugin_file_loaded() {
  require_once( "libs/oik_boot.php" );
	oik_lib_fallback( dirname( __FILE__ ) . '/libs' );
	add_filter( "oik_query_libs", "oik_query_libs_query_libs" );
	add_action( "oik_lib_loaded", "oik_oik_lib_loaded" );
	oik_require_lib( "bwtrace" );
	oik_require_lib_wrapper( "bobbfunc" );
	oik_require_lib_wrapper( "class-BW-" );
  require_once( "oik-add-shortcodes.php" );
  require_once( "includes/bobbcomp.php" );
   
  if ( defined('DOING_AJAX') && DOING_AJAX ) {
    oik_require( "includes/oik-ajax.php" );
    oik_ajax_lazy_init();
  } else {
    add_action('wp_enqueue_scripts', 'oik_enqueue_stylesheets', 11);
    add_action( 'admin_enqueue_scripts', 'add_thickbox' );
  }
  add_action( 'init', 'oik_main_init', 20 );
  add_action( 'rest_api_init', 'oik_rest_api_init', 20 );
    
  add_filter( "attachment_fields_to_edit", "oik_attachment_fields_to_edit", null, 2 ); 
  add_filter( "attachment_fields_to_save", "oik_attachment_fields_to_save", null, 2 );
}

/** 
 * Implement 'wp_enqueue_scripts' action to enqueue the oik.css and $customCSS stylesheets as required
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
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$version = oik_version();
		} else {
			$version = false;
		}
		$url = plugins_url( "/oik/oik.css" );
		bw_trace2( $url, "oikCSS URL", false );
		wp_enqueue_style( 'oikCSS', $url, array(), $version );
	}
	$customCSS =  bw_get_option( 'customCSS' );
	if ( !empty( $customCSS) ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$timestamp = oik_query_timestamp( get_stylesheet_directory(), $customCSS );
		} else {
			$timestamp = false;
		}
		if ( $timestamp !== null ) {
			$customCSSurl = get_stylesheet_directory_uri() . '/' .  $customCSS;
			// bw_trace( $customCSSurl, __FUNCTION__, __LINE__, __FILE__, "customCSSurl");
			wp_register_style( 'customCSS', $customCSSurl, array(), $timestamp );
			wp_enqueue_style( 'customCSS' );
		}
	}
}

/**
 * Query the timestamp for a file
 * 
 * We need to wrap filemtime() to avoid 'Warning: filemtime(): stat failed for' messages.  
 * 
 * @param string $path the first part of the path to the file ( not terminated with a '/' )
 * @param string $file the rest of the path to the file ( not prefixed with a '/' )
 * @return integer/null the timestamp of the file
 */ 
function oik_query_timestamp( $path, $file ) {
	$full_file = $path . '/' . $file;
	$timestamp = null;	
	if ( file_exists( $full_file ) ) {
		$timestamp = filemtime( $full_file );
		if ( $timestamp === false ) {
			$timestamp = null;
		}
	}
	return( $timestamp );
} 

/** 
 * Implement the 'init' action
 * 
 * start oik and let oik dependent plugins know it's OK to use the oik API
 */
function oik_main_init() {
  add_action( 'admin_menu', 'oik_admin_menu' );
  add_action( 'network_admin_menu', "oik_network_admin_menu" );
  add_action( "network_admin_notices", "oik_network_admin_menu" );
  add_action( "admin_bar_menu", "oik_admin_bar_menu", 20 );
  add_action( 'login_head', 'oik_login_head');
	add_action( 'admin_notices', "oik_admin_notices", 9 );
	
	add_filter( "_sc__help", "oik_oik_sc__help", 10, 2 );
	$bobbfunc = oik_require_lib_wrapper( "bobbfunc" );
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
	oik_require_lib_wrapper( "bobbforms" );
	oik_require_lib_wrapper( "oik-admin" );
	oik_require_lib_wrapper( "class-bobbcomp" );
	oik_require_lib_wrapper( "oik-l10n" );
	oik_require_lib_wrapper( "class-BW-" );
	oik_require_lib_wrapper( "class-oik-update" );
	require_once( 'admin/oik-admin.php' );
  oik_options_add_page();
  add_action( 'admin_init', 'oik_admin_init' );
  do_action( 'oik_admin_menu' );
  add_action('admin_enqueue_scripts', 'oik_enqueue_stylesheets', 11 );
	
  add_action( "activate_plugin", "oik_load_plugins" );
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
		oik_require_lib_wrapper( "bobbforms" );
		oik_require_lib_wrapper( "oik-admin" );
		oik_require_lib_wrapper( "class-bobbcomp" );
		oik_require_lib_wrapper( "oik-l10n" );
		oik_require_lib_wrapper( "class-BW-" );
		oik_require_lib_wrapper( "class-oik-update" );
    require_once( 'admin/oik-admin.php' );
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
 * Implements "admin_bar_menu" action for oik
 *
 * We implement this first for the whole site...
 * @TODO but allow each user to override it in oik-user? 
 *
 * - This action hook runs after other action hooks to alter the "Howdy," prefix for 'my-account'.
 * - Most howdy replace plugins look for the string "Howdy," so the code won't work if the string has already been translated.
 * - We look for the translated version, which includes the user's display name.
 * - So this logic should work for localized versions.
 * - However, WordPress keeps changing the code. 
 * - Which has caused it to stop working in both 4.7 and 4.8.
 * - See WordPress TRACs 37794 and 40342.
 *  
 * The structure we're changing is the node for 'my-account'. e.g.
 * `
    [id] => my-account
    [parent] => top-secondary
    [title] => Howdy, <span class="display-name">vsgloik</span><img alt='' onerror='this.src="http://qw/wordpress/wp-content/themes/rngs0721/images/no-avatar.jpg"' src='http://1.gravatar.com/avatar/1c32865f0cfb495334dacb5680181f2d?s=26&amp;d=http%3A%2F%2F1.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536%3Fs%3D26&amp;r=G' class='avatar avatar-26 photo' height='26' width='26' />
    [href] => http://qw/wordpress/wp-admin/profile.php
    [meta] => Array
        (
            [class] => with-avatar
            [title] => My Account
        )
 * `
 * 
 * @param WP_Admin_Bar $wp_admin_bar - the WP_Admin_Bar object
 */
function oik_admin_bar_menu( &$wp_admin_bar ) {
	$replace = bw_get_option( "howdy" );
	if ( $replace ) {
		$node = $wp_admin_bar->get_node( 'my-account' );
		$current_user = wp_get_current_user();
		$howdy = sprintf( __('Howdy, %s'), '<span class="display-name">' . $current_user->display_name . '</span>' );
		//bw_trace2( $node, "node: $howdy" );
		$replace = $replace . " " . '<span class="display-name">' . $current_user->display_name . '</span>';
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
 * There is no need to define oik_boot nor bwtrace as these are pre-requisite libraries.
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
						, "oik-sc-help" => "class-BW-"
						, "oik-activation" => "oik-depends"
						, "oik-depends" => null 
						, "oik_plugins" => null
						, "oik_themes" => null
						, "bobbfunc" => null
						, "oik-autoload" => null
						, "oik-honeypot" => "bobbforms"
						, "class-oik-remote" => null
						, "class-oik-update" => "class-oik-remote"
						, "class-bobbcomp" => null
						, "class-dependencies-cache" => null
						, "class-BW-" => null
						, "oik-l10n" => null
						, "bw_fields" => null
						);
	$new_libraries = oik_lib_check_libs( $libraries, $libs, "oik" );
	
	// @TODO Replace this temporary fiddle of the version of bobbfunc with something more acceptable
	//$last = end( $new_libraries );
	//$last->version = "3.0.0";
	
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
	oik_require_lib_wrapper( "oik-depends" );
  $loaded = oik_require_lib_wrapper( "oik-activation" );
	bw_trace2( $loaded, "oik-activation loaded?", false, BW_TRACE_DEBUG );
	
}

/**
 * Wraps oik_require_lib
 *
 * oik_require_lib() has a number of possible outcomes
 * It can be invoked before "oik_query_libs" has been run.
 * It may need to use fallback logic to load library files, even when oik-lib is activated.
 * oik-lib itself also needs to implement fallback logic.
 *
 */
function oik_require_lib_wrapper( $lib ) {
	$loaded = oik_require_lib( $lib );
	bw_trace2( $loaded, "$lib loaded?", false );
	if ( is_object( $loaded ) ) {
		if ( is_wp_error( $loaded ) ) {
			bw_trace2( $loaded, "Library failed to load." );
		}
	}
	return $loaded;
}

/**
 * Implements "_sc__help" for oik
 * 
 * @param array $help array of translated help keyed by shortcode 
 * @param string $shortcode
 * @return array updated help array
 */
function oik_oik_sc__help( $help, $shortcode ) {
	oik_require( "includes/oik-sc-help.php" );
	$help = oik_lazy_sc__help( $help, $shortcode );
	return $help;
}


/**
 * Implements 'rest_api_init'
 *
 * Disables the_content processing to save time when the request doesn't need it.
 *
 */
function oik_rest_api_init() {
    $context = bw_array_get( $_REQUEST, 'context', null );
    if ( $context === 'edit') {
        remove_all_filters("the_content");
    }

}

function oik_is_rest() {
    $is_rest = defined( 'REST_REQUEST' ) && REST_REQUEST;
    return $is_rest;
}

function oik_is_block_editor() {
	$is_block_editor = false;
	if ( function_exists( "get_current_screen" ) ) {
		$current_screen = get_current_screen();
		bw_trace2( $current_screen, "current_screen" );
		$is_block_editor = $current_screen && $current_screen->is_block_editor();
	} else {
		bw_backtrace();
		bw_trace2("get_current_screen", "function does not exist", null, BW_TRACE_DEBUG);
	}
	return $is_block_editor;
}

function oik_is_block_renderer( $renderer=null ) {
	static $is_block_renderer = false;
	if ( $renderer !== null  ) {
		$is_block_renderer = $renderer;
	}
	return $is_block_renderer;
}


/**
 * Determines if shortcode expansion is necessary
 *
 * @TODO This is prototype code. Is it necessary?
 *
 * REST API | block renderer | Necessary?
 * -------- | -------------- | ----------
 * N        | n/a            | true
 * Y        | false          | false - don't expand shortcodes
 * Y        | true           | true - expand shortcodes when the block's being rendered

 *
 * @return bool
 */

function oik_is_shortcode_expansion_necessary() {
	$shortcode_expansion_necessary = true;
	if ( oik_is_rest() ) {
		if ( oik_is_block_renderer() ) {
			// We need to do this
		} else {
			$shortcode_expansion_necessary = false;
		}
	}
	return $shortcode_expansion_necessary;
}
	
/**
 * Initiate oik processing 
 */
oik_plugin_file_loaded();
        






