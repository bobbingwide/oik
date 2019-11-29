<?php // (C) Copyright Bobbing Wide 2017

/** 
 * Unit tests for the includes/bw_images.inc file
 */

class Tests_includes_bw_images extends BW_UnitTestCase {

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 * - The oik plugin must be activated!
	 */
	function setUp(): void {
		parent::setUp();
		oik_require( "includes/bw_images.inc" );
	}
	
	/**
	 */ 
	function test_bw_link_thumbnail() {
		$this->switch_to_locale( 'en_GB' );
		$post = $this->dummy_post();
		$atts = array( "link" => $post->ID, "title" => "translated text" );
		$html = bw_ret( bw_link_thumbnail( "image tag", null, $atts ) );
		$html = $this->replace_home_url( $html );
		$html = $this->replace_post_id( $html, $post, 'id="link-' );
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
	
	function replace_post_id( $expected, $post, $prefix="post=" ) {
		$expected = str_replace( $prefix . $post->ID, $prefix . "42", $expected );
		return $expected;
	}
	
	
}
	
