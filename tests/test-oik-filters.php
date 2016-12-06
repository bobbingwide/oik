<?php // (C) Copyright Bobbing Wide 2016

/**
 * @package oik-bob-bing-wide
 * 
 * Test the functions in shortcodes/oik-bob-bing-wide.php
 */
class Tests_oik_filters extends BW_UnitTestCase {

	function setUp() {
		parent::setUp();
		oik_require( "includes/oik-filters.inc" );
	}
	
	static function it_worked( $parm1, $parm2, $parm3 ) {
		$parm1 = "It worked!";
		return( $parm1 );
	}
	
	static function gob( $parm1, $parm2, $parm3 ) {
		return( "go bang - but I'm not expected to be called" );
	}

	/**
	 * Unit test to demonstrate bw_replace_filter
	 * If it works then we get "It worked!" else we gobang()
	 * 
	 * Note: This test works when the function being replaced is not defined as a class method!
	 */
	function test_bw_replace_filter() {
		add_filter( "test_replace", array( $this, "gob" ), 8, 3 );
		bw_replace_filter( "test_replace", array( $this, "gob" ), 8, array( $this, "it_worked") );
		$html = apply_filters( "test_replace", "How's it going?", "2", "3" );
		$expected_output = "It worked!";
		$this->assertEquals( $expected_output, $html );
	}
	
	/**
	 * Unit test to demonstrate bw_disable_filter
	 */
	function test_bw_disable_filter() {
		add_filter( "test_replace", "Tests_oik_filters::it_worked", 8, 3 );
		$result1 = apply_filters( "test_replace", "How's it going?", "2", "3" );
		$this->assertEquals( "It worked!", $result1 );
		bw_disable_filter( "test_replace", "Tests_oik_filters::it_worked", 8 );
		$result2 = apply_filters( "test_replace", "How's it going?", "2", "3" );
		$this->assertEquals( "How's it going?", $result2 );
	}
	
	/**
	 * Unit test to demonstrate bw_restore_filter
	 */
	function test_bw_restore_filter() {
		add_filter( "test_replace", array( $this, "it_worked" ), 8, 3 );
		bw_disable_filter( "test_replace", array( $this, "it_worked" ), 8 );
		$result1 = apply_filters( "test_replace", "How's it going?", "2", "3" );
		$this->assertEquals( "How's it going?", $result1 );
		bw_restore_filter( "test_replace", array( $this, "it_worked" ), 8 );
		$result2 = apply_filters( "test_replace", "How's it going?", "2", "3" );
		$this->assertEquals( "It worked!", $result2 );
	}
		
		
		

}
