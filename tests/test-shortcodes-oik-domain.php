<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-domain.php
 */
class Tests_shortcodes_oik_domain extends BW_UnitTestCase {

	function setUp(): void {
		parent::setUp();
		oik_require( "shortcodes/oik-domain.php" ); 
																
		$oik_plugins = oik_require_lib( "oik_plugins" );
	}
	
	function test_bw_wpadmin() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		bw_update_option( "domain", "qw/src" );
		$html = bw_wpadmin();
		bw_update_option( "domain", null );
		update_option( "siteurl", "http://qw/src" );
		$html .= bw_wpadmin(); 
		$html = $this->replace_admin_url( $html );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_wpadmin_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		bw_update_option( "domain", "qw/src" );
		$html = bw_wpadmin();
		bw_update_option( "domain", null );
		update_option( "siteurl", "http://qw/src" );
		$html .= bw_wpadmin(); 
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	
}
	
