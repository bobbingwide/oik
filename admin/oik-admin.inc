<?php // (C) Copyright Bobbing Wide 2011-2017

/**
 * oik admin functions not in the oik-admin library
 * 
 * 
 * Functions moved to the oik-admin library:
 * - bw_load_plugin
 * - oik_box
 * - scolumn
 * - ecolumn
 * - oik_menu_header
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
 p( "This box intentionally left blank" );
}

/** 
 *  Introduce oik options
 */
function oik_shortcode_options( ) {
  p( "oik provides sets of lazy smart shortcodes that you can use just about anywhere in your WordPress site." );
 
  p( "You enter your common information, such as contact details, slogans, location information, PayPal payment information and your social networking and bookmarking information using oik Options" );
  p( "If required, you enter alternative information using More options" );
  
  _alink( "button-primary", admin_url("admin.php?page=oik_options"), "Options", "Enter your common information" );
  e( "&nbsp;" );
  _alink( "button-secondary", admin_url("admin.php?page=oik_options-1"), "More options", "Enter additional information" );
  
  p( "Discover the shortcodes that you can use in your content and widgets using Shortcode help" );
  
  _alink( "button-secondary", admin_url( "admin.php?page=oik_sc_help"), "Shortcode help", "Discover shortcodes you can use" );
  
  p( "Choose the helper buttons that help you to insert shortcodes when editing your content" );
  _alink( "button-secondary", admin_url( "admin.php?page=oik_buttons"), "Buttons", "Select TinyMCE and HTML edit helper buttons" );
  
}

/**
 * Display the oik custom CSS box
 */
function oik_custom_css_box() {
  $theme = bw_get_theme();
  p( "To style the output from shortcodes you can create and edit a custom CSS file for your theme." );
  p( "Use the [bw_editcss] shortcode to create the <b>edit CSS</b> link anywhere on your website." ); 
  p( "Note: If you change themes then you will start with a new custom CSS file." );
  p( "You should save your custom CSS file before updating your theme." );
  
  $options = get_option('bw_options');     
  $customCSS = bw_array_get( $options, 'customCSS', NULL );
  if ( $customCSS ) {
    p( "Click on this button to edit your custom CSS file.");
    oik_custom_css( $theme );
  } else {
    p( "You have not defined a custom CSS file." );
    alink( "button-secondary", admin_url("admin.php?page=oik_options"), "Name your custom CSS file", "Enter the name of your custom CSS file on the Options page" );
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
  p( "Some oik plugins and themes are supported from servers other than WordPress.org" );
  p( "Premium plugin and theme versions require API keys." );
  p( "Use the Plugins page to manage oik plugins servers and API keys" );
  _alink( "button-secondary", admin_url("admin.php?page=oik_plugins"), "Plugins", "Manage plugin servers and API keys" );
  p( "Use the Themes page to manage oik themes servers and API keys" );
  _alink( "button-secondary", admin_url("admin.php?page=oik_themes"), "Themes", "Manage theme servers and API keys" );
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
  
  $imagefile_bw = retimage( NULL, oik_url( 'admin/bw-bn-icon.gif' ), "Button shortcodes" );
  $imagefile_pp = retimage( NULL, oik_url( 'admin/bw-pp-icon.gif' ), "PayPal shortcodes" );
  $imagefile_sc = retimage( NULL, oik_url( 'admin/bw-sc-icon.gif' ), "ALL shortcodes" );
  bw_checkbox_arr( "bw_buttons", $imagefile_bw . bw_translate(" Button shortcodes" ), $options, 'oik-button-shortcodes' ); 
  bw_checkbox_arr( "bw_buttons", $imagefile_pp . bw_translate(" PayPal shortcodes" ), $options, 'oik-paypal-shortcodes' ); 
  bw_checkbox_arr( "bw_buttons", $imagefile_sc . bw_translate(" ALL shortcodes"), $options, 'oik-shortc-shortcodes' ); 
  bw_checkbox_arr( "bw_buttons", bw_translate( "[] quicktag for HTML editor"), $options, "oik-quicktags" );
  bw_checkbox_arr( "bw_buttons", bw_translate( "Integrate with shortcake" ), $options, "oik-shortcake" );
  //bw_tablerow( array( "", "<input type=\"submit\" name=\"ok\" value=\"Save changes\" class=\"button-primary\"/>") ); 
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
  p("For more information:" );
  sul();
  stag( "li" );
  _alink( null, "http://www.oik-plugins.com/tutorial/getting-started-with-oik-plugins/", "Getting started", "Getting started" );
  stag( "li" );
  _alink( null,  "http://www.oik-plugins.com/oik/oik-faq", "Frequently Asked Questions", "Frequently Asked Questions" );
  etag( "li" );
  stag( "li" );
  _alink( null, "http://wordpress.org/tags/oik?forum_id=10", "Forum", "Forum" );
  etag( "li" );
  eul();
  sp();
  // art_button( "http://www.oik-plugins.com", "oik documentation", "Read the documentation for the oik plugin" );
  _alink( "button button-secondary", "http://www.oik-plugins.com", "oik documentation", "Read the documentation for the oik plugin" );
  ep();
} 

/**
 * Display the PayPal donate button 
 */
function oik_support() {
  oik_require( "shortcodes/oik-paypal.php" );
	oik_require( "shortcodes/oik-bob-bing-wide.php" );
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
  bw_checkbox_arr( "bw_admin_options", "Show IDs on admin pages", $options, 'show_ids' );
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
  oik_menu_header( "overview", "w70pc" );
  oik_box( NULL, NULL, "Shortcode options", "oik_shortcode_options" );
  oik_box( NULL, NULL, "Custom CSS", "oik_custom_css_box" );
  do_action( "oik_menu_box" );
  oik_box( NULL, NULL, "Servers and keys", "oik_plugins_servers" );
  ecolumn();
  scolumn( "w30pc" );
  oik_box( NULL, NULL, "oik documentation", "oik_documentation" );
  oik_box( NULL, NULL, "support oik", "oik_support" );
  oik_box( null, null, "Admin options", "oik_admin_options" );
  oik_menu_footer();
  bw_flush();
}

/** 
  * Draw the oik buttons page
  *
  */
function oik_buttons_do_page() {
  oik_menu_header( "button selection" );
  oik_box( NULL, NULL, "TinyMCE buttons", "oik_tinymce_buttons" );
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
  oik_menu_header( "shortcode options", "w60pc" );
  oik_box( null, null, "Often included key information", "oik_main_shortcode_options" );
  ecolumn();
  scolumn( "w40pc" );
  oik_box( null, null, "Usage notes", "oik_usage_notes" );
  oik_menu_footer();
  bw_flush();
} 

/**
 * Display telephone numbers
 */
function oik_contact_numbers( $option, $options, $alt0_suffix ) {
  bw_translation_on();
  $telephone = _bwtnt( "Telephone", " [bw_telephone$alt0_suffix] / [bw_tel$alt0_suffix]" );
  $mobile = _bwtnt( "Mobile", " [bw_mobile$alt0_suffix] / [bw_mob$alt0_suffix]" );
  $fax = _bwtnt( "Fax", " [bw_fax$alt0_suffix]" );
  $emergency = _bwtnt( "Emergency", " [bw_emergency$alt0_suffix]" );
  bw_translation_off();
  
  bw_textfield_arr( $option, $telephone, $options, 'telephone', 50 );
  bw_textfield_arr( $option, $mobile , $options, 'mobile', 50 );
  bw_textfield_arr( $option, $fax , $options, 'fax', 50 );
  bw_textfield_arr( $option, $emergency, $options, 'emergency', 50 ); 
  bw_translation_on();   
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
  bw_textfield_arr( $option, _bwtnt( "Company", " [bw_company]" ), $options, 'company', 50 );    
  bw_textfield_arr( $option, _bwtnt( "Business", " [bw_business]" ), $options, 'business', 50 );    
  bw_textfield_arr( $option, _bwtnt( "Formal", " [bw_formal]" ), $options, 'formal', 50 );    
  bw_textfield_arr( $option, _bwtnt( "Abbreviation", " [bw_abbr]" ), $options, 'abbr', 50 );    
  bw_textfield_arr( $option, _bwtnt( "Main slogan", " [bw_slogan]" ), $options, 'main-slogan', 50 );    
  bw_textfield_arr( $option, _bwtnt( "Alt. slogan", " [bw_alt_slogan]" ), $options, 'alt-slogan', 50 );    
}

/**
 * Display Contact information 
 */
function oik_contact_info( $option, $options, $alt0_suffix ) {  
  bw_textfield_arr( $option, _bwtnt( "Contact", " [bw_contact$alt0_suffix]" ), $options, 'contact', 50 );    
  bw_textfield_arr( $option, _bwtnt( "Email", " [bw_mailto$alt0_suffix]/[bw_email$alt0_suffix]" ), $options, 'email', 50 );    
  bw_textfield_arr( $option, _bwtnt( "Admin", " [bw_admin]" ), $options, 'admin', 50 );    
  bw_textfield_arr( $option, _bwtnt( "Contact button page permalink", " [bw_contact_button]" ), $options, 'contact-link', 50 );    
  bw_textfield_arr( $option, "Contact button text", $options, 'contact-text', 50 );    
  bw_textfield_arr( $option, "Contact button tooltip", $options, 'contact-title', 50 );    
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
  bw_textfield_arr( $option, _bwtnt( "Extended-address", " [bw_address$alt0_suffix]" ), $options, 'extended-address', 50 );
  bw_textfield_arr( $option, "Street-address", $options, 'street-address', 50 );
  bw_textfield_arr( $option, "Locality", $options, 'locality', 50 );
  bw_textfield_arr( $option, "Region", $options, 'region', 50 );
  bw_textfield_arr( $option, "Post Code", $options, 'postal-code', 50 );
  bw_textfield_arr( $option, "Country name", $options, 'country-name', 50 );
  
  bw_textarea_arr( $option, _bwtnt( "Google Maps introductory text for", " [bw_show_googlemap$alt0_suffix]" ), $options, 'gmap_intro', 50 );
  bw_textfield_arr( $option, _bwtnt( "Latitude", " [bw_geo$alt0_suffix] [bw_directions$alt0_suffix]" ), $options, 'lat', 50 );
  bw_textfield_arr( $option, _bwtnt( "Longitude", " [bw_show_googlemap$alt0_suffix]" ), $options, 'long', 50 );
}

/**
 * Display the social media "follow me" fields
 * 
 * @param string $option Option name
 * @param array $options Array of options
 * @param string $alt0_suffix 'alt=' parameter 
 */
function oik_follow_me( $option, $options, $alt0_suffix ) {
  bw_textfield_arr( $option, _bwtnt( "Twitter URL", " [bw_twitter$alt0_suffix]" ), $options, 'twitter', 50 );
  bw_textfield_arr( $option, _bwtnt( "Facebook URL", " [bw_facebook$alt0_suffix]" ), $options, 'facebook', 50 );
  bw_textfield_arr( $option, _bwtnt( "LinkedIn URL", " [bw_linkedin$alt0_suffix]" ), $options, 'linkedin', 50 );
  bw_textfield_arr( $option, _bwtnt( "Google Plus URL", " [bw_googleplus$alt0_suffix]" ), $options, 'googleplus', 50 );
  bw_textfield_arr( $option, _bwtnt( "YouTube URL", " [bw_youtube$alt0_suffix]" ), $options, 'youtube', 50 );
  bw_textfield_arr( $option, _bwtnt( "Flickr URL", " [bw_flickr$alt0_suffix]" ), $options, 'flickr', 50 );
  bw_textfield_arr( $option, _bwtnt( "Picasa URL", " [bw_picasa$alt0_suffix]" ), $options, 'picasa', 50 );
  bw_textfield_arr( $option, _bwtnt( "Pinterest URL", " [bw_pinterest$alt0_suffix]" ), $options, 'pinterest', 50 );
  bw_textfield_arr( $option, _bwtnt( "Instagram URL", " [bw_instagram$alt0_suffix]" ), $options, 'instagram', 50 );
  bw_textfield_arr( $option, _bwtnt( "Skype Name", " [bw_skype$alt0_suffix]" ), $options, 'skype', 50 );
	
	// @TODO Decide what shortcode to display, if any " [bw_github$alt0_suffix]"
	bw_textfield_arr( $option, _bwtnt( "GitHub username", null  ), $options, 'github', 50 );
	bw_textfield_arr( $option, _bwtnt( "WordPress.org username", null ), $options, 'wordpress', 50 );
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
  oik_company_info( $option, $options );
  oik_contact_info( $option, $options, $alt0_suffix );
  oik_address_info( $option, $options, $alt0_suffix );

  bw_textfield_arr( $option, "Google Map width", $options, 'width', 10 );
  bw_textfield_arr( $option, "Google Map height", $options, 'height', 10 );
	bw_textfield_arr( $option, "Google Maps API key", $options, 'google_maps_api_key', 39 ); 
  
  bw_textfield_arr( $option, _bwtnt( "Domain", " [bw_domain] [bw_wpadmin]" ), $options, 'domain', 50 );
  
  bw_checkbox_arr( $option, "Do NOT use the oik.css styling. <br />Check this is you don't want to use the oik provide CSS styling", $options, "oikCSS" ); 
  bw_textfield_arr( $option, _bwtnt( "Custom CSS in theme directory:<br />", get_stylesheet_directory_uri() ), $options, 'customCSS', 50 );
	
  bw_textfield_arr( $option, __( "Custom jQuery UI CSS URL", 'oik' ), $options, 'customjQCSS', 90 );
  
  oik_follow_me( $option, $options, $alt0_suffix );
  
  bw_textfield_arr( $option, _bwtnt( "PayPal email", " [bw_paypal]" ), $options, 'paypal-email', 50 );
  bw_textfield_arr( $option, "PayPal country", $options, 'paypal-country', 50 );
  
  /** Extracted from 
      @link https://www.paypalobjects.com/WEBSCR-640-20120609-1/en_US/GB/pdf/PP_OrderManagement_IntegrationGuide.pdf
      
   * AUD Australian Dollar 12,500 AUD
   * CAD Canadian Dollar 12,500 CAD
   * EUR Euro 8,000 EUR
   * GBP Pound Sterling 5,500 GBP
   * JPY Japanese Yen 1,000,000 JPY
   * USD U.S. Dollar 10,000 USD
   * whereas the list below was extracted from a WordPress plugin [bw_plug name="
   
  
  */
  $paypal_currency_list = array("GBP", "USD", "EUR", "AUD", "BRL", "CAD", "CHF", "CZK", "DKK", "HKD", "HUF", "ILS", "JPY", "MXN", "MYR", "NOK", "NZD", "PHP", "PLN", "SEK", "SGD", "THB", "TRY", "TWD");
  
  $paypal_currency_assoc = bw_assoc( $paypal_currency_list );                
  bw_select_arr( $option, "PayPal currency", $options, 'paypal-currency',array( "#options" => $paypal_currency_assoc ) );
  

  $upload_dir = wp_upload_dir();
  $baseurl = $upload_dir['baseurl'];
    
  bw_textfield_arr( $option, _bwtnt( "Logo image ID/URL/in uploads", "<br />$baseurl [bw_logo]" ), $options, 'logo-image', 50 );
  bw_checkbox_arr(  $option, "Use as login logo?", $options, "login-logo" ); 
  bw_textfield_arr( $option, _bwtnt( "QR code image in uploads", " [bw_qrcode]" ), $options, 'qrcode-image', 50 );
  
  $artisteer_versions = array( "na", "41", "40", "31", "30" ,"25" );
  $artisteer_assoc = bw_assoc( $artisteer_versions );
  bw_select_arr( $option, "Artisteer version", $options, 'art-version', array( "#options" => $artisteer_assoc ) );

  $options['yearfrom'] = bw_array_get_dcb( $options, "yearfrom", NULL, "bw_get_yearfrom" );
  bw_textfield_arr( $option, _bwtnt( "Copyright from year", " [bw_copyright]" ), $options, 'yearfrom', 4 );
  

  $options['howdy'] = bw_array_get( $options, "howdy", null );
  bw_textfield_arr( $option, _bwtnt( "'Howdy,'", " replacement string" ), $options, 'howdy', 10 );

  //tablerow( "", "<input type=\"submit\" name=\"ok\" value=\"Save changes\" />" ); 
  //bw_tablerow( array( "", "<input type=\"submit\" name=\"ok\" value=\"Save changes\" class=\"button-primary\"/>") ); 

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
  oik_require( "includes/oik-sc-help.inc" );
  p( "Use the shortcodes in your pages, widgets and titles. e.g." );
  bw_invoke_shortcode( "bw_contact", $alt0_suffix, "Display your contact name" );
  bw_invoke_shortcode( "bw_telephone", $alt0_suffix, "Display your telephone number." );     
  bw_invoke_shortcode( "bw_address", $alt0_suffix, "Display your address." );
  bw_invoke_shortcode( "bw_email", $alt0_suffix, "Display your email address." );
  bw_invoke_shortcode( "bw_show_googlemap", $alt0_suffix, "Display a Googlemap for your primary address." );
  bw_invoke_shortcode( "bw_directions", $alt0_suffix, "Display a button for obtaining directions to your address." );
  bw_invoke_shortcode( "bw_follow_me", $alt0_suffix, "Show all your <b>Follow me</b> buttons" );
  bw_invoke_shortcode( "bw_follow_me", "$alt0_suffix theme=gener", "Show your <b>Follow me</b> buttons using genericons" );
  p( "For more information about the shortcodes you can use select <b>Shortcode help</b>" );
  alink( "button-secondary", admin_url( "admin.php?page=oik_sc_help"), "Shortcode help", "Discover shortcodes you can use" );
  bw_flush();
}

/** 
 * Draw the oik options-1 page
 *
 * This page is for additional fields to enable multiple sets of bw_options, with $alt = 1;
*/
function oik_options_do_page_1() {
  oik_menu_header( "extra shortcode options", "w60pc" );
  oik_box( null, null, "alternative values using alt=1", "oik_extra_shortcode_options" );
  ecolumn();
  scolumn( "w40pc" );
  oik_box( null, null, "usage notes", "oik_extra_usage_notes" );
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
  
  /* We can't use the function blocks until they support the shortcode parameter suffix " alt=1" */ 

  bw_textfield_arr( $option, "Contact [bw_contact alt=1]", $options, 'contact', 50 );
  bw_textfield_arr( $option, "Email [bw_email alt=1]", $options, 'email', 50 );
  bw_textfield_arr( $option, "Telephone [bw_telephone alt=1]", $options, 'telephone', 50 );
  bw_textfield_arr( $option, "Mobile [bw_mobile alt=1]", $options, 'mobile', 50 );
  
  bw_textfield_arr( $option, "Extended-address [bw_address alt=1]", $options, 'extended-address', 50 );
  bw_textfield_arr( $option, "Street-address", $options, 'street-address', 50 );
  bw_textfield_arr( $option, "Locality", $options, 'locality', 50 );
  bw_textfield_arr( $option, "Region", $options, 'region', 50 );
  bw_textfield_arr( $option, "Post Code", $options, 'postal-code', 50 );
  bw_textfield_arr( $option, "Country name", $options, 'country-name', 50 );
  
  bw_textarea_arr( $option, "Google Maps introductory text for [bw_show_googlemap alt=1]", $options, 'gmap_intro', 50 );
  bw_textfield_arr( $option, "Latitude [bw_geo alt=1] [bw_directions alt=1]", $options, 'lat', 50 );
  bw_textfield_arr( $option, "Longitude [bw_show_googlemap alt=1]", $options, 'long', 50 );
 
  //bw_tablerow( array( "", "<input type=\"submit\" name=\"ok\" value=\"Save changes\" class=\"button-primary\"/>") ); 
  etag( "table" ); 
  e( isubmit( "ok", __("Save changes", "oik" ), null, "button-primary" ) ); 
  etag( "form" );
  bw_flush();
}  
  
/**
 * Display additional usage notes
 */
function oik_extra_usage_notes() {
  oik_require( "includes/oik-sc-help.inc" );
  p( "Use the shortcodes in your pages, widgets and titles. e.g." );
  bw_invoke_shortcode( "bw_contact", "alt=1", "Display your alternative contact name." );
  bw_invoke_shortcode( "bw_email", "alt=1 prefix=e-mail", "Display your alternative email address, with a prefix of 'e-mail'." );
  bw_invoke_shortcode( "bw_telephone", "alt=1", "Display your alternative telephone number." );
  bw_invoke_shortcode( "bw_address", "alt=1", "Display your alternative address." );
  bw_invoke_shortcode( "bw_show_googlemap", "alt=1", "Display a Googlemap for your alternative address." );
  bw_invoke_shortcode( "bw_directions", "alt=1", "Display directions to the alternative address." );
  bw_flush();
}

/**
 * Sanitize and validate input. 
 * 
 * @param array $input - Accepts an array
 * @return array - returns a sanitized array.
 */
function oik_options_validate( $input ) {
  $customCSS = bw_array_get( $input, 'customCSS', NULL );
  if ( $customCSS ) {
    $sanfile = sanitize_file_name( $customCSS );
    // Should we check the sanitized file name with the original ?
    bw_create_file( get_stylesheet_directory(), $sanfile, plugin_dir_path( __FILE__ ) . 'custom.css' );  
  }
  
  $input = oik_set_latlng( $input ); 
  
  return $input;
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
  
  if ( $customCSS != NULL ) {
    global $wp_version; 
    $link = network_admin_url( "theme-editor.php" );
    $link .= '?file=';
    //$link .= 'oik/custom.css';
    if ( version_compare( $wp_version, '3.4', "ge" ) ) {
      $link .= $customCSS;
    } else {  
      $link .= path_join( get_stylesheet_directory(), $customCSS );
    }  
    $link .= "&theme=$theme";
    //$img = retimage( null, oik_url( 'images/editcss_48.png') , "Edit custom CSS" );
    $img = "{}";
    alink( "bw_custom_css", $link, $img, "Edit custom CSS" ); 
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
  oik_menu_header( "shortcode help", "w95pc" );
  oik_box( null, null, "About shortcodes", "oik_code_about" );
  
  $shortcode = bw_array_get( $_REQUEST, "code", null );
  if ( $shortcode ) {
    oik_box( null, NULL, "$shortcode - more information", "oik_code_example" );
    oik_box( null, null, "$shortcode - snippet", "oik_code_snippet" ); 
  }
  oik_require( "shortcodes/oik-codes.php" );
  oik_box( null, null, "Shortcode summary", "oik_code_table" );
  oik_menu_footer();
  bw_flush();
}  

/**
 * Display the introduction to oik shortcode help
 */
function oik_code_about() {
  p( "This page lists all the currently active shortcodes. To find out more information about a shortcode click on the shortcode name in the Help column." );
  p( "Depending on how the shortcode is implemented you will either be shown some more information with one or more examples, or an 'oik generated example.' "); 
  p( "You will also be shown the HTML snippet for the example. There should be no need to do anything with this output." );
  p( "For further information on a shortcode or its parameters click on the links in the Syntax column." );
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
 * oik-plugins.co.uk is the test site so we don't break oik-plugins.com
 * as we test oik v1.17 and the transition to oik v2.0
 */
define( 'OIK_PLUGINS_COM', "http://www.oik-plugins.com" );
define( 'OIK_PLUGINS_COUK', "http://www.oik-plugins.co.uk" );

/** 
 * Return the URL for the Premium (Pro) or Freemium version
 * 
 * THIS could also append the other parameters such as the key value pairs for registration codes
 * We're trying to find the value for 
 * 
 * $transient->response[$plugin_slug]->url
 

  // define( 'BW_OIK_PLUGINS_SERVER', "http://qw/wpit" );     
  // $url = "http://api.wordpress.org/plugins/info/1.0/";
  // $url = "http://qw/wpit/plugins/update-check/1.0/";
  // $url = "http://qw/wpit/plugins/update-check/";

 * 
 */
if ( !function_exists( "oik_get_plugins_server" ) ) { 
function oik_get_plugins_server() {
  if ( defined( 'BW_OIK_PLUGINS_SERVER' )) {
    $url = BW_OIK_PLUGINS_SERVER;
  } else {
		if ( !defined( "OIK_PLUGINS_COM" ) ) {
			define( "OIK_PLUGINS_COM", "http://www.oik-plugins.com" );
		}
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
	
		$text = "WordPress has previously determined that there is a new version available: ";
		$text .= $new_version;
		$text .= "<br />";
		$text .= "Current version: ";
		$text .= $version;
		$text .= "<br />However, this is a symlinked plugin, located at <br /> ";
		$text .= $plugin_data['real_path'];
		$text .= "&nbsp;";
		$text .= "<br /><strong>So please don't attempt to apply updates.</strong>";

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
	//if ( $new_version != $version ) {
		$text = "This plugin is a Git repository.";
	
		$text .= "New version?: ";
		$text .= $new_version;
		$text .= "<br />";
		$text .= "Current version: ";
		$text .= $version;
		//$text .= "<br />However, this is a symlinked plugin, located at <br /> ";
		//$text .= $plugin_data['real_path'];
		$text .= "&nbsp;";
		$text .= "<br /><strong>Please don't attempt to apply updates using WordPress.</strong>";

		$message = '<tr class="plugin-update-tr">';
		$message .= '<td colspan="3" class="plugin-update colspanchange">';
		$message .= '<div class="update-message">';
		$message .= $text;
		$message .= "</div>";
		$message .= "</td>";
		$message .= "</tr>";
		
		echo $message;
 // }
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



