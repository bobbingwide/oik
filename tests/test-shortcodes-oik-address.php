<?php // (C) Copyright Bobbing Wide 2016,2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-address.php
 */
class Tests_shortcodes_oik_address extends BW_UnitTestCase {

	function setUp(): void {
		parent::setUp();
		oik_require( "shortcodes/oik-address.php" );
		oik_require_lib( "oik-sc-help" );
		$this->update_options();
	}
	
	function test_bw_address__syntax() {
		$this->switch_to_locale( "en_GB" );
		$array = bw_address__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_address__syntax_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$array = bw_address__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	/**
	 * This tests the output from the shortcode. 
	 * The 'the_content' filter is not applied so wpautop() does not mess with it.
	 */
	function test_bw_address() {
		$this->switch_to_locale( "en_GB" );
		$atts = array(); 
		$html = bw_address( $atts);
		$html_array = $this->tag_break( $html ); 
		$this->assertNotNull( $html_array );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}
	
	function test_bw_address_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$atts = array(); 
		$html = bw_address( $atts);
		$html_array = $this->tag_break( $html ); 
		$this->assertNotNull( $html_array );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
		$this->switch_to_locale( "en_GB" );
	}
	
	/**
	 * In this test 'the_content' filter messes with our nicely crafted HTML,
	 * adding an unwanted p tag but no end tag.
	 *
	 * Other plugins can affect the result of this test case.
	 * eg the bigram plugin disables wptexturize()
	 * which means a single quote is not encoded to &#8217;
	 * Workaround: deactivate bigram or remove the filter.
	 * But see below... removing the filter doesn't work.
	 */
	function test_bw_address__example() {

		$this->switch_to_locale( "en_GB" );
		/** Note: It's not possible to alter wptexturize's logic once it's already been run
		 * See TRAC #54721
		 * In other words, the code below doesn't YET work.
		 *
		 */
		remove_filter( 'run_wptexturize', '__return_false', 10);
		wptexturize( '', true );
		$html = bw_ret( bw_address__example() );
		// Fiddle the result to pretend wptexturize worked for single quotes.
		$html = str_replace( "'", '&#8217;', $html );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_address__example_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = bw_ret( bw_address__example() );
		//$this->generate_expected_file( $html );
		// Fiddle the result to pretend wptexturize worked for single quotes.
		$html = str_replace( "'", '&#8217;', $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	/**
	 * How do we test the snippet logic for bw_address? 
	 */
	function test_bw_address__snippet() {
		$this->switch_to_locale( "en_GB" );
		bw_lazy_sc_snippet( "bw_address" );
		$html = bw_ret();
		// Fiddle the result to pretend wptexturize worked for single quotes.
		$html = str_replace( "&#039;", '&#8217;', $html );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_address__snippet_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		bw_lazy_sc_snippet( "bw_address" );
		$html = bw_ret();
		// Fiddle the result to pretend wptexturize worked for single quotes.
		$html = str_replace( "&#039;", '&#8217;', $html );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	/**
	 * Set the options to the values expected in the test output
	 */
	function update_options() {
		$bw_options = get_option( "bw_options" );
		$bw_options['extended-address'] = "";
		$bw_options['street-address'] = "41 Redhill Road";
		$bw_options['locality'] = "Rowland's Castle";
		$bw_options['region'] = "HAMPSHIRE";
		$bw_options['postal-code'] = "PO9 6DE";
		$bw_options['country-name'] = "United Kingdom";
		update_option( "bw_options", $bw_options );
	}

	
}
	
