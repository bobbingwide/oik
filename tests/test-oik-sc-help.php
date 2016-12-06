<?php // (C) Copyright Bobbing Wide 2016

/**
 * @package oik-sc-help
 * 
 * Tests for logic in includes/oik-sc-help.inc
 */
class Tests_oik_sc_help extends BW_UnitTestCase {

	function setUp() { 
		oik_require( "admin/oik-admin.inc" );	 
		oik_require( "includes/oik-sc-help.inc" ); 														
		$this->_url = oik_get_plugins_server();
	}

	/**
	 * Test for Issue #43
	 * 
	 * The link to a shortcode parameter should pass all of the comma separate values in the parameter name,
	 * not just the first one.
	 */
	function test_bw_form_sc_parm_help_issue_43() {
		$expected_output = '<a href="';
		$expected_output .= $this->_url;
		$expected_output .= '/oik_sc_param/audio-src,mp3,m4a,ogg,wav,wma-parameter"';
		$expected_output .= ' title="audio src,mp3,m4a,ogg,wav,wma parameter">src,mp3,m4a,ogg,wav,wma</a>';
    $link = bw_form_sc_parm_help( "src,mp3,m4a,ogg,wav,wma", "audio" );
		$this->assertEquals( $link, $expected_output );
	}

}
