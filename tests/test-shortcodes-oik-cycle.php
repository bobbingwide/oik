<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-cycle.php
 */
class Tests_shortcodes_oik_cycle extends BW_UnitTestCase {

	function setUp() { 
		parent::setUp();
		oik_require( "includes/oik-sc-help.inc" );
		oik_require( "shortcodes/oik-cycle.php" ); 														
	}
	
	function test_bw_cycle__help() {
		$this->switch_to_locale( "en_GB" );
		$html = bw_cycle__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_cycle__help_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = bw_cycle__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_cycle__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = bw_cycle__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_cycle__syntax_bb_BB() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "bb_BB" );
		$array = bw_cycle__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	
}
	
