<?php // (C) Copyright Bobbing Wide 2016,2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-email.php
 */
class Tests_shortcodes_oik_email extends BW_UnitTestCase {

	function setUp() { 
	
		parent::setUp();
		//oik_require_lib( "oik-sc-help" );
		oik_require( "shortcodes/oik-email.php" ); 														
		//oik_require( "shortcodes/oik-bob-bing-wide.php" );
		
		//if ( !did_action( "oik_add_shortcodes" ) ) {
    //  do_action( "oik_add_shortcodes" );
		//}	
	}
	
	function test_sc_email() {
		$this->switch_to_locale( "en_GB" );
		$array = _sc_email();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_sc_email_bb_BB() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "bb_BB" );
		$array = _sc_email();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_email__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = bw_email__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_email__syntax_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$array = bw_email__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}

	function test_bw_mailto__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = bw_mailto__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_mailto__syntax_bb_BB() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "bb_BB" );
		$array = bw_mailto__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_email() {
		$this->switch_to_locale( "en_GB" );
		$atts = array( "email" => "herb@bobbingwide.com" ); 
		$html = bw_email( $atts);
		$html_array = $this->tag_break( $html ); 
		$this->assertNotNull( $html_array );
		$html_array = $this->replace_antispambot( $html_array );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	function test_bw_email_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$atts = array( "email" => "herb@bobbingwide.com" ); 
		$html = bw_email( $atts);
		$html_array = $this->tag_break( $html ); 
		$this->assertNotNull( $html_array );
		$html_array = $this->replace_antispambot( $html_array );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_mailto() {
		$this->switch_to_locale( "en_GB" );
		$atts = array( "email" => "herb@bobbingwide.com" ); 
		$html = bw_mailto( $atts);
		$html_array = $this->tag_break( $html ); 
		$this->assertNotNull( $html_array );
		$html_array = $this->replace_antispambot( $html_array );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	function test_bw_mailto_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$atts = array( "email" => "herb@bobbingwide.com" ); 
		$html = bw_mailto( $atts);
		$html_array = $this->tag_break( $html ); 
		$this->assertNotNull( $html_array );
		$html_array = $this->replace_antispambot( $html_array );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( "en_GB" );
	}
	
	
	
	
	
}
	
