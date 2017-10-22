<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-bob-bing-wide.php
 * Note: The shortcodes themselves are implemented in the oik-bob-bing-wide plugin
 * Some of the functions are tested in tests/oik-bob-bing-wide.php
 * 
 */
class Tests_shortcodes_oik_bob_bing_wide extends BW_UnitTestCase {

	function setUp() { 
		parent::setUp();
		
		//oik_require_lib( "oik-sc-help" );
		oik_require( "shortcodes/oik-bob-bing-wide.php" ); 	
		//oik_require_lib( "oik_plugins" );
	}
	
	/**
	 * bw_lbw() is already tested but not for bb_BB
	 */
	function test_bw_lbw() {
		$this->switch_to_locale( "en_GB" );
		$html = bw_lbw();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_lbw_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = bw_lbw();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_loik() {
		$this->switch_to_locale( "en_GB" );
		$html = bw_loik();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_loik_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = bw_loik();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
}
	
