<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-paypal.php
 */
class Tests_shortcodes_oik_paypal extends BW_UnitTestCase {

	function setUp() { 
		parent::setUp();
		oik_require_lib( "oik-sc-help" );
		oik_require( "shortcodes/oik-paypal.php" ); 														
	}
	

	/**
	 * @TODO - Remove the dependency upon PayPal options being set, by setting in the test.
	 * PayPal country = "United Kingdom"
	 *
	 *
	 */
	function test_paypal__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = paypal__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_paypal__syntax_bb_BB() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "bb_BB" );
		$array = paypal__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	
	/**
	 * Test the default processing
	 */ 
	function test_bw_pp_shortcodes() {  
		$atts= null;
		bw_update_option( "paypal-email", "herb@bobbingwide.com" );
		$html = bw_pp_shortcodes( $atts );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	
	/**
	 * Test the default shortcode produced by the PayPal button
	 * Issue #101 - [paypal type="pay" shipadd="0"]
	 */ 
	function test_bw_pp_shortcodes_issue_101() {  
		$atts= array( "type" => "pay" );
		bw_update_option( "paypal-email", "herb@bobbingwide.com" );
		$html = bw_pp_shortcodes( $atts );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	
	
}
	
