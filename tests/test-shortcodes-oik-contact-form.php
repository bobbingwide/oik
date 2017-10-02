<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-contact-form.php
 */
class Tests_shortcodes_oik_contact_form extends BW_UnitTestCase {

	function setUp() { 
	
		parent::setUp();
		oik_require( "shortcodes/oik-contact-form.php" ); 														
		
		//if ( !did_action( "oik_add_shortcodes" ) ) {
		//do_action( "oik_add_shortcodes" );
		//}	
	}
	
	/**
	 * test bw_contact_form invalid user
	 */
	function test_bw_contact_form_invalid_user() {
		$this->switch_to_locale( "en_GB" );
		$html = bw_contact_form( null );
    //$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_contact_form_invalid_user_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = bw_contact_form( null );
    //$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	/**
	 */
	function test_bw_contact_form() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$atts = array( "email" => "herb@bobbingwide.com" );
		$html = bw_contact_form( $atts );
		$html = $this->replace_contact_form_id( $html );
		$html_array = $this->tag_break( $html );
		$this->assertNotNull( $html_array );
		$html_array = $this->replace_nonce_with_nonsense( $html_array, "_oik_contact_nonce", "_oik_contact_nonce" );
    //$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	function test_bw_contact_form_bb_BB() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "bb_BB" );
		$atts = array( "email" => "herb@bobbingwide.com" );
		$html = bw_contact_form( $atts );
		$html = $this->replace_contact_form_id( $html );
		$html_array = $this->tag_break( $html );
		$this->assertNotNull( $html_array );
		$html_array = $this->replace_nonce_with_nonsense( $html_array, "_oik_contact_nonce", "_oik_contact_nonce" );
    //$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( "en_GB" );
	}
	
	/**
	 * We need to set the contact form ID in the generated html to a predefined value
	 * `<input type="submit" name="oiku_contact-2" value="Contact mE" />`
	 */
	function replace_contact_form_id( $html ) {
		$html = str_replace( bw_contact_form_id(), "oiku_contact-1", $html );
		return $html;
	}
	
}
