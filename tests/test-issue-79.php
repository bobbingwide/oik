<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package oik
 * 
 * Test issue #79 - Accessibility ( a11y ) changes
 */
class Tests_issue_79 extends BW_UnitTestCase {

	function setUp(): void {
		parent::setUp();
	}
	
	/**
	 * retlink should not create a title= attribute unless text and title are defined an different
	 * 
	 * ie. retlink should only add the title= attribute if it's different from the text in the link
	 * 
	 * The data array consists of URL, text, title, expected
	 * We're always assuming 
	 */
	function test_retlink() {
	
		$array = array( array( "https://example.com", null, null, '<a href="https://example.com">https://example.com</a>' ) 
									, array( "https://example.com", "example.com", null, '<a href="https://example.com">example.com</a>' )
									, array( "https://example.com", null, "https://example.com", '<a href="https://example.com">https://example.com</a>' )
									, array( "https://example.com", "example.com", "example.com", '<a href="https://example.com">example.com</a>' )
									, array( "https://example.com", "example.com", "title", '<a href="https://example.com" title="title">example.com</a>' ) 
									);
		foreach ( $array as $key=> $data ) {
			$url = $data[0];
			$text = $data[1];
			$title = $data[2];
			$expected = $data[3];
			$html = retlink( null, $url, $text, $title  );
			$this->assertEquals( $expected, $html, $key );
		}
	}
}

