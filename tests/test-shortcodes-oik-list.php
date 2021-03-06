<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-list.php
 */
class Tests_shortcodes_oik_list extends BW_UnitTestCase {

	function setUp(): void {
		parent::setUp();
		oik_require_lib( "oik-sc-help" );
		oik_require( "shortcodes/oik-list.php" ); 
		update_option( "posts_per_page", 10 ); 											
	}
	
	function test_bw_list__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = bw_list__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_list__syntax_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$array = bw_list__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
}
	
