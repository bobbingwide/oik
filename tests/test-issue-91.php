<?php // (C) Copyright Bobbing Wide 2017

class Tests_issue_91 extends BW_UnitTestCase {

	/** 
	 * Issue #91 - Support PHP 7.2 
	 * 
	 */
	
	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 */
	function setUp(): void {
		parent::setUp();
	}
	
	/**
	 * This should not produce Warning: count(): Parameter must be...  under PHP 7.2 when $mydata is null
	 */
	function test_bw_update_post_meta() {
		oik_require( "includes/bw_metadata.php" );
		$post_id = 0;
		$field = "issue-91";
		$mydata = null;
		$result = bw_update_post_meta( $post_id, $field, $mydata );
		$this->assertTrue( true );
	}
		
}
