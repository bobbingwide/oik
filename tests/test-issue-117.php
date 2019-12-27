<?php // (C) Copyright Bobbing Wide 2019

class Tests_issue_117 extends BW_UnitTestCase {

	/** 
	 * Issue #117 - tests bw_remote_get passing $asJSON=false
	 *
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
	 * Cater for the file line endings or CRLF or just LF
	 * This test expects readme.html to be part of the WordPress installation.
	 * It might fail if it's a wp-a2z version; there are versions which do not have this file.
	 */
	function test_bw_remote_get_asJSON_false() {
		$url = site_url( 'readme.html');
		//echo $url;
		oik_require_lib( 'class-oik-remote');
		$contents = oik_remote::bw_remote_get( $url, false );
		//echo $contents;
		$this->assertStringStartsWith( '<!DOCTYPE html', $contents );
		//$contents = str_replace( "\r\n", "\n", $contents);
		$contents = str_replace( "html>\r", "html>", $contents );
		$this->assertStringEndsWith( "</html>\n", $contents );
	}

}
