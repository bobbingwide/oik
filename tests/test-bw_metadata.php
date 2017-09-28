<?php // (C) Copyright Bobbing Wide 2017

/** 
 * Unit tests for the bw_metadata.inc file
 */

class Tests_bw_metadata extends BW_UnitTestCase {

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 * - The oik plugin must be activated!
	 */
	function setUp() {
		parent::setUp();
	}
	
	/**
	 * Note: these tests don't display a current value since the field name is not expected to be set in $_REQUEST
	 */ 
	function test_bw_form_field_() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( 'en_GB' );
		$html = bw_ret( bw_form_field_( "field_name", "text", "translated text", "translated value", array() ) );
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	function test_bw_form_field_email() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( 'en_GB' );
		$html = bw_ret( bw_form_field_email( "email_name", "email", "translated text", "translated value", array() ) );
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	function test_bw_form_field_textarea() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( 'en_GB' );
		$html = bw_ret( bw_form_field_textarea( "textarea_name", "textarea", "translated text", "translated value", array() ) );
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	
	
}
	
