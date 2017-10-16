<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-table.php
 */
class Tests_shortcodes_oik_table extends BW_UnitTestCase {

	function setUp() { 
		parent::setUp();
		
		oik_require_lib( "oik-sc-help" );
		oik_require( "shortcodes/oik-table.php" ); 	
		oik_require_lib( "oik_plugins" );
		update_option( "posts_per_page", 10 ); 											
	}
	
	function test_bw_table__help() {
		$this->switch_to_locale( "en_GB" );
		$html = bw_table__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_table__help_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = bw_table__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_table__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = bw_table__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_table__syntax_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$array = bw_table__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	/**
   * 
	 */
	function test_bw_bw_table__example() {
		$this->switch_to_locale( "en_GB" );
		$html = bw_ret( bw_table__example() );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_bw_table__example_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = bw_ret( bw_table__example() );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_bw_table__snippet() {
		$this->switch_to_locale( "en_GB" );
		$html = bw_ret( bw_table__snippet() );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_bw_table__snippet_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = bw_ret( bw_table__snippet() );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}

}
	
