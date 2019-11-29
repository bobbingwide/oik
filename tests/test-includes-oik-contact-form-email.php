<?php // (C) Copyright Bobbing Wide 2017

/** 
 * Unit tests for the includes/oik-contact-form-email.php file
 */

class Tests_includes_oik_contact_form_email extends BW_UnitTestCase {

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 * - The oik plugin must be activated!
	 */
	function setUp(): void {
		parent::setUp();
		oik_require( "includes/oik-contact-form-email.php" );
	}
	
	/**
	 * Tests a failure to send an email using bw_send_email.
	 * 
	 * We might simply expect to get the following error, which would be detected by bw_trace_error_handler
	 * ` 
    [0] => 2
    [1] => Warning: mail(): Failed to connect to mailserver at "localhost" port 25, verify your "SMTP" and "smtp_port" setting in php.ini or use ini_set()
    [2] => C:\apache\htdocs\wordpress\wp-includes\class-phpmailer.php
    [3] => 698 
	 * `
	 */ 
	function test_bw_send_email() {
		$this->switch_to_locale( 'en_GB' );
		$atts = array();
		$fields = array( "from" => "herb@bobbingwide.com" );
		$result = bw_send_email( "herb@bobbingwide.com", "Subject", "Message", null, $fields );
		$this->assertFalse( $result );
		$html = bw_ret(); 
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	function test_bw_send_email_bb_BB() {
		$this->switch_to_locale( 'bb_BB' );
		$atts = array();
		$fields = array( "from" => "herb@bobbingwide.com" );
		$result = bw_send_email( "herb@bobbingwide.com", "Subject", "Message", null, $fields );
		$this->assertFalse( $result );
		$html = bw_ret(); 
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
}
	
