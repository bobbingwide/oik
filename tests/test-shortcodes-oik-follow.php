<?php // (C) Copyright Bobbing Wide 2017

/** 
 * Test for shortcodes/oik-follow.php
 * Note: Some other tests are in test-oik-follow.php
 */
class Tests_shortcodes_oik_follow extends BW_UnitTestCase {

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
		oik_require( "includes/oik-sc-help.php" );
	}
	
	function test_bw_follow_link_dash() {
		$this->switch_to_locale( "en_GB" );
		$social = "bobbingwide";
		$lc_social = "wordpress";
		$social_network = "WordPress";
		$social_url = bw_social_url( $lc_social, $social );
		$html = bw_ret( bw_follow_link_dash( $social_url, $lc_social, $social_network, "me", "myclass" ) );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_follow_link_dash_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$social = "bobbingwide";
		$lc_social = "wordpress";
		$social_network = "WordPress";
		$social_url = bw_social_url( $lc_social, $social );
		$html = bw_ret( bw_follow_link_dash( $social_url, $lc_social, $social_network, "me", "myclass" ) );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_follow_link_gener() {
		$this->switch_to_locale( "en_GB" );
		$social = "bobbingwide";
		$lc_social = "wordpress";
		$social_network = "WordPress";
		$social_url = bw_social_url( $lc_social, $social );
		$html = bw_ret( bw_follow_link_gener( $social_url, $lc_social, $social_network, "me", "myclass" ) );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_follow_link_gener_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$social = "bobbingwide";
		$lc_social = "wordpress";
		$social_network = "WordPress";
		$social_url = bw_social_url( $lc_social, $social );
		$html = bw_ret( bw_follow_link_gener( $social_url, $lc_social, $social_network, "me", "myclass" ) );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_follow_me__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = bw_follow_me__syntax();
		$html = $this->arraytohtml( $array );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_follow_me__syntax_bb_BB() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "bb_BB" );
		$array = bw_follow_me__syntax();
		$html = $this->arraytohtml( $array );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
		
		
																					
		

}
