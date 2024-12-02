<?php // (C) Copyright Bobbing Wide 2017

/** 
 * Unit tests for the includes/bw_formatter.php file
 */

class Tests_includes_bw_formatter extends BW_UnitTestCase {

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 * - The oik plugin must be activated!
	 */
	function setUp(): void {
		parent::setUp();
		oik_require( "includes/bw_formatter.php" );
	}
	
	/**
	 * Tests bw_field_function_edit
	 *
	 * User needs to be logged in to get any output
	 */ 
	function test_bw_field_function_edit() {
		wp_set_current_user( 1 );
		$this->switch_to_locale( 'en_GB' );
		$post = $this->dummy_post();
		$atts = null;
		$html = bw_ret( bw_field_function_edit( $post, $atts, null ) );
		$html = $this->replace_admin_url( $html );
		$html = $this->replace_post_id( $html, $post );
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	/**
	 * Tests bw_field_function_edit
	 *
	 * User needs to be logged in to get any output
	 * 
	 * Note: Currently [Edit] is [Edit] in bb_BB
	 */ 
	function test_bw_field_function_edit_bb_BB() {
		wp_set_current_user( 1 );
		$this->switch_to_locale( 'bb_BB' );
		$post = $this->dummy_post();
		$atts = null;
		$html = bw_ret( bw_field_function_edit( $post, $atts, null ) );
		$html = $this->replace_admin_url( $html );
		$html = $this->replace_post_id( $html, $post );
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	function dummy_post() {
		$args = array( 'post_type' => 'page', 'post_title' => 'post title' );
		$id = self::factory()->post->create( $args );
		$post = get_post( $id );
		return $post;
	}
	
	function dummy_post_no_title() {
		$args = array( 'post_type' => 'page', 'post_title' => null, 'post_status' => 'draft' );
		$id = self::factory()->post->create( $args );
		$post = get_post( $id );
		print_r( $post );
		$this->assertEmpty( $post->title );
		return $post;
	}

	function unset_global_post() {
		global $post;
		//print_r( $post );
		$post = null;
		//print_r( $post );

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
	
	/**
	 * Test default title where none is set for the post.
	 */
	function test_bw_field_function_title() {
		wp_set_current_user( 1 );
		$this->switch_to_locale( 'en_GB' );
    $post = $this->dummy_post();
        $this->unset_global_post();

		$post->ID = 0;
		//$post->title = null;
		$atts = array();

		bw_field_function_title( $post,	$atts, null );
		$html = bw_ret();
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	/**
	 * Test default title where none is set for the post
	 */
	function test_bw_field_function_title_bb_BB() {
		wp_set_current_user( 1 );
		$this->switch_to_locale( 'bb_BB' );
    $post = $this->dummy_post();
		$this->unset_global_post();

		$post->ID = 0;
		$atts = array();
		bw_field_function_title( $post,	$atts, null );
		$html = bw_ret();
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	/**
	 * This test will return a null list of categories since "Category" is not associated to the "page" post type
	 */
	function test_bw_field_function_categories() {
		wp_set_current_user( 1 );
		$this->switch_to_locale( 'en_GB' );
    $post = $this->dummy_post();
		$atts = array();
		$f = null;
		bw_field_function_categories( $post, $atts, $f );
		$html = bw_ret();
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	/**
	 * This test will return a null list of categories since "Category" is not associated to the "page" post type
	 */
	function test_bw_field_function_categories_bb_BB() {
		wp_set_current_user( 1 );
		$this->switch_to_locale( 'bb_BB' );
    $post = $this->dummy_post();
		$atts = array();
		$f = null;
		bw_field_function_categories( $post, $atts, $f );
		$html = bw_ret();
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	
	/**
	 * This test will return a count of 0 Comments 
	 */
	function test_bw_field_function_comments() {
		wp_set_current_user( 1 );
		$this->switch_to_locale( 'en_GB' );
    $post = $this->dummy_post();
		$atts = array();
		$f = null;
		bw_field_function_comments( $post, $atts, $f );
		$html = bw_ret();
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	/**
	 * This test will return a count of 0 Comments 
	 */
	function test_bw_field_function_comments_bb_BB() {
		wp_set_current_user( 1 );
		$this->switch_to_locale( 'bb_BB' );
    $post = $this->dummy_post();
		$atts = array();
		$f = null;
		bw_field_function_comments( $post, $atts, $f );
		$html = bw_ret();
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	/**
	 * This test will return a null list of tags since "Tags" is not associated to the "page" post type
	 */
	function test_bw_field_function_tags() {
		wp_set_current_user( 1 );
		$this->switch_to_locale( 'en_GB' );
    $post = $this->dummy_post();
		$atts = array();
		$f = null;
		bw_field_function_tags( $post, $atts, $f );
		$html = bw_ret();
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	/**
	 * This test will return a null list of tags since "Tags" is not associated to the "page" post type
	 */
	function test_bw_field_function_tags_bb_BB() {
		wp_set_current_user( 1 );
		$this->switch_to_locale( 'bb_BB' );
    $post = $this->dummy_post();
		$atts = array();
		$f = null;
		bw_field_function_tags( $post, $atts, $f );
		$html = bw_ret();
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	/**
	 * We need to alter the author's URL and nicename in the output for environment independence.
	 */
	function test_bw_field_function_author() {
		wp_set_current_user( 1 );
		$this->switch_to_locale( 'en_GB' );
    $post = $this->dummy_post();
		$atts = array();
		$f = null;
		bw_field_function_author( $post, $atts, $f );
		$html = bw_ret();
		$author_posts_url = get_author_posts_url( $post->post_author );
		$author_name =  get_the_author_meta( "nicename", $post->post_author );
		$html = str_replace( $author_posts_url, "https://qw/src/author/author", $html );
		$html = str_replace( $author_name, "author name", $html );
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	/**
	 * We need to alter the author's URL and nicename in the output for environment independence.
	 * This is an iffy test since bb_BB for By is By
	 */
	function test_bw_field_function_author_bb_BB() {
		wp_set_current_user( 1 );
		$this->switch_to_locale( 'bb_BB' );
    $post = $this->dummy_post();
		$atts = array();
		$f = null;
		bw_field_function_author( $post, $atts, $f );
		$html = bw_ret();
		$author_posts_url = get_author_posts_url( $post->post_author );
		$author_name =  get_the_author_meta( "nicename", $post->post_author );
		$html = str_replace( $author_posts_url, "https://qw/src/author/author", $html );
		$html = str_replace( $author_name, "author name", $html );
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	
	/**
	 * We need to alter the date for environment and date independence.
	 */
	function test_bw_field_function_date() {
		wp_set_current_user( 1 );
		$this->switch_to_locale( 'en_GB' );
    $post = $this->dummy_post();
		$atts = array();
		$f = null;
		bw_field_function_date( $post, $atts, $f );
		$html = bw_ret();
    $date_format = get_option('date_format');
    $date = get_post_time( $date_format, false, $post->ID, false );
		$html = str_replace( $date, "ccyy/mm/dd", $html );
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}
	
	/**
	 * We need to alter the date for environment and date independence.
	 */
	function test_bw_field_function_date_bb_BB() {
		wp_set_current_user( 1 );
		$this->switch_to_locale( 'bb_BB' );
    $post = $this->dummy_post();
		$atts = array();
		$f = null;
		bw_field_function_date( $post, $atts, $f );
		$html = bw_ret();
    $date_format = get_option('date_format');
    $date = get_post_time( $date_format, false, $post->ID, false );
		$html = str_replace( $date, "ccyy/mm/dd", $html );
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( 'en_GB' );
	}


	

}
	
