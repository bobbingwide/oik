<?php // (C) Copyright Bobbing Wide 2016

/** 
 * Unit tests for the [bw_follow_me] shortcode
 * Specifically to test the display of the new GitHub option
 *
 */

class Tests_oik_follow extends WP_UnitTestCase {

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
		oik_require( "shortcodes/oik-follow.php" );
	}
	
	/**
	 * Test the Follow me for GitHub
	 *
	 * We assume that the option value is set in the database.
	 */
	function test_follow_me_with_github_set() {
		$value = bw_get_option_arr( "github" );
		$html = bw_follow( array( "network" => "github" ) );
		$this->assertStringStartsWith( '<a href="https://github.com/' . $value, $html );
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
	
	

}
