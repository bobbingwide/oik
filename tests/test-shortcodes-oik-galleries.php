<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-galleries.php
 */
class Tests_shortcodes_oik_galleries extends BW_UnitTestCase {

	function setUp(): void {
		parent::setUp();
		
		oik_require_lib( "oik-sc-help" );
		oik_require( "shortcodes/oik-galleries.php" ); 														
	}
	
	function test_nggallery__help() {
		$this->switch_to_locale( "en_GB" );
		$html = nggallery__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_nggallery__help_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = nggallery__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
		
	
	function test_nggallery__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = nggallery__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_nggallery__syntax_bb_BB() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "bb_BB" );
		$array = nggallery__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	/**
	 * We don't want nggallery to do anything 
	 * so let's consider replacing the shortcode with null
	 */
	function test_nggallery__example() {
		remove_shortcode( "nggallery" );
		$this->switch_to_locale( "en_GB" );
		$html = bw_ret( nggallery__example() );
		// Fiddle the result to pretend wptexturize worked for double quotes.
		//$html = str_replace( '"', '&#8221;', $html );

    //$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_nggallery__example_bb_BB() {
		remove_shortcode( "nggallery" );
		$this->switch_to_locale( "bb_BB" );
		$html = bw_ret( nggallery__example() );
    //$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}

	
	
}
	
