<?php // (C) Copyright Bobbing Wide 2016, 2017

/** 
 * Unit tests for the [bw_follow_me] shortcode
 * and
 *
 */

class Tests_oik_follow extends BW_UnitTestCase {

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 * - we need "oik_plugins" for bw_update_option 
	 * - we need oik-follow to load the functions we're testing
	 */
	function setUp() {
		parent::setUp();
		$oik_plugins = oik_require_lib( "oik_plugins" );
		bw_trace2( $oik_plugins, "oik_plugins" );
		oik_require( "shortcodes/oik-follow.php" );
	}
	
	/**
	 * Test the Follow me for GitHub
	 *
	 * Specifically to test the display of the new GitHub option #47
	 *
	 * We assume that the option value is set in the database.
	 */
	function test_follow_me_with_github_set() {
		$saved = bw_get_option_arr( "github" );
		$value = bw_update_option( "github", "bobbingwide" );
		$html = bw_follow( array( "network" => "github" ) );
		$value2 = bw_update_option( "github", $saved );
		
		$this->assertStringStartsWith( '<a class=" bw_follow_new" href="https://github.com/' . $value, $html );
	}
	
	
	/**
	 * Test the Follow me for GitHub - bb_BB
	 *
	 * Specifically to test the display of the new GitHub option #47
	 *
	 * We assume that the option value is set in the database.
	 */
	function test_follow_me_with_github_set_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$saved = bw_get_option_arr( "github" );
		$value = bw_update_option( "github", "bobbingwide" );
		$html = bw_follow( array( "network" => "github" ) );
		$value2 = bw_update_option( "github", $saved );
		//$this->assertStringStartsWith( '<a href="https://github.com/' . $value, $html );
		
		$html = $this->replace_oik_url( $html );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	/**
	 * Test the Follow me for GitHub when the option value is not set
	 * 
	 * Notes: 
	 * - after performing these tests we trust the value will still be set to the "original" value; the tearDown() process will perform a rollback.
	 * - bw_follow_e() allows a mixed case value for network, bw_follow() does not.
	 * - Should this work regardless of the state of the oik-user plugin?
	 */
	function test_follow_me_with_github_not_set() {
		$value = bw_update_option( "github", null );
		$html = bw_follow_e( array( "network" => "GitHub" ) );
		$this->assertNull( $html );
		bw_trace2( $html, "html null?", false );
	}
	
	/**
	 * Tests issue #61 - Option field for WordPress.org
	 */
	function test_follow_me_with_wordpress_set() {
		$saved = bw_get_option_arr( "wordpress" );
		$value = bw_update_option( "wordpress", "bobbingwide" );
		$html = bw_follow( array( "network" => "wordpress" ) );
		$value2 = bw_update_option( "wordpress", $saved );
		$this->assertStringStartsWith( '<a class=" bw_follow_new" href="https://profiles.wordpress.org/' . $value, $html );
	}

}
