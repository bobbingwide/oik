<?php // (C) Copyright Bobbing Wide 2017

/** 
 * Unit tests for the includes/bw_posts.inc file
 */

class Tests_includes_bw_posts extends BW_UnitTestCase {

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 * - The oik plugin must be activated!
	 */
	function setUp() {
		parent::setUp();
		oik_require( "includes/bw_posts.inc" );
	}
	
	/**
	 * Tests bw_more_text
	 */ 
	function test_bw_more_text() {
		$this->switch_to_locale( 'en_GB' );
		$atts = array();
		$html = bw_more_text( null, $atts );
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	function test_bw_more_text_bb_BB() {
		$this->switch_to_locale( 'bb_BB' );
		$atts = array();
		$html = bw_more_text( null, $atts );
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	/**
	 * Here we test a very basic post just to see the br tag. 
	 * We don't need to test bb_BB
	 */
	function test_bw_format_post() {
		$this->switch_to_locale( 'en_GB' );
		$atts = array();
		$atts['block'] = false;
		
		$post = $this->dummy_post();
		$html = bw_ret( bw_format_post( $post, $atts ) );
		$html = $this->replace_home_url( $html );
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	function dummy_post() {
		$args = array( 'post_type' => 'page', 'post_title' => 'post title', 'post_excerpt' => 'Excerpt. No post ID' );
		$id = self::factory()->post->create( $args );
		$post = get_post( $id );
		return $post;
	}
	
	function test_bw_format_more() {
		$this->switch_to_locale( 'en_GB' );
		$atts = array( "read_more" => "more" );
		$post = $this->dummy_post();
		$html = bw_ret( bw_format_more( $post, $atts ) );
		$html = $this->replace_home_url( $html );
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	/**
	 * Test bw_format_list with a title
	 */
	function test_bw_format_list() {
		$this->switch_to_locale( 'en_GB' );
		$atts = array();
		$post = $this->dummy_post();
		$html = bw_ret( bw_format_list( $post, $atts ) );
		$html = $this->replace_home_url( $html );
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
}
	
