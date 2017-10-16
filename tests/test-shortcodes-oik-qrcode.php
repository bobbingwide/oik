<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-qrcode.php
 */
class Tests_shortcodes_oik_qrcode extends BW_UnitTestCase {

	function setUp() { 
		parent::setUp();
		
		//oik_require_lib( "oik-sc-help" );
		oik_require( "shortcodes/oik-qrcode.php" ); 	
		oik_require_lib( "oik_plugins" );
	}
	
	function test_bw_qrcode__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		bw_update_option( "company", "Bobbing Wide" );
		$this->switch_to_locale( "en_GB" );
		$array = bw_qrcode__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_qrcode__syntax_bb_BB() {
		bw_update_option( "company", "Bobbing Wide" );
		$this->switch_to_locale( "bb_BB" );
		$array = bw_qrcode__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_qrcode() {
		bw_update_option( "qrcode-image", "/qrcode-image.jpg" );
		bw_update_option( "company", "company" );
		$this->switch_to_locale( "en_GB" );
		$atts = array( "link" => "https://example.com" );
		$html = bw_qrcode( $atts );
		$html = $this->replace_home_url( $html );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_qrcode_bb_BB() {
		bw_update_option( "qrcode-image", "/qrcode-image.jpg" );
		bw_update_option( "company", "company" );
		$this->switch_to_locale( "bb_BB" );
		$atts = array( "link" => "https://example.com" );
		$html = bw_qrcode( $atts );
		$html = $this->replace_home_url( $html );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
		
		
	
	
}
	
