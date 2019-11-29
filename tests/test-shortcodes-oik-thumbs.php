<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-thumbs.php
 */
class Tests_shortcodes_oik_thumbs extends BW_UnitTestCase {

	function setUp(): void {
		parent::setUp();
		
		oik_require_lib( "oik-sc-help" );
		oik_require( "shortcodes/oik-thumbs.php" ); 	
		oik_require_lib( "oik_plugins" );
		update_option( "posts_per_page", 10 ); 											
	}
	
	function test_bw_thumbs__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = bw_thumbs__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_thumbs__syntax_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$array = bw_thumbs__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	/**
	 * Since it's not easy to verify and all we want to do at present is to check the i18n/10n of the text preceding
	 * - We run the example twice 
	 * - but throw away the output of the first call
	 * - Prior to running the second test we remove the shortcode. 
	 * - We also need to reset the cached expansion of the shortcode
	 */ 
	function test_bw_thumbs__example() {
		$this->switch_to_locale( "en_GB" );
		$html = bw_ret( bw_thumbs__example() );
		$this->remove_shortcode( "bw_thumbs" );
		bw_expand_shortcode();
		$html = bw_ret( bw_thumbs__example() );
		//$post_1 = $this->dummy_post( 1 );
		//$attachment_1 = $this->dummy_attachment( 0 );
		//$attachment_2 = $this->dummy_attachment( 0 );
		//$attachment_3 = $this->dummy_attachment( 0 );
		//$this->get_three_attachments();
		$html = $this->replace_home_url( $html );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	
	function test_bw_thumbs__example_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = bw_ret( bw_thumbs__example() );
		$this->remove_shortcode( "bw_thumbs" );
		$html = bw_ret( bw_thumbs__example() );
		//$post_1 = $this->dummy_post( 1 );
		//$attachment_1 = $this->dummy_attachment( 0 );
		//$attachment_2 = $this->dummy_attachment( 0 );
		//$attachment_3 = $this->dummy_attachment( 0 );
		//$this->get_three_attachments();
		$html = $this->replace_home_url( $html );
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
	
	function dummy_attachment( $parent ) {
		$args = array( 'post_type' => 'attachment'
								 , 'post_parent' => $parent
								 , 'post_content' => 'attachment content'
								 , 'file' => oik_path( '!.png' )
								 , 'post_title' => ' !'
								 );
		$id = self::factory()->attachment->create_upload_object( "!.jpg", 0 );
		$this->assertGreaterThan( 0, $id );
		$post = get_post( $id );
		print_r( $post );
		return $post;
	}
	
	function get_three_attachments() {
		$args = array( "post_type" => "attachment" 
		             , "numberposts" => 3
								 , "post_mime_type" => "image"
								 );
		$this->posts = bw_get_posts( $args );
		print_r( $this->posts );
		
		
	}
	
	function remove_shortcode( $shortcode ) {
		remove_shortcode( $shortcode );
	}

	
		
		

	
	
		
		
	
	
}
	
