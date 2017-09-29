<?php // (C) Copyright Bobbing Wide 2017

/** 
 * Unit tests for the includes/bw_formatter.inc file
 */

class Tests_includes_bw_formatter extends BW_UnitTestCase {

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 * - The oik plugin must be activated!
	 */
	function setUp() {
		parent::setUp();
		oik_require( "includes/bw_formatter.inc" );
	}
	
	/**
	 * Tests bw_field_function_edit
	 *
	 * User needs to be logged in to get any output
	 */ 
	function test_bw_field_function_edit() {
		wp_set_current_user( 1 );
		$this->switch_to_locale( 'en_GB' );
		$post = $this->dummy_post();
		$atts = null;
		$html = bw_ret( bw_field_function_edit( $post, $atts, null ) );
		$html = $this->replace_admin_url( $html );
		$html = $this->replace_post_id( $html, $post );
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	/**
	 * Tests bw_field_function_edit
	 *
	 * User needs to be logged in to get any output
	 * 
	 * Note: Currently [Edit] is [Edit] in bb_BB
	 */ 
	function test_bw_field_function_edit_bb_BB() {
		wp_set_current_user( 1 );
		$this->switch_to_locale( 'bb_BB' );
		$post = $this->dummy_post();
		$atts = null;
		$html = bw_ret( bw_field_function_edit( $post, $atts, null ) );
		$html = $this->replace_admin_url( $html );
		$html = $this->replace_post_id( $html, $post );
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	function dummy_post() {
		//$post = new stdClass;
		//$post->ID = 1;
		$args = array( 'post_type' => 'page', 'post_title' => 'post title' );
		$id = self::factory()->post->create( $args );
		$post = get_post( $id );
		return $post;
	}
	
	/**
	 * Replaces home_url and site_url
	 *
	 * Should we consider using https://example.com ?
	 * @param string $expected
	 * @return string with site_url and home_url replaced by hard coded values
	 */
	function replace_home_url( $expected ) {
		$expected = str_replace( home_url(), "https://qw/src", $expected );
		$expected = str_replace( site_url(), "https://qw/src", $expected );
		return $expected;
	}
	
	function replace_post_id( $expected, $post ) {
		$expected = str_replace( "post=" . $post->ID, "post=42", $expected );
		return $expected;
	}
	
	
}
	
