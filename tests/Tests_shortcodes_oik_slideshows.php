<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-slideshows.php
 */
class Tests_shortcodes_oik_slideshows extends BW_UnitTestCase {

	function setUp(): void {
		parent::setUp();
		
		oik_require_lib( "oik-sc-help" );
		oik_require( "shortcodes/oik-slideshows.php" ); 	
		oik_require_lib( "oik_plugins" );
	}
	
	function test_portfolio_slideshow__help() {
		$this->switch_to_locale( "en_GB" );
		$html = portfolio_slideshow__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_portfolio_slideshow__help_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = portfolio_slideshow__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}

	function set_global_ps_options() {
		global $ps_options;
		$ps_options = [];
		$ps_options['size'] = null;
		$ps_options['nowrap'] = null;
		$ps_options['speed'] = null;
		$ps_options['trans'] = null;
		$ps_options['timeout'] = null;
		$ps_options['exclude_featured'] = null;
		$ps_options['autoplay'] = null;
		$ps_options['pagerpos'] = null;
		$ps_options['navpos'] = null;
		$ps_options['showcaps'] = null;
		$ps_options['showtitles'] = null;
		$ps_options['showdesc'] = null;
		$ps_options['click'] = null;
		$ps_options['target'] = null;
		$ps_options['centered'] = null;

	}
	
	function test_portfolio_slideshow__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->set_global_ps_options();
		$this->switch_to_locale( "en_GB" );
		$array = portfolio_slideshow__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_portfolio_slideshow__syntax_bb_BB() {
		$this->set_global_ps_options();
		$this->switch_to_locale( "bb_BB" );
		$array = portfolio_slideshow__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	/**
   * 
   * Expects the shortcode to be inactive!
	 */
	function test_bw_portfolio_slideshow__example() {
		$this->switch_to_locale( "en_GB" );
		$html = bw_ret( portfolio_slideshow__example() );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_portfolio_slideshow__example_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = bw_ret( portfolio_slideshow__example() );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}

}
	
