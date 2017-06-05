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
		//oik_require_lib( "class-BW-" );
		oik_require( "admin/oik-admin.inc" );
	}
	
	
	
	function replace_admin_url( $expected ) {
		$expected = str_replace( "http://qw/src/wp-admin/", admin_url(), $expected );
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
	 * 
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
		$this->assertTrue( $found );
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
		$expected = null;
		$expected = '<p>oik provides sets of lazy smart shortcodes that you can use just about anywhere in your WordPress site.</p>';
		$expected .= '<p>You enter your common information, such as contact details, slogans, location information, PayPal payment information and your social networking and bookmarking information using oik Options</p>';
		$expected .= '<p>If required, you enter alternative information using More options</p>';
		$expected .= '<a class="button-primary" href="http://qw/src/wp-admin/admin.php?page=oik_options" title="Enter your common information">Options</a>';
		$expected .= '&nbsp;';
		$expected .= '<a class="button-secondary" href="http://qw/src/wp-admin/admin.php?page=oik_options-1" title="Enter additional information">More options</a>';
		$expected .= '<p>Discover the shortcodes that you can use in your content and widgets using Shortcode help</p>';
		$expected .= '<a class="button-secondary" href="http://qw/src/wp-admin/admin.php?page=oik_sc_help" title="Discover shortcodes you can use">Shortcode help</a>';
		$expected .= '<p>Choose the helper buttons that help you to insert shortcodes when editing your content</p>';
		$expected .= '<a class="button-secondary" href="http://qw/src/wp-admin/admin.php?page=oik_buttons" title="Select TinyMCE and HTML edit helper buttons">Buttons</a>';
		
		$expected = $this->replace_admin_url( $expected );
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
		
		$custom_CSS = bw_get_option( "customCSS"	);
		$expected = null;
		$expected .= '<p>To style the output from shortcodes you can create and edit a custom CSS file for your theme.</p>';
		$expected .= '<p>Use the [bw_editcss] shortcode to create the <b>edit CSS</b> link anywhere on your website.</p>';
		$expected .= '<p>Note: If you change themes then you will start with a new custom CSS file.</p>';
		$expected .= '<p>You should save your custom CSS file before updating your theme.</p>';
		if ( !$custom_CSS ) {
			$expected .=  '<p>You have not defined a custom CSS file.</p>';
			$expected .= '<a class="button-secondary" href="http://qw/src/wp-admin/admin.php?page=oik_options" title="Enter the name of your custom CSS file on the Options page">Name your custom CSS file</a>';
		} else {
			$expected .= '<p>Click on this button to edit your custom CSS file.</p>';
			$expected .= '<a class="bw_custom_css" href="http://qw/src/wp-admin/theme-editor.php?file=custom.css&theme=genesis-image" title="Edit custom CSS">{}</a>';
      $theme = bw_get_theme();
			$expected = str_replace( "theme=genesis-image", "theme=" . $theme, $expected );
			$expected = $this->replace_admin_url( $expected );
		}
		$this->assertEquals( $expected, $html );
	}
	
	/**
	 */
	function test_oik_plugins_servers() {
		$html = bw_ret( oik_plugins_servers() );
		$expected = null;
		$expected .= '<p>Some oik plugins and themes are supported from servers other than WordPress.org</p>';
		$expected .= '<p>Premium plugin and theme versions require API keys.</p>';
		$expected .= '<p>Use the Plugins page to manage oik plugins servers and API keys</p>';
		$expected .= '<a class="button-secondary" href="http://qw/src/wp-admin/admin.php?page=oik_plugins" title="Manage plugin servers and API keys">Plugins</a>';
		$expected .= '<p>Use the Themes page to manage oik themes servers and API keys</p>';
		$expected .= '<a class="button-secondary" href="http://qw/src/wp-admin/admin.php?page=oik_themes" title="Manage theme servers and API keys">Themes</a>';
    $expected = $this->replace_admin_url( $expected );
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
	
	

}
	
