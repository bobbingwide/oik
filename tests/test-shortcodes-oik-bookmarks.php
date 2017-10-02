<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-bookmarks.php
 */
class Tests_shortcodes_oik_bookmarks extends BW_UnitTestCase {

	function setUp() { 
		parent::setUp();
		
		oik_require_lib( "oik-sc-help" );
		oik_require( "shortcodes/oik-bookmarks.php" );
	}
	
	function test_bw_bookmarks__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = bw_bookmarks__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_bookmarks__syntax_bb_BB() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "bb_BB" );
		$array = bw_bookmarks__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	/**
	 * Tests bw_bookmarks__example
	 *
	 * Using the __return_null filter removes all the output from bw_bookmarks() so we just get the preliminary stuff
	 */
	function test_bw_bookmarks__example() {
		add_filter( "wp_list_bookmarks", "__return_null" );  														
		$this->switch_to_locale( "en_GB" );
		$html= bw_ret( bw_bookmarks__example() );
		$this->assertArrayEqualsFile( $html );
		remove_filter( "wp_list_bookmarks", "__return_null" );
	}
	
	function test_bw_bookmarks__example_bb_BB() {
		add_filter( "wp_list_bookmarks", "__return_null" );  														
		$this->switch_to_locale( "bb_BB" );
		$html= bw_ret( bw_bookmarks__example() );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
		remove_filter( "wp_list_bookmarks", "__return_null" );
	}
	
	
}
	
