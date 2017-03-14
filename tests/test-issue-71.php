<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package oik
 * 
 * Test issue #71
 */
class Tests_issue_71 extends BW_UnitTestCase {

	//function setUp() {
		///parent::setUp();
		//oik_require( "includes/oik-filters.inc" );
	//}
	
	
	function test_shortcodes_expanded_in_the_excerpt_embed() {
		$content = "[wp]";
		$expanded = apply_filters( "the_excerpt_embed", $content );
		$expected = '<span class="wordpress"><span class="bw_word">Word</span><span class="bw_press">Press</span></span>';
		$expected .= "\n";
		$this->assertEquals( $expected, $expanded );
	}
}
