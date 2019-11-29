<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-wtf.php
 */
class Tests_shortcodes_oik_wtf extends BW_UnitTestCase {

	function setUp(): void {
		parent::setUp();
		
		oik_require_lib( "oik-sc-help" );
		oik_require( "shortcodes/oik-wtf.php" ); 	
		oik_require_lib( "oik_plugins" );
	}
	
	function test_bw_wtf__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = bw_wtf__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_wtf__syntax_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$array = bw_wtf__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_wtf() {
		$this->switch_to_locale( "en_GB" );
		$atts = null;
		$content = "This is <i>the</i> source.";
		$html = bw_wtf( $atts, $content );
		$html = $this->replace_home_url( $html );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_wtf_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$atts = null;
		$content = "This is <i>the</i> source.";
		$html = bw_wtf( $atts, $content );
		$html = $this->replace_home_url( $html );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	
}
	
