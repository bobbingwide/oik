<?php // (C) Copyright Bobbing Wide 2017

class Tests_oik_admin extends BW_UnitTestCase {

	/** 
	 * From | To       | Count  | Notes
	 * ---- | --       | ------ | --------
	 * p()  | BW_::p() | 129    | Or use p_()
	 * 
	 */
	
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
		$expected .= '<a class="button-secondary" href="https://qw/wordpress/wp-admin/admin.php?page=oik_plugins" title="Manage plugin servers and API keys">Plugins</a>';
		$expected .= '<p>Use the Themes page to manage oik themes servers and API keys</p>';
		$expected .= '<a class="button-secondary" href="https://qw/wordpress/wp-admin/admin.php?page=oik_themes" title="Manage theme servers and API keys">Themes</a>';
    $expected = $this->replace_admin_url( $expected );
    $this->assertEquals( $expected, $html );
	}
	
	

}
	
