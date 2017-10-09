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
	
	/**
	 * This is the code that is being deprecated
	 */
	function test__bw_form_field_title_hash_hint() {
		$this->setExpectedDeprecated( "bw_translate" );
		$this->unset_bw_l10n();
		$this->switch_to_locale( 'en_GB' );
		$args = array( '#hint' => "bobbingwide" );
		$html = _bw_form_field_title( __( "oik", "oik" ), $args );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	/**
	 * This is the code that is being deprecated.
	 * Note: #hint strings only get translated if the string itself has been somehow registered as translatable
	 * So "bobbingwide will be translated but "bobbing wide" won't be.
	 * 
	 */
	function test__bw_form_field_title_hash_hint_bb_BB() {
		$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( 'bb_BB' );
		$this->unset_bw_l10n();
		$oik = __( "oik", "oik" );
		$this->assertEquals( "OIk", $oik );
		$args = array( '#hint' => "bobbingwide" );
		$html = _bw_form_field_title( $oik, $args );
		$args = array( '#hint' => "bobbing wide" );
		$html .= _bw_form_field_title( $oik, $args );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( 'en_GB' );
	}
	
	/**
	 * This is the replacement logic using 'hint' rather than #hint
	 * where hint has to be translated already
	 */
	function test__bw_form_field_title_hint() {
		$this->switch_to_locale( 'en_GB' );
		$this->unset_bw_l10n();
		$args = array( 'hint' => __( "bobbingwide", "oik" ) );
		$html = _bw_form_field_title( __( "oik", "oik" ), $args );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	
	/**
	 * This is the replacement logic using 'hint' rather than #hint
	 * where hint has to be translated already
	 */
	function test__bw_form_field_title_hint_bb_BB() {
		$this->switch_to_locale( 'bb_BB' );
		$this->unset_bw_l10n();
		$args = array( 'hint' => __( "bobbingwide", "oik" ) );
		$html = _bw_form_field_title( __( "oik", "oik" ), $args );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		
		$this->switch_to_locale( 'en_GB' );
	}
	
	function unset_bw_l10n() {
		unset( $GLOBALS[ 'bw_l10n' ] );
	}
	
	/**
	 * We no longer want to perform a secret translation of the field title if it's unchanged after
	 * applying the "oik_form_field_title_${field_name}" filter.
	 */
	function test_bw_l10n_field_title() {
		$this->switch_to_locale( 'en_GB' );
		$title = __( "oik documentation", "oik" );
		$html = bw_l10n_field_title( "field_name", $title, "text", "field value", array( "hint" => __( "oik", "oik" ) ) );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( 'en_GB' );
	}
	
	function test_bw_l10n_field_title_bb_BB() {
		$this->switch_to_locale( 'bb_BB' );
		$title = __( "oik documentation", "oik" );
		$html = bw_l10n_field_title( "field_name", $title, "text", "field value", array( "hint" => __( "oik", "oik" ) ) );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( 'en_GB' );
	}
	
	
	
	
	

	
	
	
}
	
