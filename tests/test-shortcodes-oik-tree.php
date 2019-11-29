<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-tree.php
 */
class Tests_shortcodes_oik_tree extends BW_UnitTestCase {

	function setUp(): void {
		parent::setUp();
		
		oik_require_lib( "oik-sc-help" );
		oik_require( "shortcodes/oik-tree.php" ); 	
		oik_require_lib( "oik_plugins" );
		update_option( "posts_per_page", 10 ); 											
	}
	
	function test_bw_tree__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = bw_tree__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_tree__syntax_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$array = bw_tree__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	/**
	 * Create a tree of pages to test bw_tree, bw_tree_func and bw_format_tree
	 
	 */ 
	function test_bw_tree() {
		$this->switch_to_locale( "en_GB" );
		$parent = $this->dummy_post( 1 );
		$child = $this->dummy_post( 2, $parent->ID );
		$grandchild = $this->dummy_post( 3, $child->ID );
		$atts = array( 'post_type' => 'page', 'post_parent' => $parent->ID );
		$html = bw_tree( $atts );
		$html = $this->replace_home_url( $html );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_tree_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$parent = $this->dummy_post( 1 );
		$child = $this->dummy_post( 2, $parent->ID );
		$grandchild = $this->dummy_post( 3, $child->ID );
		$atts = array( 'post_type' => 'page', 'post_parent' => $parent->ID );
		$html = bw_tree( $atts );
		$html = $this->replace_home_url( $html );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	
	
	function dummy_post( $n, $parent=0 ) {
		$args = array( 'post_type' => 'page', 'post_title' => "post title $n", 'post_excerpt' => 'Excerpt. No post ID', 'post_parent' => $parent  );
		$id = self::factory()->post->create( $args );
		$post = get_post( $id );
		return $post;
	}
	
	
}
	
