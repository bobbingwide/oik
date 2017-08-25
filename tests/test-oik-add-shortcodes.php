<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package oik-add-shortcodes
 * 
 * Tests for logic in oik-add-shortcodes.php
 */
class Tests_oik_add_shortcodes extends BW_UnitTestCase {

	function setUp() { 
	}

	/**
	 * Test for Issue #64
	 * 
	 * Casting a null string to an array works fine
	 * Casting an empty string to an array creates  `array( 0 => '' )`
	 * This can cause problems with bw_array_get(), which caused DIY oik to fail
	 * to expand any shortcodes.
	 * 
	 */
	function test_bw_cast_array() {
		$expected_output = array();
		$atts = null;
		$atts = bw_cast_array( $atts );
		$this->assertEquals( $atts, $expected_output );
		$atts = '';
		$atts = bw_cast_array( $atts );
		$this->assertEquals( $atts, $expected_output );
		$atts = '0';
		$atts = bw_cast_array( $atts );
		$this->assertEquals( $atts, array( '0' ) );
	}
	
	function test__bw_missing_shortcodefunc() {
		$this->switch_to_locale( "bb_BB" );
		$html = _bw_missing_shortcodefunc( null, null, "missing-shortcode" );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	

}
	
