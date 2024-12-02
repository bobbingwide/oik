<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-accordion.php
 */
class Tests_shortcodes_oik_accordion extends BW_UnitTestCase {

	function setUp(): void {
		parent::setUp();
		
		oik_require_lib( "oik-sc-help" );
		oik_require( "shortcodes/oik-accordion.php" ); 	
		oik_require_lib( "oik_plugins" );
		update_option( "posts_per_page", 10 ); 											
	}
	
	/**
	 * Test the improvements to bw_accordion_id()
	 * to allow reproducible tests run in any order
	 */
	function test_bw_accordion_id() {
		$accordion_id = bw_accordion_id( null );
		$this->assertEquals( "bw_accordion-0", $accordion_id );
		$accordion_id = bw_accordion_id();
		$this->assertEquals( "bw_accordion-1", $accordion_id );
		$accordion_id = bw_accordion_id( false );
		$this->assertEquals( "bw_accordion-1", $accordion_id );
		$accordion_id = bw_accordion_id( "any" );
		$this->assertEquals( "bw_accordion-2", $accordion_id );
		$accordion_id = bw_accordion_id( null );
		$this->assertEquals( "bw_accordion-0", $accordion_id );
	}
	
	function test_bw_accordion__syntax() {
		$this->switch_to_locale( "en_GB" );
		$array = bw_accordion__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_accordion__syntax_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$array = bw_accordion__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	/**
   * This tests the HTML. not the jQuery
	 */
	function test_bw_accordion__example() {
		$this->switch_to_locale( "en_GB" );
		$post_1 = $this->dummy_post( 1 );
		$post_2 = $this->dummy_post( 2 );
		$accordion_id = bw_accordion_id( null );
		$html = bw_ret( bw_accordion__example() );
		$html = $this->replace_post_id( $html, $post_1 );
		$html = $this->replace_post_id( $html, $post_1, 'href="#bw_accordion-1-' );
		$html = $this->replace_post_id( $html, $post_1, 'href="bw_accordion-1-' );
		$html = $this->replace_post_id( $html, $post_1, 'id="bw_accordion-1-' );
		$html = $this->replace_post_id( $html, $post_2 );
		$html = $this->replace_post_id( $html, $post_2, 'href="#bw_accordion-1-' );
		$html = $this->replace_post_id( $html, $post_2, 'href="bw_accordion-1-' );
		$html = $this->replace_post_id( $html, $post_2, 'id="bw_accordion-1-' );
		$html = $this->replace_home_url( $html );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_accordion__example_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$post_1 = $this->dummy_post( 1 );
		$post_2 = $this->dummy_post( 2 );
		$accordion_id = bw_accordion_id( null );
		$html = bw_ret( bw_accordion__example() );
		$html = $this->replace_post_id( $html, $post_1 );
		$html = $this->replace_post_id( $html, $post_1, 'href="#bw_accordion-1-' );
		$html = $this->replace_post_id( $html, $post_1, 'href="bw_accordion-1-' );
		$html = $this->replace_post_id( $html, $post_1, 'id="bw_accordion-1-' );
		$html = $this->replace_post_id( $html, $post_2 );
		$html = $this->replace_post_id( $html, $post_2, 'href="#bw_accordion-1-' );
		$html = $this->replace_post_id( $html, $post_2, 'href="bw_accordion-1-' );
		$html = $this->replace_post_id( $html, $post_2, 'id="bw_accordion-1-' );
		$html = $this->replace_home_url( $html );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_accordion__snippet() {
		$this->switch_to_locale( "en_GB" );
		$html = bw_ret( bw_accordion__snippet() );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_accordion__snippet_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = bw_ret( bw_accordion__snippet() );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function dummy_post( $n ) {
		$args = array( 'post_type' => 'page', 'post_title' => "post title $n", 'post_excerpt' => 'Excerpt. No post ID' );
		$id = self::factory()->post->create( $args );
		$post = get_post( $id );
		return $post;
	}

}
	
