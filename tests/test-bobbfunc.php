<?php // (C) Copyright Bobbing Wide 2017

/** 
 * Unit tests for the libs\bobbfunc.php file
 */

class Tests_oik_bobbfunc extends BW_UnitTestCase {

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 * - we need oik-googlemap to load the functions we're testing
	 */
	function setUp() {
		parent::setUp();
	}
	
	function test_oik_url() {
		$html = oik_url();
		$expected = '/wp-content/plugins/oik/';
		$this->assertStringEndsWith( $expected, $html );
		
		$html = oik_url( "oik.php" );
		$expected = 'wp-content/plugins/oik/oik.php';
		$this->assertStringEndsWith( $expected, $html );
	
		$html = oik_url( "readme.txt", "oik-batch" );
		$expected = 'wp-content/plugins/oik-batch/readme.txt';
		$this->assertStringEndsWith( $expected, $html );
		
	}
	

}
