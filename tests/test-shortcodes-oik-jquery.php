<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-jquery.php
 */
class Tests_shortcodes_oik_jquery extends BW_UnitTestCase {

	function setUp() { 
		parent::setUp();
		
		oik_require( "includes/oik-sc-help.inc" );
		oik_require( "shortcodes/oik-jquery.php" ); 														
	}
	
	function test_bw_jq__help() {
		$this->switch_to_locale( "en_GB" );
		$html = bw_jq__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_jq__help_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = bw_jq__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_jq__syntax() {
		$this->switch_to_locale( "en_GB" );
		$array = bw_jq__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_jq__syntax_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$array = bw_jq__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	
}
	
