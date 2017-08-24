<?php // (C) Copyright Bobbing Wide 2017

/** 
 * Unit tests for the bobbcomp.inc file
 */

class Tests_oik_bobbcomp extends BW_UnitTestCase {

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 * - The oik plugin must be activated!
	 */
	function setUp() {
		parent::setUp();
	}
	
	
	/**
   * Specifically to test issue #72	-	bw_get_option_arr() should return the parameter value from $atts if set
	 */
	function test_bw_get_option_arr_field_present_in_atts() {
		$value = bw_get_option_arr( "field", "na", array( "field" => "value" ) );
		$this->assertEquals( "value", $value );
	}
	
	/**
	 * Here we set alt=0 to prevent oik-user from becoming involved
	 * We request a field that should't exist in 'bw_options', so expect null to be returned.
	 */
	function test_bw_get_option_arr_field_not_present_in_atts() {
		$value = bw_get_option_arr( "dleif", null, array( "field" => "value", "alt" => "0" ) );
		$this->assertNull( $value );
	}
	
	function test_bw_oik() {
		$this->switch_to_locale( 'bb_BB' );
		$html = bw_oik();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( 'en_GB' );
	}
	
	function test_bw_oik_long() {
		$this->switch_to_locale( 'bb_BB' );
		$html = bw_oik_long();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( 'en_GB' );
	}
	

}
