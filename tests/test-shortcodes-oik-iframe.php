<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-iframe.php
 */
class Tests_shortcodes_oik_iframe extends BW_UnitTestCase {

	function setUp() { 
		parent::setUp();
		oik_require( "shortcodes/oik-iframe.php" );
		oik_require( "includes/oik-sc-help.php" ); 														
	}
	
	function test_bw_iframe__help() {
		$this->switch_to_locale( "en_GB" );
		$html = bw_iframe__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_iframe__help_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = bw_iframe__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );

	}
	
	function test_bw_iframe__syntax() {
		$this->switch_to_locale( "en_GB" );
		$array = bw_iframe__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_iframe__syntax_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$array = bw_iframe__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	
}
	
