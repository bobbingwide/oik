<?php // (C) Copyright Bobbing Wide 2016,2017

/**
 * @package oik-bob-bing-wide
 * 
 * Test the functions in shortcodes/oik-bob-bing-wide.php
 */
class Tests_oik_bob_bing_wide extends BW_UnitTestCase {

	function setUp() {
		parent::setUp();
		oik_require( "shortcodes/oik-bob-bing-wide.php" );
	}

	/**
	 * Unit test equivalent to [wp v p m]
	 */
	function test_bw_wp_v0_p1_m2() {
		$expected_output = '<span class="wordpress"><span class="bw_word">Word</span><span class="bw_press">Press</span>';
		global $wp_version;
		$expected_output .= " $wp_version";
		$expected_output .= ". PHP: ";
    $expected_output .= phpversion();
    $memory_limit = ini_get( "memory_limit" );
		$expected_output .= ". Memory limit: " . $memory_limit;
		$expected_output .= '</span>';
	
		$html = bw_wp( array( "v", "p", "m" ) );
		$this->assertEquals( $expected_output, $html );
	}
	
	/**
	 * Unit test equivalent to [wp v m=y]
	 * 
	 * Cheating here. We're not testing that the WordPress version is included by PHP version is not.
	 */
	function test_bw_wp_v0_my() {
    $memory_limit = ini_set( "memory_limit", "256M" );
		$expected_output = null;
		$expected_output .= ". Memory limit: 256M";
		$expected_output .= '</span>';
		$html = bw_wp( array( "v", "m" => "y" ) );
		$this->assertStringEndsWith( $expected_output, $html ); 
	}
	
	/**
	 * Tests help for [oik] and [OIK]
	 */ 
	function test_oik__help() {
		$html = oik__help( "oik" );
		$html .= oik__help( "OIK" );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	/**
	 * Tests help for [oik] and [OIK] in bb_BB
	 */ 
	function test_oik__help_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = oik__help( "oik" );
		$html .= oik__help( "OIK" );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	/**
	 * Tests example for [oik] and [OIK]
	 */ 
	function test_oik__example() {
		oik__example( "oik" );
		oik__example( "OIK" );
		$html = bw_ret();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	/**
	 * Tests example for [oik] and [OIK] in bb_BB
	 */ 
	function test_oik__example_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		oik__example( "oik" );
		oik__example( "OIK" );
		$html = bw_ret();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
}
