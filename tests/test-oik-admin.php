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
		$html = str_replace( oik_url(), "http://qw/src/wp-content/plugins/oik/", $html );
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
	 * Help to generate the expected array from actual test output
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
$expected[] = '<img class="" src="http://qw/src/wp-content/plugins/oik/admin/bw-bn-icon.gif" title="Button shortcodes" alt="Button shortcodes"  /> Button shortcodes</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="hidden" name="bw_buttons[oik-button-shortcodes]" value="0" />';
$expected[] = '<input type="checkbox" name="bw_buttons[oik-button-shortcodes]" id="bw_buttons[oik-button-shortcodes]" checked="checked"/>';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_buttons[oik-paypal-shortcodes]">';
$expected[] = '<img class="" src="http://qw/src/wp-content/plugins/oik/admin/bw-pp-icon.gif" title="PayPal shortcodes" alt="PayPal shortcodes"  /> PayPal shortcodes</label>';
$expected[] = '</td>';
$expected[] = '<td>';
$expected[] = '<input type="hidden" name="bw_buttons[oik-paypal-shortcodes]" value="0" />';
$expected[] = '<input type="checkbox" name="bw_buttons[oik-paypal-shortcodes]" id="bw_buttons[oik-paypal-shortcodes]" checked="checked"/>';
$expected[] = '</td>';
$expected[] = '</tr>';
$expected[] = '<tr>';
$expected[] = '<td>';
$expected[] = '<label for="bw_buttons[oik-shortc-shortcodes]">';
$expected[] = '<img class="" src="http://qw/src/wp-content/plugins/oik/admin/bw-sc-icon.gif" title="ALL shortcodes" alt="ALL shortcodes"  /> ALL shortcodes</label>';
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
	 * To reduce the output we need to fiddle the bw_plugins option to reduce the number of 
	 * registered plugins 
	 * Also null about $bw_registered_plugins OR set a known value
	 * Let's just try an empty array.
	 *
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
 
 
 // tests
 // oik_themes_do_page
	

}
	
