<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-attachments.php
 */
class Tests_shortcodes_oik_attachments extends BW_UnitTestCase {

	function setUp(): void {
		parent::setUp();
		oik_require( "shortcodes/oik-attachments.php" ); 	
		oik_require_lib( "oik-sc-help" );	 
		update_option( "posts_per_page", 10 ); 											
	}
	
	function test_bw_link_attachment() {
		$this->switch_to_locale( "en_GB" );
		$post = $this->dummy_attachment();
		$atts = array( 'title' => "Translated title" );
		$html = bw_ret( bw_link_attachment( $post, $atts ) );
		$html = $this->replace_home_url( $html );
		//$html = $this->replace_upload_url( $html );
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
		$attachment_meta = $this->attachment_meta();
		add_post_meta( $id, "_wp_attachment_metadata", $attachment_meta, true );
		return $post;
	}

	function attachment_meta() {
		$attachment_meta=[
			'width' =>'256'
			,
			'height'=>'256'
			,
			'file'  =>'attached.file'
			// 'sizes'
		];

		return $attachment_meta;
	}
		/*

			unserialize(
'a:6:{s:5:"width";s:3:"256";s:6:"height";s:3:"256";
s:14:"hwstring_small";s:22:"height='96' width='96'";
s:4:"file";s:22:"2011/08/oik-plugin.jpg";
s:5:"sizes";a:1:{s:9:"thumbnail";a:3:{s:4:"file";s:22:"oik-plugin-125x125.jpg";s:5:"width";s:3:"125";s:6:"height";s:3:"125";}}
s:10:"image_meta";a:10:{s:8:"aperture";s:1:"0";s:6:"credit";s:0:"";s:6:"camera";s:0:"";s:7:"caption";s:0:"";s:17:"created_timestamp";s:1:"0";s:9:"copyright";s:0:"";s:12:"focal_length";s:1:"0";s:3:"iso";s:1:"0";s:13:"shutter_speed";s:1:"0";s:5:"title";s:0:"";}}';
	*/
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
		/*
		-    2 => '<img class="bw_portfolio" src="https://qw/src/post-title/" title="post title" alt="post title"  />'
		+    2 => '<img class="full wp-image-14033" src="https://qw/src/wp-content/uploads/attached.file" width="256" height="256"  loading="lazy" />'
		*/

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
		$html = $this->replace_post_id( $html, $post, 'wp-image-' );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_sc_captions() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = _sc_captions();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_sc_captions_bb_BB() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "bb_BB" );
		$array = _sc_captions();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_attachments__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = bw_attachments__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_attachments__syntax_bb_BB() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "bb_BB" );
		$array = bw_attachments__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
  }
	
	
	function test_bw_pdf__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = bw_pdf__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_pdf__syntax_bb_BB() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "bb_BB" );
		$array = bw_pdf__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_portfolio__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = bw_portfolio__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_portfolio__syntax_bb_BB() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "bb_BB" );
		$array = bw_portfolio__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_images__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = bw_images__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_images__syntax_bb_BB() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "bb_BB" );
		$array = bw_images__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
		
		
	

	
	
	
	
}
	
