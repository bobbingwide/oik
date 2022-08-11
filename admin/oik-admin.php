<?php // (C) Copyright Bobbing Wide 2011-2019

/**
 * oik admin functions not in the oik-admin library
 * 
 * 
 * Functions moved to the oik-admin library:
 * Original function   | Replacement for l10n
 * ------------------- | ---------------------
 * - bw_load_plugin
 * - oik_box           | BW_::oik_box
 * - scolumn
 * - ecolumn
 * - oik_menu_header  | BW_::oik_menu_header
 * - oik_menu_footer
 * - _bwtnt
 * - oik_plugins_validate
 * - oik_enqueue_scripts
 
 * Note: These functions will be further stripped down as the oik admin interface is improved to use tabs.
 */	

/**
 * Initialise the plugin options
 */
function oik_options_init() {
	//bw_trace2();
	if ( function_exists( "oik_l10n_enable_jti" ) ) {
		oik_l10n_enable_jti();
	}
  register_setting( 'oik_options_options', 'bw_options', 'oik_options_validate' );
  register_setting( 'oik_options_options1', 'bw_options1', 'oik_options_validate' );
  register_setting( 'oik_plugins_options', 'bw_plugins', 'oik_plugins_validate' ); // No validation yet
  register_setting( 'oik_buttons_options', 'bw_buttons', 'oik_buttons_validate' );
  register_setting( 'oik_admin_options', 'bw_admin_options', 'oik_options_validate' );
  
  bw_load_plugin( "bw_buttons", "oik-paypal-shortcodes" );
  bw_load_plugin( "bw_buttons", "oik-button-shortcodes" );
  bw_load_plugin( "bw_buttons", "oik-shortc-shortcodes" );
  bw_load_plugin( "bw_buttons", "oik-quicktags" );
  bw_load_plugin( "bw_buttons", "oik-shortcake", "oik-shortcake.php" );
  bw_load_plugin( "bw_admin_options", "show_ids", "oik-ids.php" );
  // For WordPress 3.5 these filters are added in oik.php
  //add_filter( "attachment_fields_to_edit", "oik_attachment_fields_to_edit", null, 2 ); 
  //add_filter( "attachment_fields_to_save", "oik_attachment_fields_to_save", null, 2 );
  // Add support for plugin relocation during "pre_current_active_plugins" 
  add_action( "pre_current_active_plugins", "bw_relocate_pre_current_active_plugins", 10, 1 );
	add_action( "pre_current_active_plugins", "bw_check_symlinks", 10 );
	add_action( "pre_current_active_plugins", "bw_check_gitrepos", 10 );
}

/**
 * Load the oik-quicktags jQuery/JavaScript for when TinyMCE or the advanced HTML editor is being used
 * 
 * 
 * @TODO Question: Does this work in full screen? **?**
 */
function bw_load_admin_scripts() {
  wp_register_script( "oik-quicktags", oik_url( "admin/bw_shortcodes.js" ), array('quicktags') );  
  wp_enqueue_script( "oik-quicktags" );
}

/**
 * Add the options page
 * 
 * Note: To avoid getting the oik menu duplicated the name of the first submenu item needs to be the same
 * as the main menu item. see http://geekpreneur.blogspot.com/2009/07/getting-unwanted-sub-menu-item-in.html
 * In most "normal" WP menus the main menu gives you the full list
 * Notes: we need to enqueue the oik stylesheets for the oik options page
 */
function oik_options_add_page() {
  // add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position ); 
 
  // We don't specify the icon_url here since WordPress doesn't cater for it nicely.
  // It's better as a background image which can be hovered, in focus etc
  // plugins_url( "images/oik-icon.png", __FILE__ )
  // BUT in order to make this work we need to pass the parameter as 'div' 
  $hook = add_menu_page( __('[oik] Options', 'oik'), __('oik options', 'oik'), 'manage_options', 'oik_menu', 'oik_menu', 'div' );
  bw_trace2( $hook, "oik options hook", false, BW_TRACE_DEBUG );
  // add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function ); 
  $hook = add_submenu_page( 'oik_menu', __( 'oik overview', 'oik' ), __( 'Overview', 'oik'), 'manage_options', 'oik_menu', 'oik_menu');
  bw_trace2( $hook, "oik options Overview hook", false, BW_TRACE_DEBUG );
  $hook = add_submenu_page( 'oik_menu', __( 'oik options', 'oik' ), __('Options', 'oik'), 'manage_options', 'oik_options', 'oik_options_do_page');
  bw_trace2( $hook, "oik options Options hook", false, BW_TRACE_DEBUG );
  $hook = add_submenu_page( 'oik_menu', __( 'oik options-1', 'oik' ), __('More Options', 'oik'), 'manage_options', 'oik_options-1', 'oik_options_do_page_1');
  bw_trace2( $hook, "oik options More Options hook", false, BW_TRACE_DEBUG );
  add_submenu_page( 'oik_menu', __( 'oik plugins', 'oik' ), __('Plugins', 'oik'), 'manage_options', 'oik_plugins', 'oik_plugins_do_page' );
  add_submenu_page( 'oik_menu', __( 'oik themes', 'oik' ), __('Themes', 'oik'), 'manage_options', 'oik_themes', 'oik_themes_do_page' );
  add_submenu_page( 'oik_menu', __( 'oik buttons', 'oik'), __('Buttons', 'oik'), 'manage_options', 'oik_buttons', 'oik_buttons_do_page' );
  add_submenu_page( 'oik_menu', __( 'oik shortcode help', 'oik' ), __("Shortcode help", 'oik'), 'manage_options', 'oik_sc_help', "oik_help_do_page" );
  //add_submenu_page( 'oik_menu', __( 'oik admin options', 'oik' ), __("Admin options", 'oik'), 'manage_options', 'oik_admin_options", "oik_admin_options_do_page" );
}

/**
 * Dummy callback function
 * 
 * This might please Lee Boynton or Richard Holloway
 *
 */
function oik_callback() {
	BW_::p( __( "This box intentionally left blank", "oik" ) );
}

/** 
 *  Introduce oik options
 */
function oik_shortcode_options( ) {
  BW_::p( __( "oik provides sets of lazy smart shortcodes that you can use just about anywhere in your WordPress site.", "oik" ) );
 
  BW_::p( __( "You enter your common information, such as contact details, slogans, location information, PayPal payment information and your social networking and bookmarking information using oik Options", "oik" ) );
  BW_::p( __( "If required, you enter alternative information using More options", "oik" ) );
  
  BW_::alink( "button-primary", admin_url("admin.php?page=oik_options"), __( "Options", "oik" ), __( "Enter your common information", "oik" ) );
  e( "&nbsp;" );
  BW_::alink( "button-secondary", admin_url("admin.php?page=oik_options-1"), __( "More options", "oik" ), __( "Enter additional information", "oik" ) );
  
  BW_::p( __( "Discover the shortcodes that you can use in your content and widgets using Shortcode help", "oik" ) );
  
  BW_:alink( "button-secondary", admin_url( "admin.php?page=oik_sc_help"), __( "Shortcode help", "oik" ), __( "Discover shortcodes you can use", "oik" ) );
  
  BW_::p( __( "Choose the helper buttons that help you to insert shortcodes when editing your content", "oik" ) );
  BW_::alink( "button-secondary", admin_url( "admin.php?page=oik_buttons"), __( "Buttons", "oik" ), __( "Select TinyMCE and HTML edit helper buttons", "oik" ) );
  
}

/**
 * Display the oik custom CSS box
 */
function oik_custom_css_box() {
  $theme = bw_get_theme();
  BW_::p( __( "To style the output from shortcodes you can create and edit a custom CSS file for your theme.", "oik" ) );
  BW_::p( __( "Use the [bw_editcss] shortcode to create the <b>edit CSS</b> link anywhere on your website.", "oik" ) ); 
  BW_::p( __( "Note: If you change themes then you will start with a new custom CSS file.", "oik" ) );
  BW_::p( __( "You should save your custom CSS file before updating your theme.", "oik" ) );
  
  $options = get_option('bw_options');     
  $customCSS = bw_array_get( $options, 'customCSS', NULL );
  if ( $customCSS ) {
    BW_::p( __( "Click on this button to edit your custom CSS file.", "oik" ) );
    oik_custom_css( $theme );
  } else {
    BW_::p( __( "You have not defined a custom CSS file.", "oik" ) );
    BW_::alink( "button-secondary", admin_url("admin.php?page=oik_options"), __( "Name your custom CSS file", "oik" ), __( "Enter the name of your custom CSS file on the Options page", "oik" ) );
  }
}

/**
 * Display the Edit CSS link for a defined custom.css file
 */
function oik_custom_css( $theme=null ) {
  $options = get_option('bw_options');     
  $customCSS = bw_array_get( $options, 'customCSS', NULL );
  if ( $customCSS ) {
    $sanfile = sanitize_file_name( $customCSS );
    // Should we check the sanitized file name with the original ?
    bw_create_file( get_stylesheet_directory(), $sanfile, plugin_dir_path( __FILE__ ) . 'custom.css' );  
    bw_edit_custom_css_link( $sanfile, $theme );
  }    
} 

/**
 * Display the Plugins and Themes servers box
 */ 
function oik_plugins_servers() {
  $options = get_option( 'bw_plugins' );
  BW_::p( __( "Some oik plugins and themes are supported from servers other than WordPress.org", "oik" ) );
  BW_::p( __( "Premium plugin and theme versions require API keys.", "oik" ) );
  BW_::p( __( "Use the Plugins page to manage oik plugins servers and API keys", "oik" ) );
  BW_::alink( "button-secondary", admin_url("admin.php?page=oik_plugins"), __( "Plugins", "oik" ), __( "Manage plugin servers and API keys", "oik" ) );
  BW_::p( __( "Use the Themes page to manage oik themes servers and API keys", "oik" ) );
  BW_::alink( "button-secondary", admin_url("admin.php?page=oik_themes"), __( "Themes", "oik" ), __( "Manage theme servers and API keys", "oik" ) );
}


/**
 * Return default values for the Tiny MCE buttons
 *
 * Note: oik-shortcake is not done this way **?** 2015/01/28
 */
function oik_default_tinymce_buttons() {
  $defaults = array();
  $defaults['oik-button-shortcodes'] = "on";
  $defaults['oik-paypal-shortcodes'] = "on";
  $defaults['oik-shortc-shortcodes'] = "on";
  $defaults['oik-quicktags'] = "on";
  return( $defaults );
}  


/**
 * Allow selection of the TinyMCE buttons 
 * 
 * We call bw_recreate_options() to change the "bw_buttons" so that they are not autoloaded
 * Note: The buttons aren't actually set until the user visits this page AND selects "Save changes".
 */

function oik_tinymce_buttons() {
  $option = 'bw_buttons'; 
  $options = bw_form_start( $option, 'oik_buttons_options' );
  $options = bw_reset_options( $option, $options, "oik_default_tinymce_buttons", "_oik_reset_buttons" );
  $options = bw_recreate_options( 'bw_buttons' );
  
  $imagefile_bw = retimage( NULL, oik_url( 'admin/bw-bn-icon.gif' ), __( "Button shortcodes", "oik" ) );
  $imagefile_pp = retimage( NULL, oik_url( 'admin/bw-pp-icon.gif' ), __( "PayPal shortcodes", "oik" ) );
  $imagefile_sc = retimage( NULL, oik_url( 'admin/bw-sc-icon.gif' ), __( "ALL shortcodes", "oik" ) );
  bw_checkbox_arr( "bw_buttons", $imagefile_bw . ' ' . __("Button shortcodes", "oik" ), $options, 'oik-button-shortcodes' );
  bw_checkbox_arr( "bw_buttons", $imagefile_pp . ' ' . __("PayPal shortcodes", "oik" ), $options, 'oik-paypal-shortcodes' );
  bw_checkbox_arr( "bw_buttons", $imagefile_sc . ' ' . __("ALL shortcodes", "oik" ), $options, 'oik-shortc-shortcodes' );
  bw_checkbox_arr( "bw_buttons", __( "[] quicktag for HTML editor", "oik" ), $options, "oik-quicktags" );
  bw_checkbox_arr( "bw_buttons", __( "Integrate with shortcake", "oik" ), $options, "oik-shortcake" );
  etag( "table" );
  e( isubmit( "ok", __("Save changes", "oik" ), null, "button-primary" ) ); 
  etag( "form" );
  bw_flush();
}


/**
 * Display links to the oik documentation
 *
 * The oik documentation is on oik-plugins.com
 * The forum is on wordpress.org
 */
function oik_documentation() {
  BW_::p( __( "For more information:", "oik" ) );
  sul();
  stag( "li" );
  BW_:alink( null, "https://www.oik-plugins.com/getting-started-with-oik-plugins/", __( "Getting started", "oik" ) );
	etag( "li" );
  stag( "li" );
  BW_::alink( null,  "https://www.oik-plugins.com/oik/oik-faq", __( "Frequently Asked Questions", "oik" ) );
  etag( "li" );
  stag( "li" );
  BW_::alink( null, "https://wordpress.org/support/plugin/oik", __( "Forum", "oik" ) );
  etag( "li" );
  eul();
  sp();
  BW_::alink( "button button-secondary", "https://www.oik-plugins.com", __( "oik documentation", "oik" ), __( "Read the documentation for the oik plugin", "oik" ) );
  ep();
} 

/**
 * Display the PayPal donate button 
 */
function oik_support() {
  oik_require( "shortcodes/oik-paypal.php" );
	oik_require( "shortcodes/oik-bob-bing-wide.php" );
	/* translators: %1: oik - the plugin name, %2 a link to the company - bobbing wide */
  $text = sprintf( __( 'Support the development of %1$s by making a donation to %2$s', 'oik' ), bw_oik(), bw_lbw() );
  p_( $text );
  e( bw_pp_shortcodes( array( "type" => "donate", "email" => "herb@bobbingwide.com" )) );
}

/**
 * Display the oik admin options
 */
function oik_admin_options() { 
  $option = 'bw_admin_options'; 
  $options = bw_form_start( $option, 'oik_admin_options' );
  bw_checkbox_arr( "bw_admin_options", __( "Show IDs on admin pages", "oik" ), $options, 'show_ids' );
  etag( "table" );
  e( isubmit( "ok", __("Save changes", "oik" ), null, "button-secondary" ) ); 
  etag( "form" );
}

/**
 * Display the oik menu page - oik overview
 * 
 * Calls "oik_menu_box" action to allow other plugins to add their own "oik_box"es. e.g. oik-user
 */
function oik_menu() {
  BW_::oik_menu_header( __( "overview", "oik" ), "w70pc" );
  BW_::oik_box( null, null, __( "Shortcode options", "oik" ), "oik_shortcode_options" );
  BW_::oik_box( null, null, __( "Custom CSS", "oik" ), "oik_custom_css_box" );
  do_action( "oik_menu_box" );
  BW_::oik_box( null, null, __( "Servers and keys", "oik" ), "oik_plugins_servers" );
  ecolumn();
  scolumn( "w30pc" );
  BW_::oik_box( null, null, __( "oik documentation", "oik" ), "oik_documentation" );
  BW_::oik_box( null, null, __( "support oik", "oik" ), "oik_support" );
  BW_::oik_box( null, null, __( "Admin options", "oik" ), "oik_admin_options" );
  oik_menu_footer();
  bw_flush();
}

/** 
  * Draw the oik buttons page
  *
  */
function oik_buttons_do_page() {
  BW_::oik_menu_header( __( "button selection", "oik" ) );
  BW_::oik_box( null, null, __( "TinyMCE buttons", "oik" ), "oik_tinymce_buttons" );
  oik_menu_footer();
  bw_flush();
}  

/** 
 * Draw the oik plugins page
 *
 */ 
function oik_plugins_do_page() {
  oik_plugins_server_settings();
  bw_flush();
}

/** 
 * Draw the oik themes page
 *
 */ 
function oik_themes_do_page() {
  oik_themes_server_settings();
  bw_flush();
}

/**
 * Process the oik plugins server settings page
 * 
 * Dynamically load the "oik_plugins" library 
 * If OK, call oik_lazy_plugins_server_settings() to process the page
 */
function oik_plugins_server_settings() { 
	$oik_plugins = oik_require_lib( "oik_plugins" );
	if ( $oik_plugins && !is_wp_error( $oik_plugins ) ) {
		oik_lazy_plugins_server_settings();
	} else {
		bw_trace2( $oik_plugins, "oik_plugins?", false, BW_TRACE_WARNING );
	}
}

/**
 * Process the oik themes server settings page
 */
function oik_themes_server_settings() { 
	$oik_themes = oik_require_lib( "oik_themes" );
	if ( $oik_themes && !is_wp_error( $oik_themes ) ) {
		oik_lazy_themes_server_settings();
	} else {
		bw_trace2( $oik_themes, "oik_themes?", false, BW_TRACE_WARNING );
	}
}  

/** 
 * Draw the oik options page 
*/
function oik_options_do_page() {
  oik_require( "includes/oik-filters.inc" );
  bw_disable_filter( "the_content", "wpmem_securify", 1 );
  BW_::oik_menu_header( __( "shortcode options", "oik" ), "w60pc" );
  BW_::oik_box( null, null, __( "Often included key information", "oik" ), "oik_main_shortcode_options" );
  ecolumn();
  scolumn( "w40pc" );
  BW_::oik_box( null, null, __( "Usage notes", "oik" ), "oik_usage_notes" );
  oik_menu_footer();
  bw_flush();
} 

/**
 * Display telephone numbers
 * 
 * @param string $option Option name
 * @param array $options Array of option values
 * @param string $alt0_suffix - suffix text for shortcode examples
 */
function oik_contact_numbers( $option, $options, $alt0_suffix ) {
  //bw_translation_on();
  $telephone = BW_::bwtnt( __( "Telephone", "oik" ), " [bw_telephone$alt0_suffix] / [bw_tel$alt0_suffix]" );
  $mobile = BW_::bwtnt( __( "Mobile", "oik" ), " [bw_mobile$alt0_suffix] / [bw_mob$alt0_suffix]" );
  //bw_translation_off();
  
  BW_::bw_textfield_arr( $option, $telephone, $options, 'telephone', 50 );
  BW_::bw_textfield_arr( $option, $mobile , $options, 'mobile', 50 );
  bw_translation_on();   
}

/**
 * Display more telephone numbers
 */
function oik_more_contact_numbers( $option, $options, $alt0_suffix ) {
  $fax = BW_::bwtnt( __( "Fax", "oik" ), " [bw_fax$alt0_suffix]" );
  $emergency = BW_::bwtnt( __( "Emergency", "oik" ), " [bw_emergency$alt0_suffix]" );
  BW_::bw_textfield_arr( $option, $fax , $options, 'fax', 50 );
  BW_::bw_textfield_arr( $option, $emergency, $options, 'emergency', 50 ); 
}
  

/**
 * Display Company Information
 * 
 * @TODO 
 * In the UK - Companies House Registration Number (CRN) or Company Registration Number (CRN) 
 * is allocated by Companies House on registration of a company and is 
 * - an 8 digit number 
 * - or 2 alpha characters followed by a 6 digit number. 
 * You will find it on any documentation received from Companies House. 
 * A company would normally display it on letter headings or compliment slips. 
 */
function oik_company_info( $option, $options ) {  
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Company", "oik" ), " [bw_company]" ), $options, 'company', 50 );    
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Business", "oik" ), " [bw_business]" ), $options, 'business', 50 );    
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Formal", "oik" ), " [bw_formal]" ), $options, 'formal', 50 );    
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Abbreviation", "oik" ), " [bw_abbr]" ), $options, 'abbr', 50 );    
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Main slogan", "oik" ), " [bw_slogan]" ), $options, 'main-slogan', 50 );    
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Alt. slogan", "oik" ), " [bw_alt_slogan]" ), $options, 'alt-slogan', 50 );    
}

/**
 * Display Contact information 
 */
function oik_contact_info( $option, $options, $alt0_suffix ) {  
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Email", "oik" ), " [bw_mailto$alt0_suffix]/[bw_email$alt0_suffix]" ), $options, 'email', 50 );    
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Admin", "oik" ), " [bw_admin]" ), $options, 'admin', 50 );    
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Contact button page permalink", "oik" ), " [bw_contact_button]" ), $options, 'contact-link', 50 );    
  BW_::bw_textfield_arr( $option, __( "Contact button text", "oik" ), $options, 'contact-text', 50 );    
  BW_::bw_textfield_arr( $option, __( "Contact button tooltip", "oik" ), $options, 'contact-title', 50 );    
}  
  
/**
 * Address and geo fields
 *  
 *  extended-address e.g.  Bobbing Wide
 *  street-address   e.g.  41 Redhill Road
 *  locality         e.g   Rowlands Castle
 *  region           e.g.  HANTS
 *  postal-code      e.g.  PO9 6DE                        
 *  country-name     e.g.  UK 
 */
function oik_address_info( $option, $options, $alt0_suffix ) {    
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Extended address", "oik" ), " [bw_address$alt0_suffix]" ), $options, 'extended-address', 50 );
  BW_::bw_textfield_arr( $option, __( "Street address", "oik" ), $options, 'street-address', 50 );
  BW_::bw_textfield_arr( $option, __( "Locality", "oik" ), $options, 'locality', 50 );
  BW_::bw_textfield_arr( $option, __( "Region", "oik" ), $options, 'region', 50 );
  BW_::bw_textfield_arr( $option, __( "Post Code", "oik" ), $options, 'postal-code', 50 );
  BW_::bw_textfield_arr( $option, __( "Country name", "oik" ), $options, 'country-name', 50 );
  
  BW_::bw_textarea_arr( $option, BW_::bwtnt( __( "Google Maps introductory text for", "oik" ), " [bw_show_googlemap$alt0_suffix]" ), $options, 'gmap_intro', 50 );
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Latitude", "oik" ), " [bw_geo$alt0_suffix] [bw_directions$alt0_suffix]" ), $options, 'lat', 50 );
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Longitude", "oik" ), " [bw_show_googlemap$alt0_suffix]" ), $options, 'long', 50 );
}

/**
 * Display the social media "follow me" fields
 * 
 * @param string $option Option name
 * @param array $options Array of options
 * @param string $alt0_suffix 'alt=' parameter 
 */
function oik_follow_me( $option, $options, $alt0_suffix ) {
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Twitter URL", "oik" ), " [bw_twitter$alt0_suffix]" ), $options, 'twitter', 50 );
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Facebook URL", "oik" ), " [bw_facebook$alt0_suffix]" ), $options, 'facebook', 50 );
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "LinkedIn URL", "oik" ), " [bw_linkedin$alt0_suffix]" ), $options, 'linkedin', 50 );
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Google Plus URL", "oik" ), " [bw_googleplus$alt0_suffix]" ), $options, 'googleplus', 50 );
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "YouTube URL", "oik" ), " [bw_youtube$alt0_suffix]" ), $options, 'youtube', 50 );
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Flickr URL", "oik" ), " [bw_flickr$alt0_suffix]" ), $options, 'flickr', 50 );
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Picasa URL", "oik" ), " [bw_picasa$alt0_suffix]" ), $options, 'picasa', 50 );
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Pinterest URL", "oik" ), " [bw_pinterest$alt0_suffix]" ), $options, 'pinterest', 50 );
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Instagram URL", "oik" ), " [bw_instagram$alt0_suffix]" ), $options, 'instagram', 50 );
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Skype Name", "oik" ), " [bw_skype$alt0_suffix]" ), $options, 'skype', 50 );
	
	// @TODO Decide what shortcode to display, if any " [bw_github$alt0_suffix]"
	BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "GitHub username", "oik" ), null  ), $options, 'github', 50 );
	BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "WordPress.org username", "oik" ), null ), $options, 'wordpress', 50 );
} 

/**
 * Return the alt=0 suffix if required when oik-user is loaded
 */ 
function _oik_alt0_suffix() {
  if ( function_exists( "oiku_loaded" ) ) {
    $alt0_suffix = " alt=0";
  } else {
    $alt0_suffix = "";
  }
  return( $alt0_suffix ); 
}

/**
 * Display main shortcode options that aren't available to "More options"
 *
 * The google_map_api_key is created using developers.google.com
 * Mine's a 39 digit code. 
 
 */
function oik_main_shortcode_options() {
  $option = 'bw_options'; 
  $options = bw_form_start( $option, 'oik_options_options' );
  $alt0_suffix = _oik_alt0_suffix();
  oik_contact_numbers( $option, $options, $alt0_suffix );	
	oik_more_contact_numbers( $option, $options, $alt0_suffix );
  oik_company_info( $option, $options );
	
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Contact", "oik" ), " [bw_contact$alt0_suffix]" ), $options, 'contact', 50 );    
  oik_contact_info( $option, $options, $alt0_suffix );
  oik_address_info( $option, $options, $alt0_suffix );

  BW_::bw_textfield_arr( $option, __( "Google Map width", "oik" ), $options, 'width', 10 );
  BW_::bw_textfield_arr( $option, __( "Google Map height", "oik" ), $options, 'height', 10 );
	BW_::bw_textfield_arr( $option, __( "Google Maps API key", "oik" ), $options, 'google_maps_api_key', 39 ); 
  
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Domain", "oik" ), " [bw_domain] [bw_wpadmin]" ) , $options, 'domain', 50 );
  
  bw_checkbox_arr( $option, __( "Do NOT use the oik.css styling. <br />Check this if you don't want to use the oik provided CSS styling", "oik" ), $options, "oikCSS" ); 
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Custom CSS in theme directory:<br />", "oik") , get_stylesheet_directory_uri() ), $options, 'customCSS', 50 );
	
  BW_::bw_textfield_arr( $option, __( "Custom jQuery UI CSS URL", 'oik' ), $options, 'customjQCSS', 90 );
  
  oik_follow_me( $option, $options, $alt0_suffix );
  
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "PayPal email", "oik" ), " [bw_paypal]" ), $options, 'paypal-email', 50 );
  BW_::bw_textfield_arr( $option, __( "PayPal country", "oik" ), $options, 'paypal-country', 50 );
  
  /** Extracted from 
      @link https://www.paypalobjects.com/WEBSCR-640-20120609-1/en_US/GB/pdf/PP_OrderManagement_IntegrationGuide.pdf
      
   * AUD Australian Dollar 12,500 AUD
   * CAD Canadian Dollar 12,500 CAD
   * EUR Euro 8,000 EUR
   * GBP Pound Sterling 5,500 GBP
   * JPY Japanese Yen 1,000,000 JPY
   * USD U.S. Dollar 10,000 USD
   * whereas the list below was extracted from a WordPress plugin
   
  
  */
  $paypal_currency_list = array("GBP", "USD", "EUR", "AUD", "BRL", "CAD", "CHF", "CZK", "DKK", "HKD", "HUF", "ILS", "JPY", "MXN", "MYR", "NOK", "NZD", "PHP", "PLN", "SEK", "SGD", "THB", "TRY", "TWD");
  $paypal_currency_assoc = bw_assoc( $paypal_currency_list );                
  BW_::bw_select_arr( $option, __( "PayPal currency", "oik" ), $options, 'paypal-currency',array( "#options" => $paypal_currency_assoc ) );
  

  $upload_dir = wp_upload_dir();
  $baseurl = $upload_dir['baseurl'];
    
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Logo image ID/URL/in uploads", "oik"), "<br />$baseurl [bw_logo]" ), $options, 'logo-image', 50 );
  bw_checkbox_arr(  $option, __( "Use as login logo?", "oik" ), $options, "login-logo" ); 
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "QR code image in uploads", "oik" ), " [bw_qrcode]" ), $options, 'qrcode-image', 50 );
  
  $artisteer_versions = array( "na", "41", "40", "31", "30" ,"25" );
  $artisteer_assoc = bw_assoc( $artisteer_versions );
  BW_::bw_select_arr( $option, __( "Artisteer version", "oik" ), $options, 'art-version', array( "#options" => $artisteer_assoc ) );

  $options['yearfrom'] = bw_array_get_dcb( $options, "yearfrom", null, "bw_get_yearfrom" );
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Copyright from year", "oik" ), " [bw_copyright]" ), $options, 'yearfrom', 4 );
  

  $options['howdy'] = bw_array_get( $options, "howdy", null );
  BW_::bw_textfield_arr( $option, __( "'Howdy,' replacement string", "oik" ), $options, 'howdy', 10 );

  etag( "table" ); 		
  e( isubmit( "ok", __("Save changes", "oik" ), null, "button-primary" ) ); 
	
  etag( "form" );
  bw_flush();
}

/**
 * Display usage notes for some oik shortcodes
 * 
 *
 */
function oik_usage_notes() {
  $alt0_suffix = str_replace( " ", "", _oik_alt0_suffix() );
  oik_require( "includes/oik-sc-help.php" );
  BW_::p( __( "Use the shortcodes in your pages, widgets and titles. e.g.", "oik" ) );
  bw_invoke_shortcode( "bw_contact", $alt0_suffix, __( "Display your contact name.", "oik" ) );
  bw_invoke_shortcode( "bw_telephone", $alt0_suffix, __( "Display your telephone number.", "oik" ) );     
  bw_invoke_shortcode( "bw_address", $alt0_suffix, __( "Display your address.", "oik" ) );
  bw_invoke_shortcode( "bw_email", $alt0_suffix, __( "Display your email address.", "oik" ) );
  bw_invoke_shortcode( "bw_show_googlemap", $alt0_suffix, __( "Display a Google Map for your primary address.", "oik" ) );
  bw_invoke_shortcode( "bw_directions", $alt0_suffix, __( "Display a button for obtaining directions to your address.", "oik" ) );
  bw_invoke_shortcode( "bw_follow_me", $alt0_suffix, __( "Show all your <b>Follow me</b> buttons.", "oik" ) );
  bw_invoke_shortcode( "bw_follow_me", "$alt0_suffix theme=gener", __( "Show your <b>Follow me</b> buttons using genericons.", "oik" ) );
  BW_::p( __( "For more information about the shortcodes you can use select <b>Shortcode help</b>", "oik" ) );
  BW_::alink( "button-secondary", admin_url( "admin.php?page=oik_sc_help"), __( "Shortcode help", "oik" ), __( "Discover shortcodes you can use", "oik" ) );
  bw_flush();
}

/** 
 * Draw the oik options-1 page
 *
 * This page is for additional fields to enable multiple sets of bw_options, with $alt = 1;
*/
function oik_options_do_page_1() {
  BW_::oik_menu_header( __( "extra shortcode options", "oik" ), "w60pc" );
  BW_::oik_box( null, null, __( "alternative values using alt=1", "oik" ), "oik_extra_shortcode_options" );
  ecolumn();
  scolumn( "w40pc" );
  BW_::oik_box( null, null, __( "usage notes", "oik" ), "oik_extra_usage_notes" );
  oik_menu_footer();
  bw_flush();
}
  
/**
 * Display "More options" fields
 */
function oik_extra_shortcode_options() {    
  $alt = "1";
  $option = "bw_options$alt";
  $options = bw_form_start( $option, "oik_options_options$alt" );
  $alt1_suffix = " alt=1";
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Contact", "oik" ), " [bw_contact$alt1_suffix]" ), $options, 'contact', 50 );    
  BW_::bw_textfield_arr( $option, BW_::bwtnt( __( "Email", "oik" ), " [bw_mailto$alt1_suffix]/[bw_email$alt1_suffix]" ), $options, 'email', 50 );    
  
  /* We can't use the function blocks until they support the shortcode parameter suffix " alt=1" */ 
  oik_contact_numbers( $option, $options, $alt1_suffix );
  //oik_contact_info( $option, $options, $alt1_suffix );
  oik_address_info( $option, $options, $alt1_suffix );

  etag( "table" ); 
  e( isubmit( "ok", __("Save changes", "oik" ), null, "button-primary" ) ); 
  etag( "form" );
  bw_flush();
}  
  
/**
 * Display additional usage notes
 */
function oik_extra_usage_notes() {
  oik_require( "includes/oik-sc-help.php" );
  BW_::p( __( "Use the shortcodes in your pages, widgets and titles. e.g.", "oik" ) );
  bw_invoke_shortcode( "bw_contact", "alt=1",__( "Display your alternative contact name.", "oik" ) );
  /* translators: %s: e-mail Not translatable */
  bw_invoke_shortcode( "bw_email", "alt=1 prefix=e-mail", sprintf( __( 'Display your alternative email address, with a prefix of \'%1$s\'.', "oik" ), "e-mail" ) );
  bw_invoke_shortcode( "bw_telephone", "alt=1", __( "Display your alternative telephone number.", "oik" ) );
  bw_invoke_shortcode( "bw_address", "alt=1", __( "Display your alternative address.", "oik" ) );
  bw_invoke_shortcode( "bw_show_googlemap", "alt=1", __("Display a Google Map for your alternative address.", "oik" ) );
  bw_invoke_shortcode( "bw_directions", "alt=1", __( "Display directions to the alternative address.", "oik" ) );
  bw_flush();
}

/**
 * Sanitize and validate input. 
 * 
 * @param array $input - Accepts an array
 * @return array - returns a sanitized array.
 */
function oik_options_validate( $input ) {
	$input = oik_draconian_validation( $input );
	$customCSS = bw_array_get( $input, 'customCSS', null );
	if ( $customCSS ) {
		$sanfile = sanitize_file_name( $customCSS );
        // Should we check the sanitized file name with the original ?
        bw_create_file( get_stylesheet_directory(), $sanfile, plugin_dir_path( __FILE__ ) . 'custom.css' );
    }
    $input = oik_set_latlng( $input );
	return $input;
}

/**
 * Performs sanitization on all the input fields passed
 *
 * Note: Missing fields cannot be sanitized
 * Extraneous fields will be ignored.
 *
 * @param array $input
 * @return mixed*
 */
function oik_draconian_validation( $input ) {
	bw_trace2( null, null, true, BW_TRACE_VERBOSE );
	bw_backtrace();

	foreach ( $input as $key => $value ) {
		$input[ $key ] = oik_sanitize_key_value( $key, $value );
	}
	bw_trace2( $input, "input after", false, BW_TRACE_VERBOSE );
	return $input;
}

function oik_list_validations() {
	static $validations = null;
	if ( null === $validations ) {
		$validations = [];
		$validations['email'] = "sanitize_email";
		$validations['gmap_intro'] = "sanitize_textarea_field";
		$validations['paypal-email'] = "sanitize_email";
	}
	return $validations;
}

function oik_sanitize_key_value( $key, $value ) {
	$validations = oik_list_validations();
	$validation = bw_array_get( $validations, $key, null );
	if ( $validation && is_callable( $validation ) ) {
		$new_value = call_user_func( $validation, $value );
	} else {
		$new_value = sanitize_text_field( $value );
	}
	return $new_value;
}

/**
 * Set the lat and lng fields for the given address
 * 
 * if the address is set but either the lat/lng is not.
 *
 */
function oik_set_latlng( $input ) {
  $long = false;
  bw_trace2( $long, "long", false, BW_TRACE_DEBUG );
  
  $lat = bw_array_get( $input, "lat", false );
  $long = bw_array_get( $input, "long", $long );
  
  bw_trace2( $lat, "lat" );
  if ( $lat ) {
    $latlng = explode( ",",  $lat );
    bw_trace2( $latlng, "latlng" );
    $lat = bw_array_get( $latlng, 0, $lat );
    
    $long2 = bw_array_get( $latlng, 1, false );
    $long = bw_pick_one( $long, $long2 ); 
    
    
  }
  
  $input['lat'] = $lat;
  $input['long'] = $long;
  bw_trace2( $lat, "lat" );
  bw_trace2( $long, "long",  false );
  if ( $lat && $long ) {
    /* We seem to be sorted  but they could be wrong? Let's trust the user to know what they meant. It could just be 0,0! */
  } else {
    oik_require( "shortcodes/oik-googlemap.php" );
    $input = bw_geocode_googlemap( $input );
  }
  return( $input );
}

/**
 * Dummy validation for buttons
 */
function oik_buttons_validate( $input ) {
  return( $input );
}  

/**
 * Create a file with the specified name in the specified directory 
 * @param string base - the base path for the file name - may be absolute
 * @param string path - the rest of the file name - as specified by the user
 * @param string default - the fully qualified filename of the base source file to copy
 */
function bw_create_file( $base, $path, $default ) {
  $target = path_join( $base, $path );
  
  if ( !file_exists( $target ) ) {
     // create an empty file - or copy the original  
     // $info = pathinfo( $target );
     // $name = basename( $target );
     if ( $default ) {
        $success = copy( $default, $target );
     } else {
       // write an empty file
       $resource = fopen( $target, 'xb' );
       fclose( $resource );
     }
  }
}

/** 
 * Link to allow the custom CSS file to be edited 
 * @param string $customCSS Name of the custom.css file. Probably 'custom.css' 
 *
 * Note: you can't specify a relative path to this file. If you do you may see this message
 *
 *   Sorry, can't edit files with ".." in the name. 
 *   If you are trying to edit a file in your WordPress home directory, you can just type the name of the file in.
 *
 * With WPMS, the link takes you to style.css rather than custom.css. Don't know why! 
 * Actually, it's a bit more complex... the theme may be shared by multiple sites
 * so we need to further qualify the custom.css file
 * As a workaround just give it a different name than custom.css and hope for the best
 * it should really include the site ID or something
 * Note: note sure what authority is needed to view/edit the theme files.
 * 
 * For MultiSite the admin_url() is wrong - but we can use network_admin_url() for both
 * 
 * http://rowlandscastlewebdesign.com/wp-admin/network/theme-editor.php?
 * file=/home/rcwdcom/public_html/wp-content/themes/wpg0216/custom.css&theme=wpg0216&a=te&scrollto=0
 */ 
function bw_edit_custom_css_link( $customCSS, $theme ) {
  
  if ( $customCSS != null ) {
    global $wp_version; 
    $link = network_admin_url( "theme-editor.php" );
    $link .= '?file=';
    if ( version_compare( $wp_version, '3.4', "ge" ) ) {
      $link .= $customCSS;
    } else {  
      $link .= path_join( get_stylesheet_directory(), $customCSS );
    }  
    $link .= "&theme=$theme";
    $img = "{}";
    BW_::alink( "bw_custom_css", $link, $img, __( "Edit custom CSS", "oik" ) ); 
  }
}  

/**
 *  Lazy implementation of plugin dependency logic
 */
function oik_load_plugins() {
  oik_require( "admin/oik-depends.inc" );
}  

/**
 * Display help information for all the active shortcodes
 *
 * When a shortcode is selected for further display then we invoke the __example and __snippet routine
 * **?** For some reason, when the shortcode is 'nivo' the columns get wider than normal. Don't yet know why Herb 2012/04/26
*/ 
function oik_help_do_page() {
  do_action( "oik_add_shortcodes" );
  BW_::oik_menu_header( __( "shortcode help", "oik" ), "w95pc" );
  BW_::oik_box( null, null, __( "About shortcodes", "oik" ), "oik_code_about" );
  
  $shortcode = bw_array_get( $_REQUEST, "code", null );
  if ( $shortcode ) {
      /* translators: %s: oik_code_example - non translatable function name */
    BW_::oik_box( null, null, sprintf( __( '%1$s - more information', 'oik' ), $shortcode ), "oik_code_example" );
    /* translators: %s: oik_code_snippet - non translatable function name */
    BW_::oik_box( null, null, sprintf( __( '%1$s - snippet', 'oik' ), $shortcode ), "oik_code_snippet" ); 
  }
  oik_require( "shortcodes/oik-codes.php" );
  BW_::oik_box( null, null, __( "Shortcode summary", "oik" ), "oik_code_table" );
  oik_menu_footer();
  bw_flush();
}  

/**
 * Display the introduction to oik shortcode help
 */
function oik_code_about() {
  BW_::p( __( "This page lists all the currently active shortcodes. To find out more information about a shortcode click on the shortcode name in the Help column.", "oik" ) );
  BW_::p( __( "Depending on how the shortcode is implemented you will either be shown some more information with one or more examples, or an 'oik generated example.'", "oik" ) ); 
  BW_::p( __( "You will also be shown the HTML snippet for the example. There should be no need to do anything with this output.", "oik" ) );
  BW_::p( __( "For further information on a shortcode or its parameters click on the links in the Syntax column.", "oik" ) );
}

/** 
 * Display an example of the shortcode, which may be oik generated
 */
function oik_code_example() {
  $shortcode = bw_array_get( $_REQUEST, "code", null );
  do_action( "bw_sc_example", $shortcode );
}
  
/** 
 * Display the snippet the shortcode, which may be oik generated
 */
function oik_code_snippet() {
  $shortcode = bw_array_get( $_REQUEST, "code", null );
  do_action( "bw_sc_snippet", $shortcode );
}  
 
/**
 * Display the table of active shortcodes, sorted by name
 */ 
function oik_code_table() {
  $shortcodes = bw_list_shortcodes( array( "ordered" => "y") );
} 

/**
 * Constants for the oik-plugins servers
 * oik-plugins.co.uk was the test site so we didn't break oik-plugins.com as we tested oik v1.17 and the transition to oik v2.0
 * It's not so important now we're on oik v3.2
 */
if ( !defined( "OIK_PLUGINS_COM" ) ) {
	define( "OIK_PLUGINS_COM", "https://www.oik-plugins.com" );
}
if ( !defined( "OIK_PLUGINS_COUK" ) ) {
	define( 'OIK_PLUGINS_COUK', "https://www.oik-plugins.co.uk" );
}

/** 
 * Return the domain for the Premium (Pro) or Freemium version
 * 
 * We're trying to find the value to be set in `$transient->response[$plugin_slug]->url`
 * 
 * @return string the 
 */
if ( !function_exists( "oik_get_plugins_server" ) ) { 
function oik_get_plugins_server() {
  if ( defined( 'BW_OIK_PLUGINS_SERVER' )) {
    $url = BW_OIK_PLUGINS_SERVER;
  } else {
    $url = OIK_PLUGINS_COM;
  }
  return( $url );
}
}

/**
 * Return the URL for the oik theme's server
 */
 
if ( !function_exists( "oik_get_themes_server" ) ) { 
function oik_get_themes_server() {
  return( oik_get_plugins_server() );
}
}


/*
 * These functions have been promoted to the class-oik-update library.
 * They will be deprecated in the future, but in the mean time the public APIs
 * have been rewritten to invoke the shared library functions.
 * 
 * API                               | Rewritten or moved? | Referrer
 * --------------------------------- | ------------------- | -------
 * oik_register_plugin_server        | Rewritten           | Multiple plugins
 * oik_register_theme_server         | Rewritten           | Multiple themes
 * oik_lazy_altapi_init              | Moved 
 * oik_lazy_alttheme_init            | Moved
 * oik_site_transient_update_plugins | Moved
 * oik_site_transient_update_themes  | Moved
 * oik_altapi_check                  | Moved
 * oik_alttheme_check                | Moved
 * oik_query_plugins_server          | Rewritten | 
 * oik_query_themes_server           | Moved
 * bw_get_slug                       | Rewritten | oik-plugins, oik-themes
 * bw_last_path                      | Moved
 * oik_pluginsapi                    | Moved
 * oik_themes_api                    | Moved
 * oik_themes_api_result             | Moved
 */
 

/**
 * Register this plugin as one that is served from a different server to WordPress.org
 * 
 * Notes: Plugins registered using the API set the default value for the server ... which may be null
 * i.e. they set the intention to be served from somewhere other than WordPress.org
 * When required we determine the actual server location AND other fields as needed during oik_query_plugins_server() 
 *
 * At least ONE plugin needs to call this API for the oik plugin server logic to be activated.
 * 
 * @param string $file - fully qualified plugin file name
 * @param string $server - server name initial value - only set when the server value in the options is blank 
 * @param string $apikey - hard coded apikey initial value
 *
 */
function oik_register_plugin_server( $file, $server=null, $apikey=null ) {
	$oik_update_loaded = oik_require_lib( "class-oik-update" );	
	bw_trace2( $oik_update_loaded, "oik_update_loaded" );
	oik_update::oik_register_plugin_server( $file, $server, $apikey );
}

/**
 * Register this theme as one that is served from a different server to WordPress.org
 * 
 * Notes: Themese registered using the API set the default value for the server ... which may be null
 * i.e. they set the intention to be served from somewhere other than WordPress.org
 * When required we determine the actual server location AND other fields as needed during oik_query_themes_server() 
 *
 * At least ONE theme needs to call this API for the oik theme server logic to be activated.
 * 
 * @param string $file - fully qualified theme file name
 * @param string $server - server name initial value - only set when the server value in the options is blank 
 * @param string $apikey - hard coded apikey initial value
 * 
 */
function oik_register_theme_server( $file, $server=null, $apikey=null ) {
	$oik_update_loaded = oik_require_lib( "class-oik-update" );	
	bw_trace2( $oik_update_loaded, "oik_update_loaded" );
	oik_update::oik_register_theme_server( $file, $server, $apikey );
}

/**
 * Return the plugins server if the requested plugin is one of ours
 *
 * Note: $bw_registered_plugins is an array of filenames
 * we create $bw_slugs as an array of "slug" => array( 'basename' => "slug/plugin_name.php", 'file'=> 'server'=>, 'apikey'=> )
 * $bw_plugins (stored in WordPress options) also contains 'server' and 'apikey'
 * 
 * @param string $slug plugin slug
 * @return array 
 */
function oik_query_plugins_server( $plug ) {
	$bobbcomp_loaded = oik_require_lib( "class-bobbcomp" );
	$oik_update_loaded = oik_require_lib( "class-oik-update" );	
	$plugin_settings = oik_update::oik_query_plugins_server( $plug );	 
	return( $plugin_settings ); 
}

/**
 * Return the slug part of a plugin name
 *
 * This function should only be called when we know it's a plugin name with a directory.
 * 
 * Sample results
 * - "slug" for "slug/plugin_name.php" - when called for "update-check"
 * - "slugonly" for "slugonly" - when called for "plugin_information"
 * - "hello" for "hello.php" - does this ever happen?
 * - null for null
 * 
 * @param string $plugin - a plugin name
 * @return string $slug - the slug used to identify the plugin 
 *
 */
function bw_get_slug( $plugin ) {
	if ( $plugin ) {
		$pathinfo = pathinfo( $plugin );
		$slug = $pathinfo['dirname'] ;
		if ( $slug == '.' ) { 
			$slug = $pathinfo['filename'];
		}
	} else {
		$slug = null;
	}  
	return( $slug );    
}

/**
 * Perform plugin relocation just before the plugins are listed on the admin page
 *
 * for action: pre_current_active_plugins
 */
function bw_relocate_pre_current_active_plugins( $plugins ) {
  oik_require( "admin/oik-relocate.inc" );
  bw_lazy_relocate_pre_current_active_plugins( $plugins );
}

/** 
 * Add a plugin relocation to the $bw_relocations list
 * @param string $from - from plugin basename
 * @param string $to - to plugin basename
 */
function bw_add_relocation( $from, $to ) {
  global $bw_relocations;
  if ( !isset( $bw_relocations ) ) {
    $bw_relocations = array();
  }
  $bw_relocations[ $from ] = $to;
  bw_trace2( $bw_relocations );
}

/**
 * Implement 'pre_current_active_plugins' to perform symlink checking
 *
 * Disable updates for symlinked plugins.
 * 
 * There must be at least one plugin in the $wp_list_table if it's set; since we're that plugin. 
 * 
 * The plugin_data['plugin'] is only set if there is an update available.
 *
 * @TODO Trying to change the items in the wp_list_table doesn't appear to work. Remove this logic if that's the case.  
 * 
 */ 															
function bw_check_symlinks() {
	global $wp_list_table;
	if ( isset( $wp_list_table ) ) {
		$myplugins = $wp_list_table->items;
		$normalized = wp_normalize_path( WP_PLUGIN_DIR );
		foreach ( $myplugins as $plugin => $plugin_data ) {
			$new_version = bw_array_get( $plugin_data, "new_version", null );
			if ( $new_version ) {
				//bw_trace2( $new_version, "new_version", false );
				// For some plugins the $plugin key and $plugin_data['plugin'] are different.
				// so we should be using $plugin here
				$plugin_file = $plugin; 
				$plugin_path = $normalized . '/' . $plugin_file;
				bw_trace2( $plugin_path, "plugin_path", false );
				$real_path = realpath( $plugin_path );
				$real_path = wp_normalize_path( $real_path );
				bw_trace2( $real_path, "real_path" );
				if ( $real_path != $plugin_path ) { 
					$myplugins[ $plugin ]['update'] = 0;
					$myplugins[ $plugin ]['real_path'] = $real_path;
					unset( $myplugins[ $plugin ]['package'] );
					//remove_action( "after_plugin_row_" . $plugin_data['plugin'], "
					add_action( "in_plugin_update_message-" . $plugin_file, "bw_symlinked_plugin", 10, 2 ); 
					 
					remove_action( "after_plugin_row_$plugin_file", 'wp_plugin_update_row', 10, 2 );
					add_action( "after_plugin_row_" . $plugin_file, "bw_symlinked_after_plugin_row", 10, 2 ); 
					bw_trace2( $plugin_data, "plugin_data", false, BW_TRACE_VERBOSE );
				}
			}
		}
		bw_trace2( $wp_list_table->items, "Items" ); 
		bw_trace2( $myplugins, "myplugins" );
		$wp_list_table->items = $myplugins;
	}
}

/**
 * Implement "in_plugin_update_message-$plugin_file" for a symlinked plugin
 *  
 * Display a message for a symlinked plugin that appears to need updating
 *
 * @param array $plugin_data information about the plugin and any new version that's available
 * @param mixed $r 	YGIAGAM ???
 */
function bw_symlinked_plugin( $plugin_data, $r ) {
	bw_trace2( null, null, true, BW_TRACE_VERBOSE );
	bw_backtrace( BW_TRACE_VERBOSE );
	
	$version = bw_array_get( $plugin_data, "Version", null );
	$new_version = bw_array_get( $plugin_data, "new_version", null ); 
	if ( $new_version != $version ) {
	    /* translators: %s: new version number */
		$text = sprintf( __( 'WordPress has previously determined that there is a new version available: %s', "oik" ), $new_version );
		$text .= "<br />";
		/* translators: %s: Current plugin version */
		$text .= sprintf( __( 'Current version: %s', "oik" ), $version );
		$text .= "<br />";
		/* translators: %s: path to the symlinked plugin */
		$text .= sprintf( __( 'However, this is a symlinked plugin, located at <br /> %s', "oik" ), $plugin_data['real_path'] );
		$text .= "&nbsp;";
		$text .= "<br /><strong>";
		$text .= __( "So please don't attempt to apply updates.", "oik" );
		$text .= "</strong>";

		$message = '<tr class="plugin-update-tr">';
		$message .= '<td colspan="3" class="plugin-update colspanchange">';
		$message .= '<div class="update-message">';
		$message .= $text;
		$message .= "</div>";
		$message .= "</td>";
		$message .= "</tr>";
		
		echo $message;
	}
}

/**
 * Implement 'after_plugin_row_$plugin_file' 
 *  
 * @param string $file
 * @param array $plugin_data 
 */
function bw_symlinked_after_plugin_row( $file, $plugin_data ) {
	$r = null;
	bw_symlinked_plugin( $plugin_data, $r );
}

/**
 * Implement 'pre_current_active_plugins' to perform Git repo checking
 *
 * Disable updates for Git repositories. 
 * This should also work for Git repositories in symlinked plugins.
 * 
 * There must be at least one plugin in the $wp_list_table if it's set; since we're that plugin. 
 * 
 * The plugin_data['plugin'] is only set if there is an update available.
 *
 * @TODO Trying to change the items in the wp_list_table doesn't appear to work. Remove this logic if that's the case.  
 * 
 */ 															
function bw_check_gitrepos() {
	global $wp_list_table;
	if ( isset( $wp_list_table ) ) {
		$myplugins = $wp_list_table->items;
		$normalized = wp_normalize_path( WP_PLUGIN_DIR );
		foreach ( $myplugins as $plugin => $plugin_data ) {
			$new_version = bw_array_get( $plugin_data, "new_version", null );
			$slug = bw_array_get( $plugin_data, "slug", null );
			//if ( $new_version ) {
				bw_trace2( $new_version, "new_version", false );
				// For some plugins the $plugin key and $plugin_data['plugin'] are different.
				// so we should be using $plugin here
				$plugin_file = $plugin; 
				$plugin_path = $normalized . '/' . $slug;
				bw_trace2( $plugin_path, "plugin_path", false );
				$real_path = realpath( $plugin_path );
				$real_path = wp_normalize_path( $real_path );
				bw_trace2( $real_path, "real_path" );
				

				$gitdir = "$real_path/.git";
				$dotgit = file_exists( $gitdir );
				if ( $dotgit && is_dir( $gitdir ) ) {
					//gob();
					$myplugins[ $plugin ]['update'] = 0;
					$myplugins[ $plugin ]['real_path'] = $real_path;
					unset( $myplugins[ $plugin ]['package'] );
					//remove_action( "after_plugin_row_" . $plugin_data['plugin'], "
					add_action( "in_plugin_update_message-" . $plugin_file, "bw_gitrepo_plugin", 10, 2 ); 
					 
					remove_action( "after_plugin_row_$plugin_file", 'wp_plugin_update_row', 10, 2 );
					add_action( "after_plugin_row_" . $plugin_file, "bw_gitrepo_after_plugin_row", 10, 2 ); 
					bw_trace2( $plugin_data, "plugin_data", false, BW_TRACE_VERBOSE );
				}
			//}
		}
		bw_trace2( $wp_list_table->items, "Items" ); 
		bw_trace2( $myplugins, "myplugins" );
		$wp_list_table->items = $myplugins;
	}
}


/**
 * Implement "in_plugin_update_message-$plugin_file" for a Git repo plugin
 *  
 * Display a message for a Git repo plugin 
 *
 * @param array $plugin_data information about the plugin and any new version that's available
 * @param mixed $r 	YGIAGAM ???
 */
function bw_gitrepo_plugin( $plugin_data, $r ) {
	bw_trace2( null, null, true, BW_TRACE_VERBOSE );
	bw_backtrace( BW_TRACE_VERBOSE );
	$version = bw_array_get( $plugin_data, "Version", null );
	$new_version = bw_array_get( $plugin_data, "new_version", null ); 
	$text = __( "This plugin is a Git repository.", "oik" );
	$text .= "&nbsp;";
	/* translators: %s: New plugin version */
	$text .= sprintf( __( 'New version?: %s', "oik" ), $new_version );
	$text .= "&nbsp;";
	/* translators: %s: Current plugin version */
	$text .= sprintf( __( 'Current version: %s', "oik" ), $version );
	$text .= "<br /><strong>";
	$text .= __( "Please don't attempt to apply updates using WordPress.", "oik" );
	$text .= "</strong>";
	$message = '<tr class="plugin-update-tr">';
	$message .= '<td colspan="3" class="plugin-update colspanchange">';
	$message .= '<div class="update-message">';
	$message .= $text;
	$message .= "</div>";
	$message .= "</td>";
	$message .= "</tr>";
	echo $message;
}

/**
 * Implement 'after_plugin_row_$plugin_file' for a Git repo
 *  
 * @param string $file
 * @param array $plugin_data 
 */
function bw_gitrepo_after_plugin_row( $file, $plugin_data ) {
	$r = null;
	bw_gitrepo_plugin( $plugin_data, $r );
}



