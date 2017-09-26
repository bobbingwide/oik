<?php // (C) Copyright Bobbing Wide 2016,2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-codes.php
 */
class Tests_shortcodes_oik_codes extends BW_UnitTestCase {

	function setUp() { 
	
		parent::setUp();
		//oik_require( "admin/oik-admin.inc" );	 
		//oik_require( "includes/oik-sc-help.inc" );
		oik_require( "shortcodes/oik-codes.php" ); 														
		//$this->_url = oik_get_plugins_server();
		
		oik_require( "shortcodes/oik-bob-bing-wide.php" );
		
		if ( !did_action( "oik_add_shortcodes" ) ) {
      do_action( "oik_add_shortcodes" );
		}	
	}
	
	
	/**
	 * Reduce a print_r'ed string
	 *
	 * print_r's an array then removes unwanted white space
	 */
	function arraytohtml( $array ) {
		$string = print_r( $array, true );
		$again = explode( "\n", $string );
		$again = array_map( "trim", $again );
		$string = implode( "\n", $again );
		return $string;
	}
	
	function test_bw_code__help() {
		$this->switch_to_locale( "en_GB" );
		$html = bw_code__help( null );
    //$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_code__help_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = bw_code__help( null );
    //$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_code__syntax() {
		$this->switch_to_locale( "en_GB" );
		$array = bw_code__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_code__syntax_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$array = bw_code__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	/**
	 * If "oik_add_shortcodes" has not been run then we won't find the correct name for the implementing function.
	 * 
	 */
	function test_bw_code__example() {
		$this->switch_to_locale( "en_GB" );
		$html = bw_ret( bw_code__example() );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_code__example_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = bw_ret( bw_code__example() );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	
}
