<?php // (C) Copyright Bobbing Wide 2016

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
}
