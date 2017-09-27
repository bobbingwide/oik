<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-company.php
 */
class Tests_shortcodes_oik_company extends BW_UnitTestCase {

	function setUp() { 
		parent::setUp();
		
		oik_require_lib( "oik-sc-help" );
		oik_require( "shortcodes/oik-company.php" ); 														
	}
	
	function test_bw_contact__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = bw_contact__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_contact__syntax_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$array = bw_contact__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	
}
	
