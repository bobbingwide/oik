<?php // (C) Copyright Bobbing Wide 2017

/** 
 * Unit tests for the bobbcomp.inc file
 */

class Tests_oik_bobbcomp extends BW_UnitTestCase {

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 * - we need oik-googlemap to load the functions we're testing
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

}