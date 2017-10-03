<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-googlemap.php
 */
class Tests_shortcodes_oik_googlemap extends BW_UnitTestCase {

	function setUp() { 
		parent::setUp();
		
		oik_require_lib( "oik-sc-help" );
		oik_require( "shortcodes/oik-googlemap.php" ); 														
	}
	
	function test_bw_show_googlemap__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = bw_show_googlemap__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_show_googlemap__syntax_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$array = bw_show_googlemap__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	/**
	 * We need to reset bw_gmap_map() to 0
	 */
	function test_bw_show_googlemap__example() {
		bw_gmap_map( null );
		$this->switch_to_locale( "en_GB" );
		$html = bw_ret( bw_show_googlemap__example() );
		$html_array = $this->tag_break( $html );
    //$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	function test_bw_show_googlemap__example_bb_BB() {
		bw_gmap_map( null );
		$this->switch_to_locale( "bb_BB" );
		$html = bw_ret( bw_show_googlemap__example() );
		$html_array = $this->tag_break( $html );
    //$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( "en_GB" );
	}
	
	

	
	
}
	
