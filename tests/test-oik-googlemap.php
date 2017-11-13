<?php // (C) Copyright Bobbing Wide 2017

/** 
 * Unit tests for the [bw_show_googlemap] shortcode
 * Specifically to test issue #44 - Uncaught Syntax Error when lat / lng is null
 *
 * More tests in tests/test-shortcodes-oik-googlemap.php
 */

class Tests_oik_googlemap extends BW_UnitTestCase {

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 * - we need oik-googlemap to load the functions we're testing
	 */
	function setUp() {
		parent::setUp();
		$oik_plugins = oik_require_lib( "oik_plugins" );
		//bw_trace2( $oik_plugins, "oik_plugins" );
		oik_require( "shortcodes/oik-googlemap.php" );
	}

	/**
	 * Test with no parameters whatsoever
	 *
	 var latlng = new google.maps.LatLng(,);
	 */
	function test_bw_show_googlemap() {
		$this->switch_to_locale( "en_GB" );
		bw_update_option( "company", null );
		bw_update_option( "postal-code", null );
		bw_update_option( "gmap_intro", null );
		bw_update_option( "google_maps_api_key", "AIzaSyBU6GyrIrVZZ0auvDzz_x0Xl1TzbcYrPJU" );
		bw_update_option( "contact", null );
		bw_update_option( "lat", null );
		bw_update_option( "long", null );
	
		bw_gmap_map( null );
		$atts = array();
		$html = bw_show_googlemap( $atts, null, null );
		$html_array = $this->tag_break( $html );
		$this->assertArrayEqualsFile( $html_array );
	}

	/**
	 * Test second call
	 * 
	 * Will null lat and long as parameters cause this invalid code to be generated? 
	 *
	 * `
	 * var latlng = new google.maps.LatLng(,);
	 * `
	 * @TODO Ensure this really is called second.
	 */
	function test_bw_show_googlemap_null_lat_and_long() {
		$this->switch_to_locale( "en_GB" );
		bw_gmap_map( null );
		bw_update_option( "company", null );
		bw_update_option( "postal-code", null );
		bw_update_option( "gmap_intro", null );
		bw_update_option( "google_maps_api_key", "AIzaSyBU6GyrIrVZZ0auvDzz_x0Xl1TzbcYrPJU" );
		bw_update_option( "lat", null );
		bw_update_option( "long", null );
		$atts = array( "lat"=> null
								 , "long" => null );
		$html = bw_show_googlemap( $atts, null, null );
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html_array );
		$this->assertArrayEqualsFile( $html_array );
	}

	function test_bw_default_empty_att() {
		$alt = "9";
		$set = "bw_options$alt"; 
		$lat = null;
		$lat = bw_default_empty_att( $lat, "lat", 50.887856, $set );
		$this->assertEquals( "50.887856", $lat );
	}
	
	/**
	 * A rather silly test
	 *
	 * In PHP 7 it appears that a double equal sign comparison is not good enough
	 * if the marker is not specified with decimals since the comparison operator
	 * will convert strings to an integer and end up matching 30 with 30.
	 * Which is not what we want.  
	 * 
	 * 
	 */
	function test_absint_comparison() {
		$marker = "30:60";
		$absinted = absint( $marker );
		if ( $marker == $absinted ) {
			//echo "It shouldn't be an integer so why did it match?";
			//echo "absinted: $absinted , marker: $marker .";
			$this->assertEquals( 30, $absinted );
			$this->assertEquals( "30:60", $marker );
			$this->assertNotEquals( $marker, $absinted );
		} else {
			$this->assertEquals( 30, $absinted );
			$this->assertEquals( "30:60", $marker );
			$this->assertNotEquals( $absinted, $marker );
		}
	}

	/**
	 * Tests different values for gmap markers
	 *
	 * Notes: 
	 * - We don't expect 9 to be a valid set for oik_options so the LatLng will be the default for bobbingwide
	 * - Then it's the top of the world - according to GoogleMaps
	 * - Then Greenwich - according to WikiPedia
	 */
	function test_bw_gmap_markers() {
		$markers = "9,85:0,51.4826:0.0077";
		bw_gmap_markers( $markers );
		$output = bw_ret();
		$expected = null;
		
		$expected .= 'latlng = new google.maps.LatLng(50.887856,-0.965113);var marker = new google.maps.Marker({ position: latlng, title:"50.887856,-0.965113"});marker.setMap( map );';
		$expected .= 'latlng = new google.maps.LatLng(85,0);var marker = new google.maps.Marker({ position: latlng, title:"85,0"});marker.setMap( map );';
		$expected .= 'latlng = new google.maps.LatLng(51.4826,0.0077);var marker = new google.maps.Marker({ position: latlng, title:"51.4826,0.0077"});marker.setMap( map );';
		$this->assertEquals( $expected, $output );
	}

}
