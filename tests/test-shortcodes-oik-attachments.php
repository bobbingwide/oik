<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-attachments.php
 */
class Tests_shortcodes_oik_attachments extends BW_UnitTestCase {

	function setUp() { 
		parent::setUp();
		oik_require( "shortcodes/oik-attachments.php" ); 														
	}
	
	function test_bw_link_attachment() {
		$this->switch_to_locale( "en_GB" );
		$post = $this->dummy_attachment();
		$atts = array( 'title' => "Translated title" );
		$html = bw_ret( bw_link_attachment( $post, $atts ) );
		$html = $this->replace_home_url( $html );
		$html = $this->replace_post_id( $html, $post, 'id="link-' );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	/** 
	 * Create a dummy attachment, which is actually a page
	 * but at least has  post meta of "_wp_attached_file" 
	 */
	function dummy_attachment() {
		$args = array( 'post_type' => 'page', 'post_title' => 'post title', 'post_excerpt' => "caption", 'post_content' => "description" );
		$id = self::factory()->post->create( $args );
		$post = get_post( $id );
		add_post_meta( $id, "_wp_attached_file", "attached.file", true );
		return $post;
	}
	
	/**
	 * Note: We're ok with the fact that wp_get_attachment_link will return "Missing attachment" since $post is not really an attachment
	 */
	function test_bw_format_attachment_captions_y() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$post = $this->dummy_attachment();
		$atts = array( 'captions' => 'y' );
		$html = bw_ret( bw_format_attachment( $post, $atts ) );
		$html = $this->replace_home_url( $html );
		$html = $this->replace_post_id( $html, $post, 'id="link-' );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	/**
	 * - fiddle $post->guid?
	 */
	function test_bw_format_matched_link() {
		$this->switch_to_locale( "en_GB" );
		$post = $this->dummy_attachment();
		$atts = array();
		$html = bw_ret( bw_format_matched_link( $post, $post, $atts ) );
		$html = $this->replace_home_url( $html );
		$html = $this->replace_post_id( $html, $post, 'id="link-' );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	

	
	
	
	
}
	
