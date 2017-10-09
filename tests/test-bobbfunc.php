<?php // (C) Copyright Bobbing Wide 2017

/** 
 * Unit tests for the libs\bobbfunc.php file
 * 
 * More tests are implemented in oik-libs
 * @TODO Move these tests to oik-libs?
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
		
	/**
	 * To test bw_tt() we may need to have used bw_dtt() 
	 * If bw_dtt() hasn't been called then the string might not get translated
	 */
	function test_bw_tt() {
		$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( 'bb_BB' );
		
		$l10n = bw_tt( "bobbingwide" );
		$this->assertEquals( "bboibgniwde", $l10n );
		$l10n = bw_tt( "oik" );
		$this->assertEquals( "OIk", $l10n );
		
		$bw_name = bw_dtt( "bw", "bobbingwide" );
		$oik = bw_dtt( "oik", "oik" );
		
		$l10n = bw_tt( "bobbingwide" );
		$this->assertEquals( "bboibgniwde", $l10n );
		$l10n = bw_tt( "oik" );
		$this->assertEquals( "OIk", $l10n );
		
		$l10n = bw_tt( "dinlo" );
		$this->assertEquals( "dinlo", $l10n );
		
		$this->switch_to_locale( 'en_GB' );
	}

}
