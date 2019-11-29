<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-contact-form.php
 */
class Tests_shortcodes_oik_contact_form extends BW_UnitTestCase {

	function setUp(): void {
	
		parent::setUp();
		oik_require( "shortcodes/oik-contact-form.php" );
		
		oik_require( "includes/oik-sc-help.php" );
		oik_require_lib( "oik_plugins" );
		
	}
	
	/**
	 * test bw_contact_form invalid user
	 */
	function test_bw_contact_form_invalid_user() {
		bw_update_option( "email", null );
		$this->switch_to_locale( "en_GB" );
		$html = bw_contact_form( null );
    //$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_contact_form_invalid_user_bb_BB() {
	
		bw_update_option( "email", null );
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
		bw_update_option( "contact", null );
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
		bw_update_option( "contact", null );
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
	
	function test_bw_thankyou_message() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		bw_thankyou_message( null, true, true );
		bw_thankyou_message( null, true, false );
		bw_thankyou_message( null, false, true );
		bw_thankyou_message( null, false, false );
		$html = bw_ret(); 
    //$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_thankyou_message_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		bw_thankyou_message( null, true, true );
		bw_thankyou_message( null, true, false );
		bw_thankyou_message( null, false, true );
		bw_thankyou_message( null, false, false );
		$html = bw_ret(); 
    //$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_contact_form__help() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$html = bw_contact_form__help();
    //$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_contact_form__help_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = bw_contact_form__help();
    //$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_contact_form__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = bw_contact_form__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_contact_form__syntax_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$array = bw_contact_form__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	/**
	 * We don't really need a valid user to test this
	 */
	function test_bw_contact_form__example() {
		bw_update_option( "email", null );
		$this->switch_to_locale( "en_GB" );
		$html = bw_ret( bw_contact_form__example() );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_contact_form__example_bb_BB() {
		bw_update_option( "email", null );
		$this->switch_to_locale( "bb_BB" );
		$html = bw_ret( bw_contact_form__example() );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	/**
	 * Here we're trynig to get the message, not the contact form
	 */
	function test_bw_contact_form__snippet() {
		$_REQUEST['oiku_contact'] = "set";
		$this->switch_to_locale( "en_GB" );
		$html = bw_ret( bw_contact_form__snippet() );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		unset( $_REQUEST['oiku_contact'] );
	}
	
	function test_bw_contact_form__snippet_bb_BB() {
		$_REQUEST['oiku_contact'] = "set";
		$this->switch_to_locale( "bb_BB" );
		$html = bw_ret( bw_contact_form__snippet() );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		unset( $_REQUEST['oiku_contact'] );
		$this->switch_to_locale( "en_GB" );
	}

}
