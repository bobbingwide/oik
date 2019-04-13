<?php // (C) Copyright Bobbing Wide 2019

class Tests_issue_124 extends BW_UnitTestCase {

	/** 
	 * Issue #124 - tests for [bw_follow_me] comma separated network parameter
	 *
	 *
	 */
	
	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 */
	function setUp() {
		parent::setUp();
		$oik_plugins = oik_require_lib( "oik_plugins" );
		bw_trace2( $oik_plugins, "oik_plugins" );
		oik_require( "shortcodes/oik-follow.php" );
	}
	
	/**
	 * [bw_follow_me network=wordpress,github]
	 */
	function test_follow_me_network_wordpress_github() {
		$this->save();
		$this->set_networks();
		$atts = [ 'network' => 'wordpress,github', 'alt' => '0', 'me' => 'me' ];
		$html = bw_follow_me( $atts );
		$this->restore();
		$html = $this->replace_home_url( $html );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}

	function save() {
		$this->saved_wordpress = bw_get_option_arr( 'wordpress');
		$this->saved_github    = bw_get_option_arr( 'github' );
	}

	function set_networks() {
		bw_update_option( 'wordpress', 'bobbingwide' );
		bw_update_option( 'github', 'bobbingwide' );
	}

	function restore() {
		bw_update_option( 'wordpress', $this->saved_wordpress );
		bw_update_option( 'github', $this->saved_github );
	}

	/**
	 * Replaces home_url and site_url
	 *
	 * Should we consider using https://example.com ?
	 * @param string $expected
	 * @return string with site_url and home_url replaced by hard coded values
	 */
	function replace_home_url( $expected ) {
		$expected = str_replace( home_url(), "https://qw/src", $expected );
		$expected = str_replace( site_url(), "https://qw/src", $expected );
		return $expected;
	}

}
