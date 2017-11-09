<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-logo.php
 */
class Tests_shortcodes_oik_logo extends BW_UnitTestCase {

	function setUp() { 
		parent::setUp();
		oik_require_lib( "oik-sc-help" );
		oik_require( "shortcodes/oik-logo.php" ); 
		oik_require_lib( "oik_plugins" );
	}
	
	function test_bw_logo__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = bw_logo__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_logo__syntax_bb_BB() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "bb_BB" );
		$array = bw_logo__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	/**
	 * We need to set a logo image
	 *
	 * In multisite the link will be to the site_url().
	 * We need to set it back to '.'.
	 */ 
	function test_bw_logo() {
		bw_update_option( "logo-image", "image-URL" );
	
		$this->switch_to_locale( "en_GB" );
		$atts = array( "link" => ".", "text" => "logo text" );
		$html = bw_logo( $atts );
		if ( is_multisite() ) {
			$html = str_replace( site_url(), ".", $html );
		}
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	
}
	
