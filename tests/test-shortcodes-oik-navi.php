<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-navi.php
 */
class Tests_shortcodes_oik_navi extends BW_UnitTestCase {

	function setUp() { 
		parent::setUp();
		oik_require_lib( "oik-sc-help" );
		oik_require( "shortcodes/oik-navi.php" ); 
		update_option( "posts_per_page", 10 ); 											
	}
	
	
	function test_bw_navi__help() {
		$this->switch_to_locale( "en_GB" );
		$html = bw_navi__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_navi__help_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = bw_navi__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_navi__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = bw_navi__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_navi__syntax_bb_BB() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "bb_BB" );
		$array = bw_navi__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_navi_s2eofn() {
    $this->switch_to_locale( "en_GB" );
		$html = bw_ret( bw_navi_s2eofn( 10, 20, 30, "prefix" ) );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_navi_s2eofn_bb_BB() {
    $this->switch_to_locale( "bb_BB" );
		$html = bw_ret( bw_navi_s2eofn( 10, 20, 30, "prefix" ) );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
    $this->switch_to_locale( "en_GB" );
	}
	
}
	
