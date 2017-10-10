<?php // (C) Copyright Bobbing Wide 2017

/** 
 * Unit tests for the includes/bw-nav-tab.inc file
 */

class Tests_includes_bw_nav_tab extends BW_UnitTestCase {

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 * - The oik plugin must be activated!
	 */
	function setUp() {
		parent::setUp();
		oik_require( "includes/bw-nav-tab.php" );
	}
	
	/**
	 * Tests bw_nav_tab_link
	 *
	 * Note: We use text that is expected to exist in oik.pot
	 */ 
	function test_bw_nav_tab_link() {
		$this->switch_to_locale( 'en_GB' );
		$html = bw_ret( bw_nav_tab_link( "tab", __( "oik", "oik" ), "page", "tab" ) );
		$html = $this->replace_admin_url( $html );
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	function test_bw_nav_tab_link_bb_BB() {
		$this->switch_to_locale( 'bb_BB' );
		$html = bw_ret( bw_nav_tab_link( "tab", __( "oik", "oik" ), "page", "tab" ) );
		$html = $this->replace_admin_url( $html );
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
}
