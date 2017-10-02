<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-bw.php
 */
class Tests_shortcodes_oik_bw extends BW_UnitTestCase {

	function setUp() { 
		parent::setUp();
		
		//oik_require_lib( "oik-sc-help" );
		oik_require( "shortcodes/oik-bw.php" ); 														
	}
	
	function test_bw__syntax() {
		$this->switch_to_locale( "en_GB" );
		$array = bw__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw__syntax_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$array = bw__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}

	function test_bw__example() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$html = bw_ret( bw__example() );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw__example_bb_BB() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "bb_BB" );
		$html = bw_ret( bw__example() );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	
	
}
	
