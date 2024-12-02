<?php // (C) Copyright Bobbing Wide 2016,2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-power.php
 */
class Tests_shortcodes_oik_power extends BW_UnitTestCase {

	function setUp(): void {
		parent::setUp();
		
		oik_require_lib( "oik-sc-help" );
		oik_require( "shortcodes/oik-power.php" ); 														
	}
	
	function test_bw_power() {
		$this->switch_to_locale( "en_GB" );
		$html = bw_power();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_power_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = bw_power();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}

	
	
}
