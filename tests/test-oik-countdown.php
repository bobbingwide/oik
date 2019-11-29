<?php // (C) Copyright Bobbing Wide 2017

/** 
 * Unit tests for the [bw_countdown] shortcode
 * Specifically to test issue #74	- jQuery.fn.load() is deprecated
 */

class Tests_oik_countdown extends BW_UnitTestCase {

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 * - we need oik-countdown to load the functions we're testing
	 */
	function setUp(): void {
		parent::setUp();
		//$oik_plugins = oik_require_lib( "oik_plugins" );
		//bw_trace2( $oik_plugins, "oik_plugins" );
		oik_require( "shortcodes/oik-countdown.php" );
	}
	
	
	function get_jq() {
		global $bw_jq;
		return $bw_jq;
	}
	
	function reset_jq() {
		global $bw_jq;
		$bw_jq = null;
	}

	/**
	 * Test with no parameters whatsoever
	 */
	function test_bw_countdown() {
		$atts = array();
		$html = bw_countdown( $atts, null, null );
		
		$expected = null;
		$expected .= '<div id="countdown-1"></div>';
		$this->assertEquals( $expected, $html );
		
		/**
		 * We need to validate what's been enqueued in the global $bw_jq.
		 *
		 * This primarily tests the change for #74 - no longer using `jQuery( document ).ready( function()`
		 */
		$expected_jq = null;
		$expected_jq .= '<script type="text/javascript">jQuery( function() { jQuery( "div#countdown-1" ).countdown( {"until":10} ); });</script>';
		$jq = $this->get_jq();
		$this->assertEquals( $expected_jq, $jq );
		$this->reset_jq();  
		/**
		 * @TODO Use libs/class-dependencies-cache to 
		 * test that the scripts and styles have been enqueued.
		 */
		
	}

}
