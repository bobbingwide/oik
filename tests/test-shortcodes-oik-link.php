<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-link.php
 */
class Tests_shortcodes_oik_link extends BW_UnitTestCase {

	function setUp() { 
		parent::setUp();
		
		oik_require( "includes/oik-sc-help.inc" );
		oik_require( "shortcodes/oik-link.php" ); 														
	}
	
	function test_bw_link__help() {
		$this->switch_to_locale( "en_GB" );
		$html = bw_link__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_link__help_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = bw_link__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_link__syntax() {
		$this->switch_to_locale( "en_GB" );
		$array = bw_link__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_link__syntax_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$array = bw_link__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_link__example() {
		$post = $this->dummy_post();
		$id = _bw_get_an_id();
		$this->assertEquals( $post->ID, $id );
		$this->switch_to_locale( "en_GB" );
		$html = bw_ret( bw_link__example() );
		$html = str_replace( $id, "42", $html );
		$html = $this->replace_home_url( $html );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_link__example_bb_BB() {
		$post = $this->dummy_post();
		$id = _bw_get_an_id();
		$this->assertEquals( $post->ID, $id );
		$this->switch_to_locale( "bb_BB" );
		$html = bw_ret( bw_link__example() );
		$html = str_replace( $id, "42", $html );
		$html = $this->replace_home_url( $html );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	
	function dummy_post() {
		$args = array( 'post_type' => 'page', 'post_title' => 'post title', 'post_excerpt' => 'Excerpt. No post ID' );
		$id = self::factory()->post->create( $args );
		$post = get_post( $id );
		return $post;
	}
	
	
}
	
