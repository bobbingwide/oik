<?php // (C) Copyright Bobbing Wide 2017

class Tests_oik_admin extends BW_UnitTestCase {

	
	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 */
	function setUp() {
		parent::setUp();
		//bobbcomp::bw_get_option( "fred" );
		oik_require( "admin/oik-admin.inc" );
		oik_require_lib( "oik_plugins" );
		oik_require_lib( "oik_themes" );
		oik_require_lib( "class-oik-update" );
	}
	
	/**
	 * Replace admin_url with the expected hardcoded value
	 *
	 */ 
	function replace_admin_url( $expected ) {
		$expected = str_replace( admin_url(), "https://qw/src/wp-admin/", $expected );
		return $expected;
	}
	
	function replace_oik_url( $html ) {
		$html = str_replace( oik_url(), "https://qw/src/wp-content/plugins/oik/", $html );
		return $html;
	}
	
	/**
	 * Break into lines at tag interfaces
	 * and convert into array?
	 */
	function tag_break( $html ) {
		$new_lined = str_replace( "><", ">\n<", $html);
		$html_array = explode( "\n", $new_lined );
		return $html_array;
	}
	
	/**
	 * Helps to generate the expected array from actual test output
	 * 
	 * echoing this output ensures we get 
	 */
	function generate_expected( $html_array ) {
		echo PHP_EOL;
		echo '$expected = array();';
		foreach ( $html_array as $line ) {
			echo PHP_EOL;
			$line = str_replace( "'", "\'", $line );
			echo '$expected[] = \'' . $line . "';";
		}
		echo PHP_EOL;
		$this->assertFalse( true );
	}
	
	/**
	 * Note: This function could fail if there is no nonce in the output
	 */
	function replace_nonce_with_nonsense( $expected_array ) {
		$found = false;
		foreach ( $expected_array as $index => $line ) {
			$pos = strpos( $line, '<input type="hidden" id="_wpnonce" name="_wpnonce" value="' );
			if ( false !== $pos ) {
				$expected_array[ $index ] = '<input type="hidden" id="_wpnonce" name="_wpnonce" value="nonsense" />';
				$found = true;
			}
		}
		$this->assertTrue( $found, "No nonce found in expected array" );
		return $expected_array;
	}
	
	function test_oik_callback() {
		$html = bw_ret( oik_callback() );
		$expected = "<p>This box intentionally left blank</p>";
		$this->assertEquals( $expected, $html );
	}
	
	function test_oik_shortcode_options() {
		//oik_require( "admin/oik-admin.inc" );
		$html = bw_ret( oik_shortcode_options() );
		$html = $this->replace_admin_url( $html );
		$expected = null;
		$expected = '<p>oik provides sets of lazy smart shortcodes that you can use just about anywhere in your WordPress site.</p>';
		$expected .= '<p>You enter your common information, such as contact details, slogans, location information, PayPal payment information and your social networking and bookmarking information using oik Options</p>';
		$expected .= '<p>If required, you enter alternative information using More options</p>';
		$expected .= '<a class="button-primary" href="https://qw/src/wp-admin/admin.php?page=oik_options" title="Enter your common information">Options</a>';
		$expected .= '&nbsp;';
		$expected .= '<a class="button-secondary" href="https://qw/src/wp-admin/admin.php?page=oik_options-1" title="Enter additional information">More options</a>';
		$expected .= '<p>Discover the shortcodes that you can use in your content and widgets using Shortcode help</p>';
		$expected .= '<a class="button-secondary" href="https://qw/src/wp-admin/admin.php?page=oik_sc_help" title="Discover shortcodes you can use">Shortcode help</a>';
		$expected .= '<p>Choose the helper buttons that help you to insert shortcodes when editing your content</p>';
		$expected .= '<a class="button-secondary" href="https://qw/src/wp-admin/admin.php?page=oik_buttons" title="Select TinyMCE and HTML edit helper buttons">Buttons</a>';
		
		$this->assertEquals( $expected, $html );
	}
	
	/**
	 * Test the oik custom CSS box
	 * 
	 * Note: This only tests one of the routes. 
	 * @TODO We need to set or unset 'customCSS' to test the other one. And perhaps another value for custom.css ?
	 * 
	 */
	function test_oik_custom_css_box() {
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
		$expected = null;
		$expected .= '<p>For more information:</p><ul><li>';
		$expected .= '<a href="http://www.oik-plugins.com/tutorial/getting-started-with-oik-plugins/" title="Getting started">Getting started</a>';
		$expected .= '</li><li>';
		$expected .= '<a href="http://www.oik-plugins.com/oik/oik-faq" title="Frequently Asked Questions">Frequently Asked Questions</a>';
		$expected .= '</li><li>';
		$expected .= '<a href="http://wordpress.org/tags/oik?forum_id=10" title="Forum">Forum</a>';
		$expected .= '</li></ul>';
		$expected .= '<p><a class="button button-secondary" href="http://www.oik-plugins.com" title="Read the documentation for the oik plugin">oik documentation</a></p>';
    $this->assertEquals( $expected, $html );
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
		//$this->generate_expected( $html_array );
		//$expected
		
		$expected = array();
$expected[] = '<p>Support the development of <span class="bw_oik">';
$expected[] = '<abbr  title="OIK Information Kit">oik</abbr>';
$expected[] = '</span> by making a donation to <a class="url" href="http://www.bobbingwide.com" title="Visit the Bobbing Wide website: www.bobbingwide.com">www.bobbingwide.com</a>';
$expected[] = '</p>';
$expected[] = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">';
$expected[] = '<input type="hidden" name="business" value="herb@bobbingwide.com" />';
$expected[] = '<input type="hidden" name="lc" value="United Kingdom" />';
$expected[] = '<input type="hidden" name="currency_code" value="GBP" />';
$expected[] = '<input type="hidden" name="item_name" value="oik-plugin" />';
$expected[] = '<input type="hidden" name="item_number" value="oik" />';
$expected[] = '<input type="hidden" name="cmd" value="_donations" />';
$expected[] = '<input type="hidden" name="no_note" value="0" />';
$expected[] = '<input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_LG.gif:NonHostedGuest" />';
$expected[] = '<input type="image" src="https://www.paypal.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">';
$expected[] = '</form>';
		$this->assertEquals( $expected, $html_array );
		
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
		//$this->generate_expected( $html_array );
    $expected = array();
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
		//$html_array = $this->replace_nonce_with_nonsense( $html_array );
    //$this->generate_expected( $html_array );
$expected = array();
$expected[] = '<div class="wrap">';
$expected[] = '<h2>plugin server settings</h2>';
$expected[] = '<div class="metabox-holder">';
$expected[] = '<div class="postbox-container w100pc">';
$expected[] = '<div class="meta-box-sortables ui-sortable">';
$expected[] = '<div class="postbox " id="oik_plugins_settings">';
$expected[] = '<div class="handlediv"  title="Click to toggle">';
$expected[] = '<br />';
$expected[] = '</div>';
$expected[] = '<h3 class="hndle">Settings</h3>';
$expected[] = '<div class="inside">';
$expected[] = '<p>The default oik plugins server is currently set to: <a href="http://qw/oikcom" title="default oik plugins server">http://qw/oikcom</a>';
$expected[] = '</p>';
$expected[] = '<form method="post">';
$expected[] = '<table class="widefat ">';
$expected[] = '<thead>';
$expected[] = '<tr>';
$expected[] = '<td>plugin</td>';
$expected[] = '<td>version</td>';
$expected[] = '<td>server</td>';
$expected[] = '<td>apikey</td>';
$expected[] = '<td>actions</td>';
$expected[] = '</tr>';
$expected[] = '</thead>';
$expected[] = '<tr>';
$expected[] = '<td>us-tides</td>';
$expected[] = '<td>0.3.0&nbsp;</td>';
$expected[] = '<td>https://example.com&nbsp;</td>';
$expected[] = '<td>sampleapikey&nbsp;</td>';
$expected[] = '<td>';
$expected[] = '<a href="https://qw/src/wp-admin/admin.php?page=oik_plugins&amp;delete_plugin=us-tides" title="Delete plugin&#039;s profile entry">Delete</a>&nbsp;<a href="https://qw/src/wp-admin/admin.php?page=oik_plugins&amp;edit_plugin=us-tides" title="Edit">Edit</a>&nbsp;<a href="https://qw/src/wp-admin/admin.php?page=oik_plugins&amp;check_plugin=us-tides&amp;check_version=0.3.0" title="Check">Check</a>&nbsp;</td>';
$expected[] = '</tr>';
$expected[] = '</table>';
$expected[] = '<p>';
$expected[] = '<input type="submit" name="_oik_plugins_add_plugin" value="Add plugin" class="button-primary" />';
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
		//$html_array = $this->replace_nonce_with_nonsense( $html_array );
    //$this->generate_expected( $html_array );
$expected = array();
$expected[] = '<div class="wrap">';
$expected[] = '<h2>theme server settings</h2>';
$expected[] = '<div class="metabox-holder">';
$expected[] = '<div class="postbox-container w100pc">';
$expected[] = '<div class="meta-box-sortables ui-sortable">';
$expected[] = '<div class="postbox " id="oik_themes_settings">';
$expected[] = '<div class="handlediv"  title="Click to toggle">';
$expected[] = '<br />';
$expected[] = '</div>';
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
    //$this->generate_expected( $html_array );
$expected = array();
$expected[] = '<div class="wrap">';
$expected[] = '<h2>shortcode options</h2>';
$expected[] = '<div class="metabox-holder">';
$expected[] = '<div class="postbox-container w60pc">';
$expected[] = '<div class="meta-box-sortables ui-sortable">';
$expected[] = '<div class="postbox " id="oik_main_shortcode_options">';
$expected[] = '<div class="handlediv"  title="Click to toggle">';
$expected[] = '<br />';
$expected[] = '</div>';
$expected[] = '<h3 class="hndle">Often included key information</h3>';
$expected[] = '<div class="inside">';
$expected[] = '<form method="post" action="options.php">';
$expected[] = '<table class="form-table">';
$expected[] = '<input type=\'hidden\' name=\'option_page\' value=\'oik_options_options\' />';
$expected[] = '<input type="hidden" name="action" value="update" />';
$expected[] = '<input type="hidden" id="_wpnonce" name="_wpnonce" value="nonsense" />';
$expected[] = '<input type="hidden" name="_wp_http_referer" value="/" />';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[telephone]">Telephone [bw_telephone alt=0] / [bw_tel alt=0]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[telephone]" id="bw_options[telephone]" value="+44 (0)2392 410090" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[mobile]">Mobile [bw_mobile alt=0] / [bw_mob alt=0]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[mobile]" id="bw_options[mobile]" value="+44 (0)7876 236864" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[fax]">Fax [bw_fax alt=0]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[fax]" id="bw_options[fax]" value="" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[emergency]">Emergency [bw_emergency alt=0]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[emergency]" id="bw_options[emergency]" value="" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[company]">Company [bw_company]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[company]" id="bw_options[company]" value="Bobbing Wide" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[business]">Business [bw_business]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[business]" id="bw_options[business]" value="web design, web development" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[formal]">Formal [bw_formal]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[formal]" id="bw_options[formal]" value="Bobbing Wide - web design, web development" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[abbr]">Abbreviation [bw_abbr]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[abbr]" id="bw_options[abbr]" value="bw" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[main-slogan]">Main slogan [bw_slogan]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[main-slogan]" id="bw_options[main-slogan]" value="" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[alt-slogan]">Alt. slogan [bw_alt_slogan]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[alt-slogan]" id="bw_options[alt-slogan]" value="" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[contact]">Contact [bw_contact alt=0]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[contact]" id="bw_options[contact]" value="" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[email]">Email [bw_mailto alt=0]/[bw_email alt=0]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[email]" id="bw_options[email]" value="" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[admin]">Admin [bw_admin]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[admin]" id="bw_options[admin]" value="" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[contact-link]">Contact button page permalink [bw_contact_button]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[contact-link]" id="bw_options[contact-link]" value="" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[contact-text]">Contact button text</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[contact-text]" id="bw_options[contact-text]" value="" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[contact-title]">Contact button tooltip</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[contact-title]" id="bw_options[contact-title]" value="" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[extended-address]">Extended-address [bw_address alt=0]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[extended-address]" id="bw_options[extended-address]" value="" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[street-address]">Street-address</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[street-address]" id="bw_options[street-address]" value="" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[locality]">Locality</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[locality]" id="bw_options[locality]" value="" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[region]">Region</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[region]" id="bw_options[region]" value="" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[postal-code]">Post Code</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[postal-code]" id="bw_options[postal-code]" value="" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[country-name]">Country name</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[country-name]" id="bw_options[country-name]" value="" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[gmap_intro]">Google Maps introductory text for [bw_show_googlemap alt=0]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<textarea rows="5" cols="50" name="bw_options[gmap_intro]">';
$expected[] = '</textarea>';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[lat]">Latitude [bw_geo alt=0] [bw_directions alt=0]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[lat]" id="bw_options[lat]" value="" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[long]">Longitude [bw_show_googlemap alt=0]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[long]" id="bw_options[long]" value="" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[width]">Google Map width</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="10"name="bw_options[width]" id="bw_options[width]" value="" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[height]">Google Map height</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="10"name="bw_options[height]" id="bw_options[height]" value="" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[google_maps_api_key]">Google Maps API key</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="39"name="bw_options[google_maps_api_key]" id="bw_options[google_maps_api_key]" value="AIzaSyBU6GyrIrVZZ0auvDzz_x0Xl1TzbcYrPJU" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[domain]">Domain [bw_domain] [bw_wpadmin]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[domain]" id="bw_options[domain]" value="" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[oikCSS]">Do NOT use the oik.css styling. <br />Check this if you don\'t want to use the oik provided CSS styling</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="hidden" name="bw_options[oikCSS]" value="0" />';
$expected[] = '<input type="checkbox" name="bw_options[oikCSS]" id="bw_options[oikCSS]"/>';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[customCSS]">Custom CSS in theme directory:<br />http://qw/wordpress/wp-content/themes/genesis-a2z</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[customCSS]" id="bw_options[customCSS]" value="custom.css" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[customjQCSS]">Custom jQuery UI CSS URL</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="90"name="bw_options[customjQCSS]" id="bw_options[customjQCSS]" value="http://qw/wordpress/wp-content/themes/jquery-ui/themes/base/jquery-ui.css" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[twitter]">Twitter URL [bw_twitter alt=0]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[twitter]" id="bw_options[twitter]" value="herb_miller" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[facebook]">Facebook URL [bw_facebook alt=0]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[facebook]" id="bw_options[facebook]" value="bobbingwide" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[linkedin]">LinkedIn URL [bw_linkedin alt=0]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[linkedin]" id="bw_options[linkedin]" value="herbmiller777" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[googleplus]">Google Plus URL [bw_googleplus alt=0]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[googleplus]" id="bw_options[googleplus]" value="herbmiller777" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[youtube]">YouTube URL [bw_youtube alt=0]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[youtube]" id="bw_options[youtube]" value="bobbingwide" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[flickr]">Flickr URL [bw_flickr alt=0]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[flickr]" id="bw_options[flickr]" value="herb_miller" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[picasa]">Picasa URL [bw_picasa alt=0]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[picasa]" id="bw_options[picasa]" value="bobbingwide" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[pinterest]">Pinterest URL [bw_pinterest alt=0]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[pinterest]" id="bw_options[pinterest]" value="bobbingwide" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[instagram]">Instagram URL [bw_instagram alt=0]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[instagram]" id="bw_options[instagram]" value="bobbingwide" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[skype]">Skype Name [bw_skype alt=0]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[skype]" id="bw_options[skype]" value="bobbingwide" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[github]">GitHub username</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[github]" id="bw_options[github]" value="splurge" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[wordpress]">WordPress.org username</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[wordpress]" id="bw_options[wordpress]" value="" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[paypal-email]">PayPal email [bw_paypal]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[paypal-email]" id="bw_options[paypal-email]" value="herb@bobbingwide.com" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[paypal-country]">PayPal country</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[paypal-country]" id="bw_options[paypal-country]" value="United Kingdom" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[paypal-currency]">PayPal currency</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<select name="bw_options[paypal-currency]">';
$expected[] = '<option value="GBP"  selected=\'selected\'>GBP</option>';
$expected[] = '<option value="USD" >USD</option>';
$expected[] = '<option value="EUR" >EUR</option>';
$expected[] = '<option value="AUD" >AUD</option>';
$expected[] = '<option value="BRL" >BRL</option>';
$expected[] = '<option value="CAD" >CAD</option>';
$expected[] = '<option value="CHF" >CHF</option>';
$expected[] = '<option value="CZK" >CZK</option>';
$expected[] = '<option value="DKK" >DKK</option>';
$expected[] = '<option value="HKD" >HKD</option>';
$expected[] = '<option value="HUF" >HUF</option>';
$expected[] = '<option value="ILS" >ILS</option>';
$expected[] = '<option value="JPY" >JPY</option>';
$expected[] = '<option value="MXN" >MXN</option>';
$expected[] = '<option value="MYR" >MYR</option>';
$expected[] = '<option value="NOK" >NOK</option>';
$expected[] = '<option value="NZD" >NZD</option>';
$expected[] = '<option value="PHP" >PHP</option>';
$expected[] = '<option value="PLN" >PLN</option>';
$expected[] = '<option value="SEK" >SEK</option>';
$expected[] = '<option value="SGD" >SGD</option>';
$expected[] = '<option value="THB" >THB</option>';
$expected[] = '<option value="TRY" >TRY</option>';
$expected[] = '<option value="TWD" >TWD</option>';
$expected[] = '</select>';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[logo-image]">Logo image ID/URL/in uploads<br />https://qw/wordpress/wp-content/uploads [bw_logo]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[logo-image]" id="bw_options[logo-image]" value="30048" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[login-logo]">Use as login logo?</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="hidden" name="bw_options[login-logo]" value="0" />';
$expected[] = '<input type="checkbox" name="bw_options[login-logo]" id="bw_options[login-logo]" checked="checked"/>';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[qrcode-image]">QR code image in uploads [bw_qrcode]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options[qrcode-image]" id="bw_options[qrcode-image]" value="" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[art-version]">Artisteer version</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<select name="bw_options[art-version]">';
$expected[] = '<option value="na" >na</option>';
$expected[] = '<option value="41"  selected=\'selected\'>41</option>';
$expected[] = '<option value="40" >40</option>';
$expected[] = '<option value="31" >31</option>';
$expected[] = '<option value="30" >30</option>';
$expected[] = '<option value="25" >25</option>';
$expected[] = '</select>';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[yearfrom]">Copyright from year [bw_copyright]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="4"name="bw_options[yearfrom]" id="bw_options[yearfrom]" value="2010" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options[howdy]">\'Howdy,\' replacement string</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="10"name="bw_options[howdy]" id="bw_options[howdy]" value="hi:" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '</table>';
$expected[] = '<input type="submit" name="ok" value="Save changes" class="button-primary" />';
$expected[] = '</form>';
$expected[] = '</div>';
$expected[] = '</div>';
$expected[] = '<p>';
$expected[] = '<!--start ecolumn-->';
$expected[] = '</div>';
$expected[] = '</div>';
$expected[] = '</div>';
$expected[] = '<p>';
$expected[] = '<!--end ecolumn-->';
$expected[] = '</p>';
$expected[] = '<div class="metabox-holder">';
$expected[] = '<div class="postbox-container w40pc">';
$expected[] = '<div class="meta-box-sortables ui-sortable">';
$expected[] = '<div class="postbox " id="oik_usage_notes">';
$expected[] = '<div class="handlediv"  title="Click to toggle">';
$expected[] = '</div>';
$expected[] = '<h3 class="hndle">Usage notes</h3>';
$expected[] = '<div class="inside">';
$expected[] = '<p>Use the shortcodes in your pages, widgets and titles. e.g.</p>';
$expected[] = '<p>Display your contact name.</p>';
$expected[] = '<p>';
$expected[] = '<code>[bw_contact alt=0]</code>';
$expected[] = '</p>';
$expected[] = '<p>';
$expected[] = '<span class="vcard">';
$expected[] = '<span class="fn">me</span>';
$expected[] = '</span>';
$expected[] = '</p>';
$expected[] = '<p>Display your telephone number.</p>';
$expected[] = '<p>';
$expected[] = '<code>[bw_telephone alt=0]</code>';
$expected[] = '</p>';
$expected[] = '<div class="tel ">';
$expected[] = '<span class="type">Tel</span>';
$expected[] = '<span class="sep">: </span>';
$expected[] = '<span class="value">+44 (0)2392 410090</span>';
$expected[] = '</div>';
$expected[] = '<p>Display your address.</p>';
$expected[] = '<p>';
$expected[] = '<code>[bw_address alt=0]</code>';
$expected[] = '</p>';
$expected[] = '<div class="adr bw_address">';
$expected[] = '<div class="type">Work</div>';
$expected[] = '<div class="extended-address">';
$expected[] = '</div>';
$expected[] = '<div class="street-address">';
$expected[] = '</div>';
$expected[] = '<div class="locality">';
$expected[] = '</div>';
$expected[] = '<div class="region">';
$expected[] = '</div>';
$expected[] = '<div class="postal-code">';
$expected[] = '</div>';
$expected[] = '<p>';
$expected[] = '<span class="country-name">';
$expected[] = '</span>';
$expected[] = '</div>';
$expected[] = '<p>Display your email address.</p>';
$expected[] = '<p>';
$expected[] = '<code>[bw_email alt=0]</code>';
$expected[] = '</p>';
$expected[] = '<p>';
$expected[] = '<span class="email">Email: <a href="mailto:" title="Send email to:">';
$expected[] = '</a>';
$expected[] = '</span>';
$expected[] = '</p>';
$expected[] = '<p>Display a Googlemap for your primary address.</p>';
$expected[] = '<p>';
$expected[] = '<code>[bw_show_googlemap alt=0]</code>';
$expected[] = '</p>';
$expected[] = '<p>';
$expected[] = '<script type="text/javascript">function initialize2() {var latlng = new google.maps.LatLng(50.887856,-0.965113);var myOptions = { zoom: 12, center: latlng, mapTypeId: google.maps.MapTypeId.ROADMAP };var map = new google.maps.Map(document.getElementById("bw_map_canvas2"), myOptions); initialize1();}window.onload=initialize2;</script>';
$expected[] = '</p>';
$expected[] = '<div class="bw_map_canvas" id="bw_map_canvas2" style="min-height: 200px; width:100%; height:400px;">';
$expected[] = '</div>';
$expected[] = '<p>Display a button for obtaining directions to your address.</p>';
$expected[] = '<p>';
$expected[] = '<code>[bw_directions alt=0]</code>';
$expected[] = '</p>';
$expected[] = '<p>';
$expected[] = '<a class="button " href="http://maps.google.co.uk/maps?f=d&#038;hl=en&#038;daddr=," title="Get directions to Bobbing Wide">Google directions</a>';
$expected[] = '</p>';
$expected[] = '<p>Show all your <b>Follow me</b> buttons.</p>';
$expected[] = '<p>';
$expected[] = '<code>[bw_follow_me alt=0]</code>';
$expected[] = '</p>';
$expected[] = '<p>';
$expected[] = '<a href="https://www.twitter.com/herb_miller" title="Follow me on Twitter">';
$expected[] = '<img class="bw_follow " src="http://qw/wordpress/wp-content/plugins/oik/images/twitter_48.png" title="Follow me on Twitter" alt="Follow me on Twitter"  />';
$expected[] = '</a>';
$expected[] = '<a href="https://www.facebook.com/bobbingwide" title="Follow me on Facebook">';
$expected[] = '<img class="bw_follow " src="http://qw/wordpress/wp-content/plugins/oik/images/facebook_48.png" title="Follow me on Facebook" alt="Follow me on Facebook"  />';
$expected[] = '</a>';
$expected[] = '<a href="http://www.linkedin.com/herbmiller777" title="Follow me on LinkedIn">';
$expected[] = '<img class="bw_follow " src="http://qw/wordpress/wp-content/plugins/oik/images/linkedin_48.png" title="Follow me on LinkedIn" alt="Follow me on LinkedIn"  />';
$expected[] = '</a>';
$expected[] = '<a href="http://www.googleplus.com/herbmiller777" title="Follow me on GooglePlus">';
$expected[] = '<img class="bw_follow " src="http://qw/wordpress/wp-content/plugins/oik/images/googleplus_48.png" title="Follow me on GooglePlus" alt="Follow me on GooglePlus"  />';
$expected[] = '</a>';
$expected[] = '<a href="http://www.youtube.com/bobbingwide" title="Follow me on YouTube">';
$expected[] = '<img class="bw_follow " src="http://qw/wordpress/wp-content/plugins/oik/images/youtube_48.png" title="Follow me on YouTube" alt="Follow me on YouTube"  />';
$expected[] = '</a>';
$expected[] = '<a href="http://www.flickr.com/herb_miller" title="Follow me on Flickr">';
$expected[] = '<img class="bw_follow " src="http://qw/wordpress/wp-content/plugins/oik/images/flickr_48.png" title="Follow me on Flickr" alt="Follow me on Flickr"  />';
$expected[] = '</a>';
$expected[] = '<a href="http://www.pinterest.com/bobbingwide" title="Follow me on Pinterest">';
$expected[] = '<img class="bw_follow " src="http://qw/wordpress/wp-content/plugins/oik/images/pinterest_48.png" title="Follow me on Pinterest" alt="Follow me on Pinterest"  />';
$expected[] = '</a>';
$expected[] = '<a href="http://www.instagram.com/bobbingwide" title="Follow me on Instagram">';
$expected[] = '<img class="bw_follow " src="http://qw/wordpress/wp-content/plugins/oik/images/instagram_48.png" title="Follow me on Instagram" alt="Follow me on Instagram"  />';
$expected[] = '</a>';
$expected[] = '<a href="https://github.com/splurge" title="Follow me on GitHub">';
$expected[] = '<img class="bw_follow " src="http://qw/wordpress/wp-content/plugins/oik/images/github_48.png" title="Follow me on GitHub" alt="Follow me on GitHub"  />';
$expected[] = '</a>';
$expected[] = '</p>';
$expected[] = '<p>Show your <b>Follow me</b> buttons using genericons.</p>';
$expected[] = '<p>';
$expected[] = '<code>[bw_follow_me alt=0 theme=gener]</code>';
$expected[] = '</p>';
$expected[] = '<p>';
$expected[] = '<a href="https://www.twitter.com/herb_miller" title="Follow me on Twitter">';
$expected[] = '<span class="genericon genericon-twitter bw_follow_me ">';
$expected[] = '</span>';
$expected[] = '</a>';
$expected[] = '<a href="https://www.facebook.com/bobbingwide" title="Follow me on Facebook">';
$expected[] = '<span class="genericon genericon-facebook-alt bw_follow_me ">';
$expected[] = '</span>';
$expected[] = '</a>';
$expected[] = '<a href="http://www.linkedin.com/herbmiller777" title="Follow me on LinkedIn">';
$expected[] = '<span class="genericon genericon-linkedin bw_follow_me ">';
$expected[] = '</span>';
$expected[] = '</a>';
$expected[] = '<a href="http://www.googleplus.com/herbmiller777" title="Follow me on GooglePlus">';
$expected[] = '<span class="genericon genericon-googleplus-alt bw_follow_me ">';
$expected[] = '</span>';
$expected[] = '</a>';
$expected[] = '<a href="http://www.youtube.com/bobbingwide" title="Follow me on YouTube">';
$expected[] = '<span class="genericon genericon-youtube bw_follow_me ">';
$expected[] = '</span>';
$expected[] = '</a>';
$expected[] = '<a href="http://www.flickr.com/herb_miller" title="Follow me on Flickr">';
$expected[] = '<span class="genericon genericon-flickr bw_follow_me ">';
$expected[] = '</span>';
$expected[] = '</a>';
$expected[] = '<a href="http://www.pinterest.com/bobbingwide" title="Follow me on Pinterest">';
$expected[] = '<span class="genericon genericon-pinterest bw_follow_me ">';
$expected[] = '</span>';
$expected[] = '</a>';
$expected[] = '<a href="http://www.instagram.com/bobbingwide" title="Follow me on Instagram">';
$expected[] = '<span class="genericon genericon-instagram bw_follow_me ">';
$expected[] = '</span>';
$expected[] = '</a>';
$expected[] = '<a href="https://github.com/splurge" title="Follow me on GitHub">';
$expected[] = '<span class="genericon genericon-github bw_follow_me ">';
$expected[] = '</span>';
$expected[] = '</a>';
$expected[] = '</p>';
$expected[] = '<p>For more information about the shortcodes you can use select <b>Shortcode help</b>';
$expected[] = '</p>';
$expected[] = '<a class="button-secondary" href="https://qw/src/wp-admin/admin.php?page=oik_sc_help" title="Discover shortcodes you can use">Shortcode help</a>';
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
	 * Tests oik_options_do_page_1
	 *
	 * Tests: 
	 * oik_extra_shortcode_options
	 * oik_extra_usage_notes
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
    //$this->generate_expected( $html_array );
		
$expected = array();
$expected[] = '<div class="wrap">';
$expected[] = '<h2>extra shortcode options</h2>';
$expected[] = '<div class="metabox-holder">';
$expected[] = '<div class="postbox-container w60pc">';
$expected[] = '<div class="meta-box-sortables ui-sortable">';
$expected[] = '<div class="postbox " id="oik_extra_shortcode_options">';
$expected[] = '<div class="handlediv"  title="Click to toggle">';
$expected[] = '<br />';
$expected[] = '</div>';
$expected[] = '<h3 class="hndle">alternative values using alt=1</h3>';
$expected[] = '<div class="inside">';
$expected[] = '<form method="post" action="options.php">';
$expected[] = '<table class="form-table">';
$expected[] = '<input type=\'hidden\' name=\'option_page\' value=\'oik_options_options1\' />';
$expected[] = '<input type="hidden" name="action" value="update" />';
$expected[] = '<input type="hidden" id="_wpnonce" name="_wpnonce" value="nonsense" />';
$expected[] = '<input type="hidden" name="_wp_http_referer" value="/" />';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options1[contact]">Contact [bw_contact alt=1]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options1[contact]" id="bw_options1[contact]" value="herbt internet" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options1[email]">Email [bw_email alt=1]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options1[email]" id="bw_options1[email]" value="herb_miller@btinternet.com" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options1[telephone]">Telephone [bw_telephone alt=1]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options1[telephone]" id="bw_options1[telephone]" value="45465" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options1[mobile]">Mobile [bw_mobile alt=1]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options1[mobile]" id="bw_options1[mobile]" value="" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options1[extended-address]">Extended-address [bw_address alt=1]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options1[extended-address]" id="bw_options1[extended-address]" value="La Lumiere" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options1[street-address]">Street-address</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options1[street-address]" id="bw_options1[street-address]" value="Les Grandes Vignes" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options1[locality]">Locality</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options1[locality]" id="bw_options1[locality]" value="Merindol les Oliviers" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options1[region]">Region</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options1[region]" id="bw_options1[region]" value="Drome" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options1[postal-code]">Post Code</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options1[postal-code]" id="bw_options1[postal-code]" value="26170" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options1[country-name]">Country name</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options1[country-name]" id="bw_options1[country-name]" value="France" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options1[gmap_intro]">Google Maps introductory text for [bw_show_googlemap alt=1]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<textarea rows="5" cols="50" name="bw_options1[gmap_intro]">This Google map shows you where [bw_company] is located</textarea>';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options1[lat]">Latitude [bw_geo alt=1] [bw_directions alt=1]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options1[lat]" id="bw_options1[lat]" value="44.267467" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_options1[long]">Longitude [bw_show_googlemap alt=1]</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="text" size="50"name="bw_options1[long]" id="bw_options1[long]" value="5.161042" class="" />';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '</table>';
$expected[] = '<input type="submit" name="ok" value="Save changes" class="button-primary" />';
$expected[] = '</form>';
$expected[] = '</div>';
$expected[] = '</div>';
$expected[] = '<p>';
$expected[] = '<!--start ecolumn-->';
$expected[] = '</div>';
$expected[] = '</div>';
$expected[] = '</div>';
$expected[] = '<p>';
$expected[] = '<!--end ecolumn-->';
$expected[] = '</p>';
$expected[] = '<div class="metabox-holder">';
$expected[] = '<div class="postbox-container w40pc">';
$expected[] = '<div class="meta-box-sortables ui-sortable">';
$expected[] = '<div class="postbox " id="oik_extra_usage_notes">';
$expected[] = '<div class="handlediv"  title="Click to toggle">';
$expected[] = '</div>';
$expected[] = '<h3 class="hndle">usage notes</h3>';
$expected[] = '<div class="inside">';
$expected[] = '<p>Use the shortcodes in your pages, widgets and titles. e.g.</p>';
$expected[] = '<p>Display your alternative contact name.</p>';
$expected[] = '<p>';
$expected[] = '<code>[bw_contact alt=1]</code>';
$expected[] = '</p>';
$expected[] = '<p>';
$expected[] = '<span class="vcard">';
$expected[] = '<span class="fn">herbt internet</span>';
$expected[] = '</span>';
$expected[] = '</p>';
$expected[] = '<p>Display your alternative email address, with a prefix of &#8216;e-mail&#8217;.</p>';
$expected[] = '<p>';
$expected[] = '<code>[bw_email alt=1 prefix=e-mail]</code>';
$expected[] = '</p>';
$expected[] = '<p>';
$expected[] = '<span class="email">e-mail: <a href="mailto:email@example.com" title="Send email to: herb_miller@btinternet.com">herb_miller@btinternet.com</a>';
$expected[] = '</span>';
$expected[] = '</p>';
$expected[] = '<p>Display your alternative telephone number.</p>';
$expected[] = '<p>';
$expected[] = '<code>[bw_telephone alt=1]</code>';
$expected[] = '</p>';
$expected[] = '<div class="tel ">';
$expected[] = '<span class="type">Tel</span>';
$expected[] = '<span class="sep">: </span>';
$expected[] = '<span class="value">45465</span>';
$expected[] = '</div>';
$expected[] = '<p>Display your alternative address.</p>';
$expected[] = '<p>';
$expected[] = '<code>[bw_address alt=1]</code>';
$expected[] = '</p>';
$expected[] = '<div class="adr bw_address">';
$expected[] = '<div class="type">Work</div>';
$expected[] = '<div class="extended-address">La Lumiere</div>';
$expected[] = '<div class="street-address">Les Grandes Vignes</div>';
$expected[] = '<div class="locality">Merindol les Oliviers</div>';
$expected[] = '<div class="region">Drome</div>';
$expected[] = '<div class="postal-code">26170</div>';
$expected[] = '<p>';
$expected[] = '<span class="country-name">France</span>';
$expected[] = '</div>';
$expected[] = '<p>Display a Googlemap for your alternative address.</p>';
$expected[] = '<p>';
$expected[] = '<code>[bw_show_googlemap alt=1]</code>';
$expected[] = '</p>';
$expected[] = '<p>This Google map shows you where <span class="company">Bobbing Wide</span> is located</p>';
$expected[] = '<p>';
$expected[] = '<script type="text/javascript">function initialize3() {var latlng = new google.maps.LatLng(44.267467,5.161042);var myOptions = { zoom: 12, center: latlng, mapTypeId: google.maps.MapTypeId.ROADMAP };var map = new google.maps.Map(document.getElementById("bw_map_canvas3"), myOptions); var marker = new google.maps.Marker({ position: latlng, title:"26170"});marker.setMap( map );var contentString = \' 26170\';var infowindow = new google.maps.InfoWindow({ content: contentString });infowindow.open( map, marker );initialize2();}window.onload=initialize3;</script>';
$expected[] = '</p>';
$expected[] = '<div class="bw_map_canvas" id="bw_map_canvas3" style="min-height: 200px; width:100%; height:400px;">';
$expected[] = '</div>';
$expected[] = '<p>Display directions to the alternative address.</p>';
$expected[] = '<p>';
$expected[] = '<code>[bw_directions alt=1]</code>';
$expected[] = '</p>';
$expected[] = '<p>';
$expected[] = '<a class="button " href="http://maps.google.co.uk/maps?f=d&#038;hl=en&#038;daddr=44.267467,5.161042" title="Get directions to  - La Lumiere - 26170">Google directions</a>';
$expected[] = '</p>';
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
	 * We want to ensure that only a few shortcodes are registered
	 * so we cam fiddle what happens during oik_add_shortcodes
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
    //$this->generate_expected( $html_array );
		
		
$expected = array();
$expected[] = '<div class="wrap">';
$expected[] = '<h2>shortcode help</h2>';
$expected[] = '<div class="metabox-holder">';
$expected[] = '<div class="postbox-container w95pc">';
$expected[] = '<div class="meta-box-sortables ui-sortable">';
$expected[] = '<div class="postbox " id="oik_code_about">';
$expected[] = '<div class="handlediv"  title="Click to toggle">';
$expected[] = '<br />';
$expected[] = '</div>';
$expected[] = '<h3 class="hndle">About shortcodes</h3>';
$expected[] = '<div class="inside">';
$expected[] = '<p>This page lists all the currently active shortcodes. To find out more information about a shortcode click on the shortcode name in the Help column.</p>';
$expected[] = '<p>Depending on how the shortcode is implemented you will either be shown some more information with one or more examples, or an \'oik generated example.\' </p>';
$expected[] = '<p>You will also be shown the HTML snippet for the example. There should be no need to do anything with this output.</p>';
$expected[] = '<p>For further information on a shortcode or its parameters click on the links in the Syntax column.</p>';
$expected[] = '</div>';
$expected[] = '</div>';
$expected[] = '<div class="postbox " id="oik_code_table">';
$expected[] = '<div class="handlediv"  title="Click to toggle">';
$expected[] = '<br />';
$expected[] = '</div>';
$expected[] = '<h3 class="hndle">Shortcode summary</h3>';
$expected[] = '<div class="inside">';
$expected[] = '<table class="widefat">';
$expected[] = '<thead>';
$expected[] = '<tr>';
$expected[] = '<th>Help</th>';
$expected[] = '<th>Syntax</th>';
$expected[] = '<th>Expands in titles?</th>';
$expected[] = '</tr>';
$expected[] = '</thead>';
$expected[] = '<tbody>';
$expected[] = '<tr>';
$expected[] = '<td>bw - Expand to the logo for Bobbing Wide</td>';
$expected[] = '<td>';
$expected[] = '<code>[<a href="http://qw/oikcom/oik-shortcodes/bw/bw_bw" title="bw help">bw</a>';
$expected[] = '<br />';
$expected[] = '<span class="key">';
$expected[] = '<a href="http://qw/oikcom/oik_sc_param/bw-cp-parameter" title="bw cp parameter">cp</a>';
$expected[] = '</span>=<span class="value">"<b>';
$expected[] = '</b>| h - Class name prefix"</span>]</code>';
$expected[] = '</td>';
$expected[] = '<td>Yes</td>';
$expected[] = '</tr>';
$expected[] = '</tbody>';
$expected[] = '</table>';
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
	
	function remove_most_shortcodes() {
		remove_all_shortcodes();
		bw_add_shortcode( 'bw', 'bw_bw', oik_path( "shortcodes/oik-bw.php" ) );
	}
	
	/**
	 * Asserts that the HTML array equals the file
	 */
	function assertArrayEqualsFile( $string, $file=null ) {
		$html_array = $this->prepareExpectedArray( $string );
		$expected_file = $this->prepareFile( $file );
		$expected = file( $expected_file, FILE_IGNORE_NEW_LINES );
		$this->assertEquals( $expected, $html_array );	
	}
	
	/**
	 * Converts to an array if required
	 */
	function prepareExpectedArray( $string ) {
		if ( is_scalar( $string ) ) {
			$html_array = $this->tag_break( $string );
		} else { 
			$html_array = $string;
		}
		return $html_array;
	}
	
	/**
	 * Returns the expected file name
	 * 
	 * Expected output files are stored in a directory tree
	 * 
	 * `tests/data/la_CY/test_name.html
	 * ` 
	 * where 
	 * - la_CY is the locale; default is `en_US`
	 * - test_name is the name of the test method
	 * 
	 * 
	 * @param string|null $file - 
	 * 
	 */
	function prepareFile( $file=null ) {
		if ( !$file ) {
			$file = $this->find_test_name();
		}
		$path_info = pathinfo( $file );
		if ( '.' == $path_info['dirname'] ) {
			$dirname = 'tests/data/';
			$dirname .= $this->query_la_CY();
			$path_info['dirname'] = $dirname;
		}
		if ( !isset( $path_info['extension'] ) ) {
			$path_info['extension'] = "html";
		}
		$expected_file = $path_info['dirname'];
		$expected_file .= "/";
		$expected_file .= $path_info['filename'];
		$expected_file .= ".";
		$expected_file .= $path_info['extension'];
		$this->assertFileExists( $expected_file );
		return $expected_file;
	}
	
	/**
	 * Finds the test name from the call stack
	 * 
	 * Assumes the test name starts with 'test_'
	 * 
	 * @param string $prefix
	 */
	function find_test_name( $prefix='test_') {
		$trace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS );
		$test_name = null;
		foreach ( $trace as $frame ) {
			if ( 0 === strpos( $frame['function'], $prefix ) ) {
				$test_name = $frame['function'];
				break;
			} 
		}
		$this->assertNotNull( $test_name );
		return $test_name;
	}
		
	
	/**
	 * When we're working in a different language e.g. bb_BB then
	 * we append the la_CY to the file name
	 */
	function assertArrayEqualsLanguageFile( $string, $file ) {
		
		
	}
	
	/**
	 * Queries the currently set locale
	 */
	function query_la_CY() {
		$locale = get_locale();
		$this->assertNotNull( $locale );
		return $locale;
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
	 * Helps to generate the expected file from actual test output
	 */
	function generate_expected_file( $html_array ) {
		echo PHP_EOL;
		foreach ( $html_array as $line ) {
			echo $line;
			echo PHP_EOL;
		}
		$this->prepareFile();
		//$this->assertFalse( true );
	}
	
	/**
	 * Switch to the required target language
	 * 
	 * switch_to_locale leaves much to be desired when the default language is en_US
	 * and/or when the translations are loaded from the plugin's language folders rather than WP_LANG_DIR
	 * We have to load the language files ourselves.
	 * 
	 * We also need to remember to pass the slug/domain to translate() :-)
	 */
	function switch_to_locale( $locale ) {
		$tdl = is_textdomain_loaded( "oik" );
		$this->assertTrue( $tdl );
		$switched = switch_to_locale( 'bb_BB' );
		if ( $switched ) {
			$this->assertTrue( $switched );
			$locale = $this->query_la_CY();
			$this->assertEquals( "bb_BB", $locale );
			$this->reload_domains();
			$tdl = is_textdomain_loaded( "oik" );
			$this->assertTrue( $tdl );
			//$this->test_domains_loaded();
			$bw = translate( "bobbingwide", "oik" );
			$this->assertEquals( "bboibgniwde", $bw );
		}	
	}
	
	
	function reload_domains() {
		$domains = array( "oik", "oik-libs" );
		foreach ( $domains as $domain ) {
			$loaded = bw_load_plugin_textdomain( $domain );
			$this->assertTrue( $loaded );
		}
	}
	
	/**
	 * For switch_to_locale() see https://core.trac.wordpress.org/ticket/26511
	 */
	function test_domains_loaded() {
		//var_dump( debug_backtrace() );
	
		global $l10n;
		$is_array = is_array( $l10n );
		$this->assertTrue( $is_array );
		$count = count( $l10n );
		$this->assertTrue( $count );
		//print_r( $l10n );
		//print_r( $domains );
	}
	
	/**
	 * We want to ensure that only a few shortcodes are registered
	 * so we cam fiddle what happens during oik_add_shortcodes
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
		
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	
	

}
	
