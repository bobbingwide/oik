<?php // (C) Copyright Bobbing Wide 2017

/** 
 * Unit tests for the includes/bw_messages.inc file
 */

class Tests_includes_bw_messages extends BW_UnitTestCase {

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 * - The oik plugin must be activated!
	 */
	function setUp(): void {
		parent::setUp();
		$this->unset_bw_messages();
		oik_require( "includes/bw_messages.php" );
		
	}
	
	function unset_bw_messages() {
		unset( $GLOBALS[ 'bw_messages' ] );
	}
	
	/**
	 * Tests bw_display_messages, bw_display_message and bw_issue_message
	 *
	 * Note: We use text that is expected to exist in oik.pot
	 */ 
	function test_bw_display_messages() {
		$this->switch_to_locale( 'en_GB' );
		bw_issue_message( "field", "code", __( "Invalid. Please correct and retry.", "oik" ) );
		$html = bw_ret( bw_display_messages() );
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	 
	/**
	 * Tests bw_display_message, bw_display_message and bw_issue_message
	 *
	 * Note: We use text that is expected to exist in oik.pot
	 */ 
	function test_bw_display_messages_bb_BB() {
		$this->switch_to_locale( 'bb_BB' );
		bw_issue_message( "field", "code", __( "Invalid. Please correct and retry.", "oik" ) );
		$html = bw_ret( bw_display_messages() );
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	} 
	
}
	
