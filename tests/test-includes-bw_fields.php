<?php // (C) Copyright Bobbing Wide 2017

/** 
 * Unit tests for the includes/bw_fields.inc file - now libs/bw_fields.php
 */

class Tests_includes_bw_fields extends BW_UnitTestCase {

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 * - The oik plugin must be activated!
	 */
	function setUp(): void {
		parent::setUp();
	}
	
	/**
	 * Tests bw_theme_field__title - en_GB only
	 */ 
	function test_bw_theme_field__title() {
		$this->switch_to_locale( 'en_GB' );
		$html = bw_ret( bw_theme_field__title( null, "translated title", array( "post" => $this->dummy_post() ) ) );
		$html = $this->replace_home_url( $html );
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
	
	
}
	
