<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-link.php
 */
class Tests_shortcodes_oik_link extends BW_UnitTestCase {

	function setUp() { 
		parent::setUp();
		
		oik_require( "includes/oik-sc-help.php" );
		oik_require( "shortcodes/oik-link.php" ); 
		oik_require_lib( "oik_plugins" );														
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
	
	
	/**
	 * Tests different links without fragments
	 * 
	 * This table describes our intention. The actual tests should match
	 * 
	 * bw_link 0th parameter      | Expected to produce              | Implemented?
	 * ----------------------     | -------------------              | --------
	 *                          	| empty link							       	 | Yes
	 * /          	            	| Link to $domain                  | Yes
	 * /somewhere                 | Link to $domain/somwehere        | Yes
	 * /somewhere/child           | Link to $domain/somewhere/child  | Yes
	 * example.com                | Link to example.com              | Yes
	 * example.com/path						| Link to example.com/path         | Yes
	 * http://example.com         | Link as given                    | Yes
	 * https://example.com        | Link as given                    | Yes
	 * 18356                      | Link to post 18356               | Yes
	 * 
	 * post_type                  | Link to the Post type archive | ?
	 * archive  | Link to any other archive? | ?
	 * s://p  | Multisite link s is site ID and p is post ID | No
	 * 
	 * Notes:
	 * - When we link to somewhere in the current domain we don't check that the permalink exists
	 * - We no longer support links to child pages
	 * - $domain may not include the scheme... as it's stripped in bw_get_domain
	 */
	function test_bw_link() { 
		bw_update_option( "domain", null );
		$domain = bw_get_domain();					
		$inout = array( "" => '<a class="bw_link"></a>'
									, "/" => '<a class="bw_link" href="http://' . $domain . '">/</a>'
									, "/somewhere" => '<a class="bw_link" href="http://' . $domain . '/somewhere">/somewhere</a>'
									, "/somewhere/child" => '<a class="bw_link" href="http://' . $domain . '/somewhere/child">/somewhere/child</a>'
									,	"example.com" 					=> '<a class="bw_link" href="http://example.com">example.com</a>'
									, "example.com/path"			=> '<a class="bw_link" href="http://example.com/path">example.com/path</a>'
									, "http://example.com" => '<a class="bw_link" href="http://example.com">http://example.com</a>'
									, "https://example.com" => '<a class="bw_link" href="https://example.com">https://example.com</a>'
									);
		foreach ( $inout as $in => $expected ) {
			$atts = array( $in );
			$html = bw_link( $atts );
			$this->assertEquals( $expected, $html );
		}	
	}
	
	/**
	 * Test different links with fragments
	 */
	function test_bw_link_fragment() { 
		bw_update_option( "domain", null );
		$domain = bw_get_domain();					
		$inout = array(	"#fragment" 						=> '<a class="bw_link" href="#fragment">fragment</a>' 
									,	"#frag ment" 						=> '<a class="bw_link" href="#frag ment">frag ment</a>' 
									,	"#frag-ment" 						=> '<a class="bw_link" href="#frag-ment">frag ment</a>' 
									,	"#frag_ment" 						=> '<a class="bw_link" href="#frag_ment">frag ment</a>' 
									, "/#fragment" 						=> '<a class="bw_link" href="http://' . $domain . '#fragment">/#fragment</a>'
									, "example.com#fragment" 	=> '<a class="bw_link" href="http://example.com#fragment">example.com#fragment</a>'
									, "example.com/path#fragment" 	=> '<a class="bw_link" href="http://example.com/path#fragment">example.com/path#fragment</a>'
									, "http://example.com/#fragment" => '<a class="bw_link" href="http://example.com/#fragment">http://example.com/#fragment</a>'
									, "https://example.com/#fragment" => '<a class="bw_link" href="https://example.com/#fragment">https://example.com/#fragment</a>'
									);
		foreach ( $inout as $in => $expected ) {
			$atts = array( $in );
			$html = bw_link( $atts );
			$this->assertEquals( $expected, $html );
		}	
	}
	
	/**
   * Tests links to a post by ID
	 * 
	 * - with or without text?
	 * - fragments not relevant
	 */ 
	function test_bw_link_id() {
		$siteurl = home_url();
		$parent = $this->dummy_post(); 				
		$inout = array( $parent->ID => '<a class="bw_link" href="' . $siteurl . '/post-title/">post title</a>'
									);
		foreach ( $inout as $in => $expected ) {
			$atts = array( $in );
			$html = bw_link( $atts );
			$this->assertEquals( $expected, $html );
		}
	}
	
		
	function dummy_post_n( $n, $parent=0 ) {
		$args = array( 'post_type' => 'page', 'post_title' => "post title $n", 'post_excerpt' => 'Excerpt. No post ID', 'post_parent' => $parent  );
		$id = self::factory()->post->create( $args );
		$post = get_post( $id );
		return $post;
	}
	
		
	
	
	
}
	
