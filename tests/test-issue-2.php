<?php // (C) Copyright Bobbing Wide 2017

class Tests_issue_2 extends BW_UnitTestCase {

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 */
	function setUp(): void {
		parent::setUp();
	}
	
	function test_bw_query_field_type() { 
		bw_register_field( "field", "text", "Field" );
		$field_type = bw_query_field_type( "field" );
		$this->assertEquals( "text", $field_type );
	}
	
	function test_bw_register_field_for_object_type() { 
		bw_register_field( "field", "text", "Field" );
		$field_type = bw_query_field_type( "field" );
		$this->assertEquals( "text", $field_type );
		bw_register_field_for_object_type( "field", "issue-2" ); 
		global $bw_mapping;
		$this->assertEquals( "issue-2", $bw_mapping['type']['field']['issue-2'] );
		$this->assertEquals( "field", $bw_mapping['field']['issue-2']['field'] );
	}
	
	function test_bw_field_registered_for_object_type() {
		bw_register_field( "field", "text", "Field" );
		bw_register_field_for_object_type( "field", "issue-2" ); 
		$registered = bw_field_registered_for_object_type( "field", "issue-2" );
		$this->assertTrue( $registered );
	}
	
	function test_bw_determine_post_type_from_hook() {
		$post_type = bw_determine_post_type_from_hook( "manage_issue-2_posts_custom_column" );
		$this->assertEquals( "issue-2", $post_type );
	}
	
	/**
	 * Tests that bw_custom_column_post_meta finds the values created in dummy_post
	 */
	function test_bw_custom_column_post_meta() {
		$this->test_bw_register_field_for_object_type();
		$id = $this->dummy_post();
		ob_start(); 
		echo "fred";
		bw_custom_column_post_meta( "field", $id );
		echo "derf";
		$html = ob_get_contents();
		ob_end_clean();
		$this->assertEquals( "fredderf", $html );
		$html2 = bw_ret();
		$this->assertNotNull( $html2 );
		$this->assertEquals( "felt value", $html2 ); 
	}
	
	/**
	 * Tests that bw_custom_column finds the values created in dummy_post
	 */
	function test_bw_custom_column() {
		$this->test_bw_register_field_for_object_type();
		$id = $this->dummy_post();
		ob_start(); 
		echo "fred";
		bw_custom_column( "field", $id );
		echo "derf";
		$html = ob_get_contents();
		ob_end_clean();
		$this->assertEquals( "fredderf", $html );
		$html2 = bw_ret();
		$this->assertNotNull( $html2 );
		$this->assertEquals( "felt value", $html2 ); 
	}
	
	/**
	 * For this test to work we have to fiddle with the current_filter()
	 * That's all a bit daft, but we'll do it anyway.
	 */
	function test_bw_custom_column_admin() {
		global $wp_current_filter;
		$wp_current_filter[] = "manage_issue-2_posts_custom_column";
		$this->test_bw_register_field_for_object_type();
		$id = $this->dummy_post();
		ob_start(); 
		echo "fred";
		bw_custom_column_admin( "field", $id );
		echo "derf";
		$html = ob_get_contents();
		ob_end_clean();
		array_pop( $wp_current_filter );
		$this->assertEquals( "fredfelt valuederf", $html );
		$html2 = bw_ret();
		$this->assertNull( $html2 );
	}
		
	/**
	 * Here we should produce some output - displaying the field value for "field" for the selected post ID
	 *
	 */
	function test_bw_custom_column_admin_field_for_post_type() {
		bw_register_field( "field", "text", "Field" );
		bw_register_field_for_object_type( "field", "issue-2" ); 
		$id = $this->dummy_post();
		add_action( "manage_issue-2_posts_custom_column", "bw_custom_column_admin", 10, 2 );
		ob_start(); 
		do_action( "manage_issue-2_posts_custom_column", "field", $id );  
		$html = ob_get_contents();
		ob_end_clean();
		$this->assertNotNull( $html );
		$this->assertEquals( "felt value", $html );
		remove_action( "manage_issue-2_posts_custom_column", "bw_custom_column_admin", 10 );
	}
	
	/**
	 * bw_custom_column_admin is an action hook which should be invoked by an action with generic name "manage_${post_type}_posts_custom_column"
	 * 
	 * It should not call bw_custom_column if 
	 * 1. It's not the right filter - so we can't determine the $post_type 
	 * 2. The field is not registered for the ${post_type}
	 * 
	 * i.e. It should only produce output when we can determine the post type from the current filter
	 * and the field was registered for this post type.
	 * 
	 * Can we simulate the current filter or just do_action();
	 */ 
	function test_bw_custom_column_admin_field_not_for_post_type() {
		bw_register_field( "field", "text", "Field" );
		bw_register_field( "wrong-field", "text", "Wrong field" );
		bw_register_field_for_object_type( "field", "issue-2" ); 
		$id = $this->dummy_post();
		add_action( "manage_issue-2_posts_custom_column", "bw_custom_column_admin", 10, 2 );
		ob_start(); 
		do_action( "manage_issue-2_posts_custom_column", "wrong-field", $id );  
		$html = ob_get_contents();
		ob_end_clean();
		$this->assertEquals( '', $html );
		remove_action( "manage_issue-2_posts_custom_column", "bw_custom_column_admin", 10 );
	}
	
	function dummy_post() {
		$args = array( 'post_type' => 'page', 'post_title' => 'post title', 'post_excerpt' => 'Excerpt. No post ID' );
		$id = self::factory()->post->create( $args );
		add_post_meta( $id, "field", "felt value", true );
		add_post_meta( $id, "wrong-field", "wrong value should not be displayed", true );
		return $id;
	}

}

