<?php // (C) Copyright Bobbing Wide 2017

class Tests_issue_7 extends BW_UnitTestCase {

	/** 
	 * Issue #7 - oik_box() produces messages when $id=null and $callback is a method
	 * 
	 */
	
	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 */
	function setUp() {
		parent::setUp();
	}
	
	function tests_oik_box_id_null_callback_function() {
		oik_require( "admin/oik-admin.php" );
		BW_::oik_box( null, null, "Issue-7", "oik_callback" );
		$html = bw_ret();
		$this->assertNotNull( $html );
	}
	
	
	/**
	 * 
	 */
	function tests_oik_box_id_set_callback_method() {
		oik_require( "admin/oik-admin.php" );
		BW_::oik_box( null, "oik_callback", "Issue-7", array( $this, "oik_callback" ) );
		$html = bw_ret();
		$this->assertNotNull( $html );
	}
	
	/**
	 * Until fixed this test will fail
			This test printed output:
			Notice: Array to string conversion in C:\apache\htdocs\wordpress\wp-content\plugins\oik-bwtrace\libs\bobbfunc.php on line 289
	 */
	function tests_oik_box_id_null_callback_method() {
		oik_require( "admin/oik-admin.php" );
		BW_::oik_box( null, null, "Issue-7", array( $this, "oik_callback" ) );
		$html = bw_ret();
		$this->assertNotNull( $html );
	}
	
	function oik_callback() {
		BW_::p( "This method intentionally blank" );
	}

}
