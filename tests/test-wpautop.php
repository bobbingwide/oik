<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests whether or not wpautop is still behaving badly
 */
class Tests_wpautop extends BW_UnitTestCase {

	function setUp(): void {
		parent::setUp();
	}
	
	/**
	 * Test for TRAC 42748 - wpautop adds an extra <p>
	 * 
	 * @link https://core.trac.wordpress.org/ticket/42748 
	 * 
	 * WordPress core can take what appears to be correct HTML and change it unexpectedly. 	 
	 * e.g. 
	 * - adding p tags before spans
	 * - adding an ending new line character \n
	 * 
	 * In this example I've added new lines to the input in the same way that wpautop would add them. 
	 * The main problem is the insertion of the unwanted p tag. 
	 *
	 * When this test fails it suggests wpautop() has been fixed.
	 */
	function test_wpautop_adding_unwanted_p() {
		$input      = "<div>\n<div></div>\n" .         "<span></span></div>\n";
		$unexpected = "<div>\n<div></div>\n" . "<p>" . "<span></span></div>\n";
		$actual = wpautop( $input );
		$this->assertEquals( $unexpected, $actual );
	}
	
	/**
	 * Test for TRAC 39377 - wpautop adds an extra </p>
	 * 
	 * For when the span comes before the div! 
	 * 
	 * When this test fails it suggests wpautop() has been fixed.
	 */
	function test_wpautop_adding_unwanted_endp() {
		$input      = "<div><span></span>" .          "\n<div></div>\n</div>\n";
		$unexpected = "<div><span></span>" . "</p>" . "\n<div></div>\n</div>\n";
		$actual = wpautop( $input );
		$this->assertEquals( $unexpected, $actual );
	}
	
	/** 
	 * Test for TRAC 2691 - wpautop adds an extra p and end p around HTML comments
	 * 
	 * We do expect there to be p and end p tags around uncommented content but not before the comment.
	 */
	function test_wpautop_adding_unwanted_pendp() {
		$input      = "<!-- HTML Comment -->\n";
		$unexpected = "<p><!-- HTML Comment --></p>\n";
		$actual = wpautop( $input );
		$this->assertEquals( $unexpected, $actual );
		
		$input      = "<!-- HTML Comment -->Content\n";
		$unexpected = "<p><!-- HTML Comment -->Content</p>\n";
		$actual = wpautop( $input );
		$this->assertEquals( $unexpected, $actual );
		
	}
	
	 /**
	  * Test wpautop when just processing content and new lines
		* 
		* Whatever it does here the same should happen when HTML comments are dotted willy nilly between the tokens.
		* 
		* Note: This test suggests the current code is correctly ignoring the HTML comments when there's just content and new lines.
		*/
	function test_wpautop_just_content_and_newlines() {
		$input_tokens =    array( null,  "Content", null,     "\n" , "More content" , "\n\n" ,     "After 2 new lines",  null,    null );
		$expected_tokens = array( "<p>", "Content", "<br />", "\n" , "More content" , "</p>\n<p>", "After 2 new lines" , "</p>",  "\n" );
		
		$input = implode( '',$input_tokens );
		$expected = implode( '', $expected_tokens );
		$actual = wpautop( $input );
		$this->assertEquals( $expected, $actual );
		
		for ( $index=0; $index < count( $input_tokens ); $index++ ) {
			if ( $input_tokens[ $index ] !== null ) {
				$input_tokens[ $index] .= "<!-- HTML Comment $index -->";
				$expected_tokens[ $index ] .= "<!-- HTML Comment $index -->";
			}
			$input = implode( '', $input_tokens );
			$expected = implode( '', $expected_tokens );
			$actual = wpautop( $input );
			$input = str_replace( "\n", "?", $input );
			$actual = str_replace( "\n", "?", $actual );
			$expected = str_replace( "\n", "?", $actual );
			/*
			echo $index . PHP_EOL;
			echo $input . PHP_EOL;
			echo $expected . PHP_EOL;
			echo $actual . PHP_EOL;
			*/
			$this->assertEquals( $expected, $actual );
			
		}
	}
		
		
	
	
	
	
	/**
	 * WordPress core can take what appears to be correct HTML and change it unexpectedly.
	 * 
	 * e.g. 
	 * - adding p tags before spans
	 * - adding an ending new line character \n
	 */
	
	function test_wpautop_adding_unwanted_stuff() {
	
		$expected_array = array( "<div><span></span></div>"
													 , "<div><span class=\"country-name\"></span></div>" 
													 , "<div><div></div><span></span></div>"
											  	 , $this->failing_string()
													 );
		foreach ( $expected_array as $key => $expected ) {
			$actual = wpautop( $expected );
			$this->assertNotEquals( $expected, $actual );
			
			//oik_require( "includes/formatting-later.php", "oik-css" );
			//$texturized = wptexturize_blocks( $expected );
			//$this->assertEquals( $expected, $texturized );
			
			//$filtered = apply_filters( "the_content", $expected );
			//$this->assertNotEquals( $expected, $filtered );
		}
		
	}
	
/*


: 0   bw_trace_parms;9 bw_trace_attached_hooks;9
: 1   oikp_the_content;1 oiksc_the_content;1 oiksp_the_content;1 oikth_the_content;1
: 2   oik_do_shortcode;1
: 8   WP_Embed::run_shortcode;1 WP_Embed::autoembed;1
: 10   prepend_attachment;1 wp_make_content_images_responsive;1
: 11   capital_P_dangit;1 do_shortcode_earlier;1
: 20   convert_smilies;1
: 98   wptexturize_blocks;1
: 99   bw_wpautop;1
: 9999   bw_trace_results;9
*/

	function failing_string() {
		$string = '<div class="adr bw_address">';
		$string .= '<div class="type">Wrok</div>';
		$string .= '<div class="extended-address">';
		$string .= '</div>';
		$string .= '<div class="street-address">41 Redhill Road</div>';
		$string .= '<div class="locality">Rowland\'s Castle</div>';
		$string .= '<div class="region">HAMPSHIRE</div>';
		$string .= '<div class="postal-code">PO9 6DE</div>';
		$string .= '<span class="country-name">United Kingdom</span>';
		$string .= '</div>';
		return $string;
	}

}
