<?php // (C) Copyright Bobbing Wide 2017

class Tests_issue_65 extends BW_UnitTestCase {

	/**
	 * Issue #65 - Enhance bw_register_taxonomy to register_taxonomy_for_object_type

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 */
	function setUp() {
		parent::setUp();
	}
	
	
	/**
	 * bw_register_taxonomy() couldn't be used to register the same taxonomy to multiple post types.
	 * ...But now it can.

   * We may want to test bw_register_custom_tags() and bw_register_custom_category() as well
	 *
	 */
	function test_bw_register_taxonomy() {
		$registered = bw_query_taxonomy( "issue-65" );
		$this->assertFalse( $registered );
		
		bw_register_taxonomy( "issue-65", "post" );
		
		$registered = bw_query_taxonomy( "issue-65" );
		$this->assertTrue( $registered );
		
		// The taxonomy is not registered for the object type by bw_register_taxonomy(), though it is registered as a field.
		$type = bw_query_field_type( "issue-65" );
		$this->assertEquals( "taxonomy", $type );
		
		$registered = bw_field_registered_for_object_type( "issue-65", "post" );
		$this->assertFalse( $registered );
		
		bw_register_taxonomy( "issue-65", "page" );
		
		$registered = bw_query_taxonomy( "issue-65" );
		$this->assertTrue( $registered );
		
		$registered = bw_field_registered_for_object_type( "issue-65", "page" );
		$this->assertFalse( $registered );
		
		$this->assertTrue( unregister_taxonomy_for_object_type( 'issue-65', 'post' ) );
		$this->assertFalse( unregister_taxonomy_for_object_type( 'issue-65', 'post' ) );
		
		$this->assertTrue( unregister_taxonomy_for_object_type( 'issue-65', 'page' ) );
		$this->assertFalse( unregister_taxonomy_for_object_type( 'issue-65', 'page' ) );
		
	}

}

