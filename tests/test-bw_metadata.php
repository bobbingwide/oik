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
	
	
	/**
	 * Tests bw_effort_box
	 * 
	 * - We need to capture the output
	 * - $post->ID must exist 
	 * - it doesn't need to have post_meta
	 * - global $bw_fields needs to be set
	 * - field type of taxonomy need not be included in the list of fields
	 * - We don't need to worry about i18n
	 */
	function test_bw_effort_box() {
		$this->switch_to_locale( 'en_GB' );
		$this->reset_global_bw_fields();
		$post = $this->dummy_post();
		$args = $this->get_args(); 
		ob_start();   
		bw_effort_box( $post, $args );  
		$html = ob_get_contents();
		ob_end_clean();
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
		
	function reset_global_bw_fields() {
		//global $bw_fields;
		//unset( $bw_fields );
		bw_register_field( "field", "text", "field title", array() );
	}
		
	function dummy_post() {
		$post = new stdClass;
		$post->ID = 42;
		return $post;
	}
	
	function get_args() {
		$args = array( "args" => array( "field" ) );
		return $args;
	}
		

		
	
	
	
}
	
