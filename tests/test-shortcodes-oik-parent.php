<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-parent.php
 */
class Tests_shortcodes_oik_parent extends BW_UnitTestCase {

	function setUp(): void {
		parent::setUp();
		oik_require_lib( "oik-sc-help" );
		oik_require( "shortcodes/oik-parent.php" ); 														
	}
	
	function test_bw_parent__help() {
		$this->switch_to_locale( "en_GB" );
		$html = bw_parent__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_parent__help_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = bw_parent__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_parent__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = bw_parent__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_parent__syntax_bb_BB() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "bb_BB" );
		$array = bw_parent__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_parent() {
		$this->switch_to_locale( "en_GB" );
		$child = $this->dummy_child();
		$atts = array( "id" => $child->ID );
		$html = bw_parent( $atts );
		$html = $this->replace_post_id( $html, $this->parent, 'id="id-' );
		$html = $this->replace_home_url( $html );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_parent_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$child = $this->dummy_child();
		$atts = array( "id" => $child->ID );
		$html = bw_parent( $atts );
		$html = $this->replace_post_id( $html, $this->parent, 'id="id-' );
		$html = $this->replace_home_url( $html );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function dummy_child() {
		$args = array( 'post_type' => 'page', 'post_title' => 'post title', 'post_excerpt' => 'Excerpt. No post ID' );
		$parent = self::factory()->post->create( $args );
		$this->parent = get_post( $parent );
		$args['post_parent'] = $parent;
		$child = self::factory()->post->create( $args );
		$post = get_post( $child );
		return $post;
	}
	
	/** 
	 * Tests the path where the link is numeric
	 * We use 0 as the ID so that the post doesn't exist
	 */
	function test_bw_post_link_numeric() {
		$this->switch_to_locale( "en_GB" );
		$html = bw_ret( bw_post_link( 0 ) );
		$html = $this->replace_home_url( $html );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	} 
	
	/** 
	 * Tests the path where the link is numeric
	 * We use 0 as the ID so that the post doesn't exist
	 */
	function test_bw_post_link_numeric_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = bw_ret( bw_post_link( 0 ) );
		$html = $this->replace_home_url( $html );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	} 
	
	
	
}
	
