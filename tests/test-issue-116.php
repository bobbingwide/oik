<?php // (C) Copyright Bobbing Wide 2019

class Tests_issue_116 extends BW_UnitTestCase {

	/** 
	 * Issue #124 - tests for [bw_follow_me] comma separated network parameter
	 *
	 *
	 */
	
	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 */
	function setUp() {
		parent::setUp();
		oik_require( "shortcodes/oik-contact-form.php" );
	}
	
	/**
	 *
	 */
	function test_bw_basic_spam_check() {
		$fields = [ 'comment_content' => 'This is considered spam just because of the hTTP'];
		$send = bw_basic_spam_check( $fields );
		$this->assertFalse( $send );
		$fields = ['comment_content' => 'This is not considered to be spam. No h followed immediately by ttp'];
		$send = bw_basic_spam_check( $fields );
		$this->assertTrue( $send );
	}

}
