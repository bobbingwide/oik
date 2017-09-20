<?php // (C) Copyright Bobbing Wide 2017

class Tests_oik_admin extends BW_UnitTestCase {

	
	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 * - require the libraries that would be loaded as standard for wp-admin processing
	 */
	function setUp() {
		parent::setUp();
		//bobbcomp::bw_get_option( "fred" );
		oik_require( "admin/oik-admin.inc" );
		oik_require_lib( "oik_plugins" );
		oik_require_lib( "oik_themes" );
		oik_require_lib( "class-oik-update" );
	}
	
	function test_oik_callback() {
		$this->switch_to_locale( 'en_GB' );
		$html = bw_ret( oik_callback() );
		$expected = "<p>This box intentionally left blank</p>";
		$this->assertEquals( $expected, $html );
	}
	
	function test_oik_callback_bb_BB() {
		$this->switch_to_locale( 'bb_BB' );
		$html = bw_ret( oik_callback() );
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	function test_oik_shortcode_options() {
		//oik_require( "admin/oik-admin.inc" );
		$this->switch_to_locale( "en_GB" );
		$html = bw_ret( oik_shortcode_options() );
		$html = $this->replace_admin_url( $html );
		
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	function test_oik_shortcode_options_bb_BB() {
		//oik_require( "admin/oik-admin.inc" );
		$this->switch_to_locale( 'bb_BB' );
		$html = bw_ret( oik_shortcode_options() );
		$html = $this->replace_admin_url( $html );
		
		$html_array = $this->tag_break( $html );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	/**
	 * Test the oik custom CSS box
	 * 
	 * Note: This only tests one of the routes. 
	 * Both routes are tested in the bb_BB version.
	 */
	function test_oik_custom_css_box() {
	
		$this->switch_to_locale( 'en_GB' );
		//oik_require( "admin/oik-admin.inc" );
		$html = bw_ret( oik_custom_css_box() );
		$html = $this->replace_admin_url( $html );
		
		$custom_CSS = bw_get_option( "customCSS"	);
		$expected = null;
		$expected .= '<p>To style the output from shortcodes you can create and edit a custom CSS file for your theme.</p>';
		$expected .= '<p>Use the [bw_editcss] shortcode to create the <b>edit CSS</b> link anywhere on your website.</p>';
		$expected .= '<p>Note: If you change themes then you will start with a new custom CSS file.</p>';
		$expected .= '<p>You should save your custom CSS file before updating your theme.</p>';
		if ( !$custom_CSS ) {
			$expected .=  '<p>You have not defined a custom CSS file.</p>';
			$expected .= '<a class="button-secondary" href="https://qw/src/wp-admin/admin.php?page=oik_options" title="Enter the name of your custom CSS file on the Options page">Name your custom CSS file</a>';
		} else {
			$expected .= '<p>Click on this button to edit your custom CSS file.</p>';
			$expected .= '<a class="bw_custom_css" href="https://qw/src/wp-admin/theme-editor.php?file=custom.css&theme=genesis-image" title="Edit custom CSS">{}</a>';
      $theme = bw_get_theme();
			$expected = str_replace( "theme=genesis-image", "theme=" . $theme, $expected );
		}
		$this->assertEquals( $expected, $html );
	}
	
	/**
	 */
	function test_oik_plugins_servers() {
		$html = bw_ret( oik_plugins_servers() );
    $html = $this->replace_admin_url( $html );
		$expected = null;
		$expected .= '<p>Some oik plugins and themes are supported from servers other than WordPress.org</p>';
		$expected .= '<p>Premium plugin and theme versions require API keys.</p>';
		$expected .= '<p>Use the Plugins page to manage oik plugins servers and API keys</p>';
		$expected .= '<a class="button-secondary" href="https://qw/src/wp-admin/admin.php?page=oik_plugins" title="Manage plugin servers and API keys">Plugins</a>';
		$expected .= '<p>Use the Themes page to manage oik themes servers and API keys</p>';
		$expected .= '<a class="button-secondary" href="https://qw/src/wp-admin/admin.php?page=oik_themes" title="Manage theme servers and API keys">Themes</a>';
    $this->assertEquals( $expected, $html );
	}
	
	/**
	 * Test the oik Buttons section
	 * 
	 * Since we invoke bw_flush() we need to capture the output buffer
	 * to apply changes before comparing with expected.
	 */
	function test_oik_tinymce_buttons() {
	
		ob_start();   
		oik_tinymce_buttons();
		
		$html = ob_get_contents();
		ob_end_clean();
		$html = $this->replace_oik_url( $html );
		$html_array = $this->tag_break( $html );
		$html_array = $this->replace_nonce_with_nonsense( $html_array );
		
		//$this->generate_expected( $html_array );
		
$expected = array();
$expected[] = '<form method="post" action="options.php">';
$expected[] = '<table class="form-table">';
$expected[] = '<input type=\'hidden\' name=\'option_page\' value=\'oik_buttons_options\' />';
$expected[] = '<input type="hidden" name="action" value="update" />';
$expected[] = '<input type="hidden" id="_wpnonce" name="_wpnonce" value="nonsense" />';
$expected[] = '<input type="hidden" name="_wp_http_referer" value="/" />';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_buttons[oik-button-shortcodes]">';
$expected[] = '<img class="" src="https://qw/src/wp-content/plugins/oik/admin/bw-bn-icon.gif" title="Button shortcodes" alt="Button shortcodes"  /> Button shortcodes</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="hidden" name="bw_buttons[oik-button-shortcodes]" value="0" />';
$expected[] = '<input type="checkbox" name="bw_buttons[oik-button-shortcodes]" id="bw_buttons[oik-button-shortcodes]" checked="checked"/>';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_buttons[oik-paypal-shortcodes]">';
$expected[] = '<img class="" src="https://qw/src/wp-content/plugins/oik/admin/bw-pp-icon.gif" title="PayPal shortcodes" alt="PayPal shortcodes"  /> PayPal shortcodes</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="hidden" name="bw_buttons[oik-paypal-shortcodes]" value="0" />';
$expected[] = '<input type="checkbox" name="bw_buttons[oik-paypal-shortcodes]" id="bw_buttons[oik-paypal-shortcodes]" checked="checked"/>';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_buttons[oik-shortc-shortcodes]">';
$expected[] = '<img class="" src="https://qw/src/wp-content/plugins/oik/admin/bw-sc-icon.gif" title="ALL shortcodes" alt="ALL shortcodes"  /> ALL shortcodes</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="hidden" name="bw_buttons[oik-shortc-shortcodes]" value="0" />';
$expected[] = '<input type="checkbox" name="bw_buttons[oik-shortc-shortcodes]" id="bw_buttons[oik-shortc-shortcodes]"/>';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_buttons[oik-quicktags]">[] quicktag for HTML editor</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="hidden" name="bw_buttons[oik-quicktags]" value="0" />';
$expected[] = '<input type="checkbox" name="bw_buttons[oik-quicktags]" id="bw_buttons[oik-quicktags]" checked="checked"/>';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_buttons[oik-shortcake]">Integrate with shortcake</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="hidden" name="bw_buttons[oik-shortcake]" value="0" />';
$expected[] = '<input type="checkbox" name="bw_buttons[oik-shortcake]" id="bw_buttons[oik-shortcake]"/>';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '</table>';
$expected[] = '<input type="submit" name="ok" value="Save changes" class="button-primary" />';
$expected[] = '</form>';
		
		$this->assertEquals( $expected, $html_array );
	}


	/**
	 * Test links to the oik documentation
	 *
	 * The oik documentation is on oik-plugins.com
	 * The forum is on wordpress.org
	 */
	function test_oik_documentation() {
		$html = bw_ret( oik_documentation() );
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	} 
	
	
	/**
	 * Test the PayPal donate button 
	 *
	 * Note: Expects paypal_country to be set to "United Kingdom" and currency set to GBP
	 * @TODO Replace with actual value
	 */
	function test_oik_support() {
		$html = bw_ret( oik_support() ) ;
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	/**
	 * Test the oik admin options
	 *
	 * We have to force the flush to get all the html that's generated.
	 */
	function test_oik_admin_options_on() { 
	
		bw_update_option( "show_ids", "on", "bw_admin_options" );
		
		ob_start(); 
		oik_admin_options();
		bw_flush();  
		$html = ob_get_contents();
		ob_end_clean();
		
		$html_array = $this->tag_break( $html );
		$html_array = $this->replace_nonce_with_nonsense( $html_array );
    //$this->generate_expected( $html_array );
		
		$expected = array();
$expected[] = '<form method="post" action="options.php">';
$expected[] = '<table class="form-table">';
$expected[] = '<input type=\'hidden\' name=\'option_page\' value=\'oik_admin_options\' />';
$expected[] = '<input type="hidden" name="action" value="update" />';
$expected[] = '<input type="hidden" id="_wpnonce" name="_wpnonce" value="nonsense" />';
$expected[] = '<input type="hidden" name="_wp_http_referer" value="/" />';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_admin_options[show_ids]">Show IDs on admin pages</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="hidden" name="bw_admin_options[show_ids]" value="0" />';
$expected[] = '<input type="checkbox" name="bw_admin_options[show_ids]" id="bw_admin_options[show_ids]" checked="checked"/>';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '</table>';
$expected[] = '<input type="submit" name="ok" value="Save changes" class="button-secondary" />';
$expected[] = '</form>';

		$this->assertEquals( $expected, $html_array );
	}
		
	function test_oik_admin_options_off() {
	
		bw_update_option( "show_ids", "0", "bw_admin_options" );
		
		ob_start(); 
		oik_admin_options();
		bw_flush();  
		$html = ob_get_contents();
		ob_end_clean();
		
		$html_array = $this->tag_break( $html );
		$html_array = $this->replace_nonce_with_nonsense( $html_array );
    //$this->generate_expected( $html_array );
		$expected = array();
$expected[] = '<form method="post" action="options.php">';
$expected[] = '<table class="form-table">';
$expected[] = '<input type=\'hidden\' name=\'option_page\' value=\'oik_admin_options\' />';
$expected[] = '<input type="hidden" name="action" value="update" />';
$expected[] = '<input type="hidden" id="_wpnonce" name="_wpnonce" value="nonsense" />';
$expected[] = '<input type="hidden" name="_wp_http_referer" value="/" />';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_admin_options[show_ids]">Show IDs on admin pages</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="hidden" name="bw_admin_options[show_ids]" value="0" />';
$expected[] = '<input type="checkbox" name="bw_admin_options[show_ids]" id="bw_admin_options[show_ids]"/>';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '</table>';
$expected[] = '<input type="submit" name="ok" value="Save changes" class="button-secondary" />';
$expected[] = '</form>';
	$this->assertEquals( $expected, $html_array );
		
		
	}
	
	/**
	 * Tests oik_menu_header
	 * 
	 * @TODO Test oik_enqueue_scripts() separately
	 */
	function test_oik_menu_header() {
		$this->setExpectedDeprecated( "bw_translate" );
	 	oik_menu_header( "Menu header title", "menu header class" );
		$html = bw_ret();
		$html_array = $this->tag_break( $html );
		$html_array = $this->replace_nonce_with_nonsense( $html_array, "closedpostboxesnonce", "closedpostboxesnonce" );
		//$this->generate_expected( $html_array );
    $expected = array();
		$expected[] = '<input type="hidden" id="closedpostboxesnonce" name="closedpostboxesnonce" value="nonsense" />';
		$expected[] = '<div class="wrap">';
		$expected[] = '<h2>Menu header title</h2>';
		$expected[] = '<div class="metabox-holder">';
		$expected[] = '<div class="postbox-container menu header class">';
		$expected[] = '<div class="meta-box-sortables ui-sortable">';
		$this->assertEquals( $expected, $html_array );
	}
	
	
	/**
	 * Tests oik_menu_footer
	 */
	function test_oik_menu_footer() {
	
	 	oik_menu_footer();
		$html = bw_ret();
		$html_array = $this->tag_break( $html );
		//$this->generate_expected( $html_array );
    $expected = array();
		$expected[] = '<!--start ecolumn-->';
		$expected[] = '</div>';
		$expected[] = '</div>';
		$expected[] = '</div>';
		$expected[] = '<!--end ecolumn-->';
		$expected[] = '<div class="clear">';
		$expected[] = '</div>';
		$expected[] = '</div>';
		$this->assertEquals( $expected, $html_array );
	
	}
	
	/**
	 * Tests oik_plugins_do_page
	 *
	 * tests oik_plugins_server_settings 
	 * eventually tests oik_lazy_plugins_server_settings() - the original display
	 *
	 * To reduce the output we need to fiddle the bw_plugins option to reduce the number of registered plugins. 
	 * Also null $bw_registered_plugins OR set a known value.
	 * Let's just try an empty array.
	 * @TODO Cater for the plugin version
	 */
	function test_oik_plugins_do_page() {
		global $bw_registered_plugins;
		$bw_plugins = array( "us-tides" => array( "server" => "https://example.com"
																						, "apikey" => "sampleapikey"
																						, "expiration" => "no longer relevant"
																						)
											);
		update_option( "bw_plugins", $bw_plugins );
		$bw_registered_plugins = null;
		ob_start(); 
		oik_plugins_do_page();
		$html = ob_get_contents();
		ob_end_clean();
		$this->assertNotNull( $html );
		$html = $this->replace_admin_url( $html );
		$html = str_replace( oik_get_plugins_server(), "http://qw/oikcom", $html );
		$html_array = $this->tag_break( $html );
		
		$this->assertNotNull( $html_array );
		// @TODO Implement nonce checking in oik_lazy_plugins_server_settings
		$html_array = $this->replace_nonce_with_nonsense( $html_array, "closedpostboxesnonce", "closedpostboxesnonce" );
		// $this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	
	}
	
	/**
	 * Test oik_plugins_add_settings
	 */
	function test_oik_plugins_add_settings() {
		$html = bw_ret( oik_plugins_add_settings() );
		$this->assertNotNull( $html );
		$html = $this->replace_admin_url( $html );
		$html = str_replace( oik_get_plugins_server(), "http://qw/oikcom", $html );
		$html_array = $this->tag_break( $html );
		
		$this->assertNotNull( $html_array );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}

	/**
	 * Test oik_plugins_edit_settings
	 */
	function test_oik_plugins_edit_settings() {
		$html = bw_ret( oik_plugins_edit_settings() );
		$this->assertNotNull( $html );
		$html = $this->replace_admin_url( $html );
		$html = str_replace( oik_get_plugins_server(), "http://qw/oikcom", $html );
		$html_array = $this->tag_break( $html );
		
		$this->assertNotNull( $html_array );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}

	/**
	 * Tests oik_themes_do_page
	 * 
	 * 
	 * tests oik_themes_server_settings 
	 * eventually tests oik_lazy_themes_server_settings() - the original display
	 *
	 * @TODO Cater for the plugin version
	 */
 function test_oik_themes_do_page() {
		global $bw_registered_themes;
		$bw_themes = get_option( "bw_themes" );
		$bw_themes = array( "genesis-oik" => array( "server" => "https://example.com"
																						, "apikey" => "sampleapikey"
																						, "expiration" => "no longer relevant"
																						)
											);
		update_option( "bw_themes", $bw_themes );
		$bw_registered_themes = null;
		ob_start(); 
		oik_themes_do_page();
		$html = ob_get_contents();
		ob_end_clean();
		$this->assertNotNull( $html );
		$html = $this->replace_admin_url( $html );
		$html = str_replace( oik_update::oik_get_themes_server(), "http://qw/oikcom", $html );
		$html_array = $this->tag_break( $html );
		
		$this->assertNotNull( $html_array );
		// @TODO Implement nonce checking in oik_lazy_plugins_server_settings
    //$this->generate_expected( $html_array );
		
		$html_array = $this->replace_nonce_with_nonsense( $html_array, "closedpostboxesnonce", "closedpostboxesnonce" );
    $expected = array();
$expected = array();
$expected[] = '<input type="hidden" id="closedpostboxesnonce" name="closedpostboxesnonce" value="nonsense" />';
$expected[] = '<div class="wrap">';
$expected[] = '<h2>theme server settings</h2>';
$expected[] = '<div class="metabox-holder">';
$expected[] = '<div class="postbox-container w100pc">';
$expected[] = '<div class="meta-box-sortables ui-sortable">';
$expected[] = '<div class="postbox " id="oik_themes_settings">';
$expected[] = '<button type="button" class="handlediv" aria-expanded="true">';
$expected[] = '<span class="screen-reader-text">Toggle panel: Settings</span>';
$expected[] = '<span class="toggle-indicator" aria-hidden="true">';
$expected[] = '</span>';
$expected[] = '</button>';
$expected[] = '<h3 class="hndle">Settings</h3>';
$expected[] = '<div class="inside">';
$expected[] = '<p>The default oik themes server is currently set to: <a href="http://qw/oikcom" title="default oik themes server">http://qw/oikcom</a>';
$expected[] = '</p>';
$expected[] = '<form method="post">';
$expected[] = '<table class="widefat ">';
$expected[] = '<thead>';
$expected[] = '<tr>';
$expected[] = '<td>theme</td>';
$expected[] = '<td>version</td>';
$expected[] = '<td>server</td>';
$expected[] = '<td>apikey</td>';
$expected[] = '<td>actions</td>';
$expected[] = '</tr>';
$expected[] = '</thead>';
$expected[] = '<tr>';
$expected[] = '<td>genesis-oik</td>';
$expected[] = '<td>1.0.8&nbsp;</td>';
$expected[] = '<td>https://example.com&nbsp;</td>';
$expected[] = '<td>sampleapikey&nbsp;</td>';
$expected[] = '<td>';
$expected[] = '<a href="https://qw/src/wp-admin/admin.php?page=oik_themes&amp;delete_theme=genesis-oik" title="Delete theme&#039;s profile entry">Delete</a>&nbsp;<a href="https://qw/src/wp-admin/admin.php?page=oik_themes&amp;edit_theme=genesis-oik" title="Edit">Edit</a>&nbsp;<a href="https://qw/src/wp-admin/admin.php?page=oik_themes&amp;check_theme=genesis-oik&amp;check_version=1.0.8" title="Check">Check</a>&nbsp;</td>';
$expected[] = '</tr>';
$expected[] = '</table>';
$expected[] = '<p>';
$expected[] = '<input type="submit" name="_oik_themes_add_theme" value="Add theme" class="button-primary" />';
$expected[] = '</p>';
$expected[] = '</form>';
$expected[] = '</div>';
$expected[] = '</div>';
$expected[] = '<!--start ecolumn-->';
$expected[] = '</div>';
$expected[] = '</div>';
$expected[] = '</div>';
$expected[] = '<!--end ecolumn-->';
$expected[] = '<div class="clear">';
$expected[] = '</div>';
$expected[] = '</div>';
		$this->assertEquals( $expected, $html_array );
	}
	
	/**
	 * Tests oik_options_do_page
	 * 
	 * tests: oik_main_shortcode_options and oik_usage_notes
	 * plus:  
   * - oik_contact_numbers
	 * - oik_company_info
	 * - oik_contact_info
	 * - oik_address_info
	 * - oik_follow_me
	 * 
	 * @TODO In order to support testing in another installation
	 * we'll need to set all the bw_options fields to the defaults or something else.
	 */
	function test_oik_options_do_page() {
	
		$bw_options = get_option( "bw_options" );
		$bw_options['telephone'] = "+44 (0)2392 410090";
		$bw_options['mobile'] = "+44 (0)7876 236864";
		update_option( "bw_options", $bw_options );
	
		ob_start(); 
		oik_options_do_page();
		$html = ob_get_contents();
		ob_end_clean();
		$this->assertNotNull( $html );
		$html = $this->replace_admin_url( $html );
		$html_array = $this->tag_break( $html );
		
		$this->assertNotNull( $html_array );
		// @TODO Implement nonce checking in oik_lazy_plugins_server_settings
		$html_array = $this->replace_nonce_with_nonsense( $html_array );
		
		$html_array = $this->replace_nonce_with_nonsense( $html_array, "closedpostboxesnonce", "closedpostboxesnonce" );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	/**
	 * Tests oik_options_do_page_1
	 *
	 * - Tests: oik_extra_shortcode_options, oik_extra_usage_notes
	 */
	function test_oik_options_do_page_1() {
		ob_start(); 
		oik_options_do_page_1();
		$html = ob_get_contents();
		ob_end_clean();
		$this->assertNotNull( $html );
		$html = $this->replace_admin_url( $html );
		$html_array = $this->tag_break( $html );
		$this->assertNotNull( $html_array );
		// @TODO Implement nonce checking ?
		$html_array = $this->replace_nonce_with_nonsense( $html_array );
		$html_array = $this->replace_antispambot( $html_array );
		$html_array = $this->replace_nonce_with_nonsense( $html_array, "closedpostboxesnonce", "closedpostboxesnonce" );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		
	}
	
	
	/**
	 * We want to ensure that only a few shortcodes are registered
	 * so we can fiddle what happens during oik_add_shortcodes
	 * - removing all shortcodes except bw
	 *
	 * We need to set $_REQUEST['code'] to that shortcode
	 *
	 */
	function test_oik_help_do_page() {
		add_action( "oik_add_shortcodes", [$this, "remove_most_shortcodes"], 9999 );
		ob_start(); 
		oik_help_do_page();
		$html = ob_get_contents();
		ob_end_clean();
		$this->assertNotNull( $html );
		$html = $this->replace_admin_url( $html );
		$html = str_replace( oik_get_plugins_server(), "http://qw/oikcom", $html );
		$html_array = $this->tag_break( $html );
		$this->assertNotNull( $html_array );
		// @TODO Implement nonce checking ?
		//$html_array = $this->replace_nonce_with_nonsense( $html_array );
		
		$html_array = $this->replace_nonce_with_nonsense( $html_array, "closedpostboxesnonce", "closedpostboxesnonce" );
    //$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	function remove_most_shortcodes() {
		remove_all_shortcodes();
		bw_add_shortcode( 'bw', 'bw_bw', oik_path( "shortcodes/oik-bw.php" ) );
	}
	
	
	function test_assertArrayEqualsFile() {
		$expected = '<p>Test assert array equals file</p>';
		$this->assertArrayEqualsFile( $expected, "tests/data/en_US/test_assertArrayEqualsFile.html" );
	}
	
	function test_assertArrayEqualsFileUnspecified() {
		$expected = array();
		$expected[] = '<p>Test assert array equals file</p>';
		$expected[] = '<p>Unspecified</p>';
		$this->assertArrayEqualsFile( $expected );
	}
	
	/**
	 * Switch to the required target language
	 * 
	 * - WordPress core's switch_to_locale() function leaves much to be desired when the default language is en_US
	 * - and/or when the translations are loaded from the plugin's language folders rather than WP_LANG_DIR
	 * - We have to (re)load the language files ourselves.
	 * 
	 * @TODO We also need to remember to pass the slug/domain to translate() :-)
	 *
	 * Note: For switch_to_locale() see https://core.trac.wordpress.org/ticket/26511 and https://core.trac.wordpress.org/ticket/39210 
	 */
	function switch_to_locale( $locale='bb_BB' ) {
		$tdl = is_textdomain_loaded( "oik" );
		$this->assertTrue( $tdl );
		$switched = switch_to_locale( $locale );
		if ( $switched ) {
			$this->assertTrue( $switched );
		}
		$new_locale = $this->query_la_CY();
		$this->assertEquals( $locale, $new_locale );
		$this->reload_domains();
		$tdl = is_textdomain_loaded( "oik" );
		$this->assertTrue( $tdl );
		//$this->test_domains_loaded();
		if ( $locale === 'bb_BB' ) {
			$bw = translate( "bobbingwide", "oik" );
			$this->assertEquals( "bboibgniwde", $bw );
		}	
			
	}
	
	/**
	 * Reloads the text domains
	 * 
	 * - Loading oik-libs from oik-libs invalidates tests where the plugin is delivered from WordPress.org so oik-libs won't exist.
	 * - but we do need to reload oik's text domain 
	 * - and cause the null domain to be rebuilt.
	 */
	function reload_domains() {
		$domains = array( "oik" );
		foreach ( $domains as $domain ) {
			$loaded = bw_load_plugin_textdomain( $domain );
			$this->assertTrue( $loaded, "$domain not loaded" );
		}
		oik_require_lib( "oik-l10n" );
		oik_l10n_enable_jti();
	}
	
	/**
	 * 	Tests that language files have been loaded
	 * 
	 * - Locale must be en_GB for this test to work since
	 * - With locale en_US we don't expect there to be any .mo files with -en_US suffix
	 * - so these would fail to load
	 */
	function test_domains_loaded() {
		//var_dump( debug_backtrace() );
	
		global $l10n;
		$is_array = is_array( $l10n );
		$this->assertTrue( $is_array, "l10n is not an array" );
		$count = count( $l10n );
		$more_than_one = $count > 1;
		$this->assertTrue( $more_than_one, "there is not more than one domain loaded" );
		//print_r( $l10n );
		//print_r( $domains );
	}
	
	/**
	 * We want to ensure that only a few shortcodes are registered
	 * so we can fiddle what happens during oik_add_shortcodes
	 * - removing all shortcodes except bw
	 *
	 * We need to set $_REQUEST['code'] to that shortcode
	 *
	 */
	function test_oik_help_do_page_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		add_action( "oik_add_shortcodes", [$this, "remove_most_shortcodes"], 9999 );
		ob_start(); 
		oik_help_do_page();
		$html = ob_get_contents();
		ob_end_clean();
		$this->assertNotNull( $html );
		$html = $this->replace_admin_url( $html );
		$html = str_replace( oik_get_plugins_server(), "http://qw/oikcom", $html );
		$html_array = $this->tag_break( $html );
		
		$this->assertNotNull( $html_array );
		
		// file will be tests/data/bb_BB/test_oik_help_do_page_bb_BB.html
		//Failed asserting that file "tests/data/bb_BB/test_oik_help_do_page_bb_BB.html" exists.
		
		$html_array = $this->replace_nonce_with_nonsense( $html_array, "closedpostboxesnonce", "closedpostboxesnonce" );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	/**
	 * Tests oik_plugins_do_page for bb_BB
	 * 
	 */
 function test_oik_plugins_do_page_bb_BB() {
 
		$this->switch_to_locale( "bb_BB" );
		global $bw_registered_plugins;
		$bw_plugins = array( "us-tides" => array( "server" => "https://example.com"
																						, "apikey" => "sampleapikey"
																						, "expiration" => "no longer relevant"
																						)
											);
		update_option( "bw_plugins", $bw_plugins );
		$bw_registered_plugins = null;
		ob_start(); 
		oik_plugins_do_page();
		$html = ob_get_contents();
		ob_end_clean();
		$this->assertNotNull( $html );
		$html = $this->replace_admin_url( $html );
		$html = str_replace( oik_get_plugins_server(), "http://qw/oikcom", $html );
		$html_array = $this->tag_break( $html );
		
		$this->assertNotNull( $html_array );
		// @TODO Implement nonce checking in oik_lazy_plugins_server_settings
		$html_array = $this->replace_nonce_with_nonsense( $html_array, "closedpostboxesnonce", "closedpostboxesnonce" );
    $expected = array();
	  //$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	
	/**
	 * Test oik_plugins_add_settings for bb_BB
	 */
	function test_oik_plugins_add_settings_bb_BB() {
	
		$this->switch_to_locale( "bb_BB" );
		$html = bw_ret( oik_plugins_add_settings() );
		$this->assertNotNull( $html );
		//$html = $this->replace_admin_url( $html );
		//$html = str_replace( oik_get_plugins_server(), "http://qw/oikcom", $html );
		$html_array = $this->tag_break( $html );
		
		$this->assertNotNull( $html_array );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	/**
	 * Test oik_plugins_edit_settings for bb_BB
	 */
	function test_oik_plugins_edit_settings_bb_BB() {
	
		$this->switch_to_locale( "bb_BB" );
		$html = bw_ret( oik_plugins_edit_settings() );
		$this->assertNotNull( $html );
		//$html = $this->replace_admin_url( $html );
		//$html = str_replace( oik_get_plugins_server(), "http://qw/oikcom", $html );
		$html_array = $this->tag_break( $html );
		
		$this->assertNotNull( $html_array );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	/**
	 * Tests oik_themes_do_page for bb_BB
	 * 
	 * 
	 * tests oik_themes_server_settings 
	 * eventually tests oik_lazy_themes_server_settings() - the original display
	 *
	 * @TODO Cater for the plugin version
	 */
 function test_oik_themes_do_page_bb_BB() {
 
		$this->switch_to_locale( "bb_BB" );
		global $bw_registered_themes;
		$bw_themes = get_option( "bw_themes" );
		$bw_themes = array( "genesis-oik" => array( "server" => "https://example.com"
																						, "apikey" => "sampleapikey"
																						, "expiration" => "no longer relevant"
																						)
											);
		update_option( "bw_themes", $bw_themes );
		$bw_registered_themes = null;
		ob_start(); 
		oik_themes_do_page();
		$html = ob_get_contents();
		ob_end_clean();
		$this->assertNotNull( $html );
		$html = $this->replace_admin_url( $html );
		$html = str_replace( oik_update::oik_get_themes_server(), "http://qw/oikcom", $html );
		$html_array = $this->tag_break( $html );
		
		$this->assertNotNull( $html_array );
		
		$html_array = $this->replace_nonce_with_nonsense( $html_array, "closedpostboxesnonce", "closedpostboxesnonce" );
		
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	
	/**
	 * Test oik_themes_add_settings for bb_BB
	 */
	function test_oik_themes_add_settings_bb_BB() {
	
		$this->switch_to_locale( "bb_BB" );
		$html = bw_ret( oik_themes_add_settings() );
		$this->assertNotNull( $html );
		//$html = $this->replace_admin_url( $html );
		//$html = str_replace( oik_get_themes_server(), "http://qw/oikcom", $html );
		$html_array = $this->tag_break( $html );
		
		$this->assertNotNull( $html_array );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	/**
	 * Test oik_themes_edit_settings for bb_BB
	 */
	function test_oik_themes_edit_settings_bb_BB() {
	
		$this->switch_to_locale( "bb_BB" );
		$html = bw_ret( oik_themes_edit_settings() );
		$this->assertNotNull( $html );
		//$html = $this->replace_admin_url( $html );
		//$html = str_replace( oik_get_themes_server(), "http://qw/oikcom", $html );
		$html_array = $this->tag_break( $html );
		
		$this->assertNotNull( $html_array );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	/**
	 * Tests oik_options_do_page_1 for bb_BB locale
	 *
	 * Tests: 
	 * oik_extra_shortcode_options
	 * oik_extra_usage_notes
	 */
	function test_oik_options_do_page_1_bb_BB() {
		do_action( "oik_add_shortcodes" );
	
		$this->switch_to_locale( "bb_BB" );
		ob_start(); 
		oik_options_do_page_1();
		$html = ob_get_contents();
		ob_end_clean();
		$this->assertNotNull( $html );
		$html = $this->replace_admin_url( $html );
		$html_array = $this->tag_break( $html );
		
		$this->assertNotNull( $html_array );
		// @TODO Implement nonce checking ?
		$html_array = $this->replace_nonce_with_nonsense( $html_array );
		$html_array = $this->replace_antispambot( $html_array );
		$html_array = $this->replace_nonce_with_nonsense( $html_array, "closedpostboxesnonce", "closedpostboxesnonce" );
		//$this->generate_expected_file( $html_array );
		
		$this->assertArrayEqualsFile( $html_array );
	}
	
	/** 
	 * Tests oik_buttons_do_page for bb_BB
	 */
	 function test_oik_button_do_page_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		ob_start();
		oik_buttons_do_page();
		$html = ob_get_contents();
		ob_end_clean();
		$this->assertNotNull( $html );
	 
		$html = $this->replace_admin_url( $html );
		$html_array = $this->tag_break( $html );
		
		$this->assertNotNull( $html_array );
		// @TODO Implement nonce checking ?
		$html_array = $this->replace_nonce_with_nonsense( $html_array );
		$html_array = $this->replace_nonce_with_nonsense( $html_array, "closedpostboxesnonce", "closedpostboxesnonce" );
		//$this->generate_expected_file( $html_array );
		
		$this->assertArrayEqualsFile( $html_array );
	}

	/**
	 * Tests oik_support for bb_BB
	 *
	 * Note: Expects paypal_country to be set to "United Kingdom" and currency set to GBP
	 * @TODO Replace with actual value
	 */
	function test_oik_support_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = bw_ret( oik_support() ) ;
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	
	/**
	 * Test the oik admin options for bb_BB
	 *
	 * We have to force the flush to get all the html that's generated.
	 */
	function test_oik_admin_options_on_bb_BB() { 
		$this->switch_to_locale( "bb_BB" );
		bw_update_option( "show_ids", "on", "bw_admin_options" );
		ob_start(); 
		oik_admin_options();
		bw_flush();  
		$html = ob_get_contents();
		ob_end_clean();
		$html_array = $this->tag_break( $html );
		$html_array = $this->replace_nonce_with_nonsense( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	/**
	 * Test the oik custom CSS box for bb_BB
	 */
	function test_oik_custom_css_box_not_defined_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		bw_update_option( "customCSS", "" );
		$html = bw_ret( oik_custom_css_box() ); 		
		$custom_CSS = bw_get_option( "customCSS"	);
		$this->assertEquals( "", $custom_CSS );
		$html = $this->replace_admin_url( $html );
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	
	/**
	 * Test the oik custom CSS box for bb_BB
	 * 
	 * Note: This only tests one of the routes. 
	 * @TODO We need to set or unset 'customCSS' to test the other one. And perhaps another value for custom.css ?
	 * 
	 */
	function test_oik_custom_css_box_edit_link_bb_BB() {
		//oik_require( "admin/oik-admin.inc" );
		
		$this->switch_to_locale( "bb_BB" );
		
		bw_update_option( "customCSS", "custom.css" );
		$html = bw_ret( oik_custom_css_box() );
		$html = $this->replace_admin_url( $html );
		
    $theme = bw_get_theme();
		$html = str_replace( "theme=" . $theme, "theme=genesis-image", $html );
		
		$custom_CSS = bw_get_option( "customCSS"	);
		$this->assertEquals( "custom.css", $custom_CSS );
		
		$html_array = $this->tag_break( $html );
		
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}

/**
	 * Test the oik Buttons section for bb_BB
	 * 
	 * Since we invoke bw_flush() we need to capture the output buffer
	 * to apply changes before comparing with expected.
	 */
	function test_oik_tinymce_buttons_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		ob_start();   
		oik_tinymce_buttons();
		$html = ob_get_contents();
		ob_end_clean();
		$html = $this->replace_oik_url( $html );
		$html_array = $this->tag_break( $html );
		$html_array = $this->replace_nonce_with_nonsense( $html_array );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		
	}

	/**
	 * Test links to the oik documentation for bb_BB
	 *
	 * The oik documentation is on oik-plugins.com
	 * The forum is on wordpress.org
	 */
	function test_oik_documentation_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = bw_ret( oik_documentation() );
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	/**
	 * Tests bw_symlinked_plugin for en_GB
	 */ 
	function test_bw_symlinked_plugin() {
		$this->switch_to_locale( "en_GB" );
		ob_start();
		$plugin_data = array( "Version" => "1.2.3", "new_version" => "1.2.4", "real_path" => "real/path/wp-content/plugins/symlinked_plugin" );
		$r = null;
		bw_symlinked_plugin( $plugin_data, $r );
		$html = ob_get_contents();
		ob_end_clean();
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	/**
	 * Tests bw_symlinked_plugin for bb_BB
	 */ 
	function test_bw_symlinked_plugin_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		ob_start();
		$plugin_data = array( "Version" => "1.2.3", "new_version" => "1.2.4", "real_path" => "real/path/wp-content/plugins/symlinked_plugin" );
		$r = null;
		bw_symlinked_plugin( $plugin_data, $r );
		$html = ob_get_contents();
		ob_end_clean();
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	
	/**
	 * Tests bw_gitrepo_plugin for en_GB
	 */ 
	function test_bw_gitrepo_plugin() {
		$this->switch_to_locale( 'en_GB' );
		ob_start();
		$plugin_data = array( "Version" => "1.2.3", "new_version" => "1.2.4", "real_path" => "real/path/wp-content/plugins/symlinked_plugin" );
		$r = null;
		bw_gitrepo_plugin( $plugin_data, $r );
		$html = ob_get_contents();
		ob_end_clean();
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	/**
	 * Tests bw_gitrepo_plugin for bb_BB
	 */ 
	function test_bw_gitrepo_plugin_bb_BB() {
		$this->switch_to_locale( 'bb_BB' );
		ob_start();
		$plugin_data = array( "Version" => "1.2.3", "new_version" => "1.2.4", "real_path" => "real/path/wp-content/plugins/symlinked_plugin" );
		$r = null;
		bw_gitrepo_plugin( $plugin_data, $r );
		$html = ob_get_contents();
		ob_end_clean();
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
		
	
	
	


}
