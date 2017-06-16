<?php // (C) Copyright Bobbing Wide 2017

/** 
 * Unit tests for the [bw_jq] shortcode
 * Specifically to test issue #74	- jQuery.fn.load() is deprecated
 */

class Tests_oik_jquery extends BW_UnitTestCase {

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 * - we need oik-googlemap to load the functions we're testing
	 */
	function setUp() {
		parent::setUp();
		//$oik_plugins = oik_require_lib( "oik_plugins" );
		//bw_trace2( $oik_plugins, "oik_plugins" );
		oik_require( "shortcodes/oik-jquery.php" );
	}
	
	
	function get_jq() {
		global $bw_jq;
		return $bw_jq;
	}
	
	function reset_jq() {
		global $bw_jq;
		$bw_jq = null;
	}
	
	/**
	 * Issue #74 - Tests correct jQuery written when windowload is true
	 */
	function test_bwsc_jquery_windowload_true() {
		$atts = array( "selector" => "selector"
								 , "method" => "method" 
								 , "windowload" => true );
	
		$html = bwsc_jquery( $atts );
		$expected = null;
		$this->assertEquals( $expected, $html );
		
		$expected_jq = null; 
		$expected_jq = '<script type="text/javascript">jQuery(window).on( "load", function() { jQuery( "selector" ).method( {} ); });</script>';
		$jq = $this->get_jq();
		$this->assertEquals( $expected_jq, $jq );
		$this->reset_jq(); 
	}
	
/*		 
	
	 bwsc_jquery( $atts=null, $content=null, $tag=null ) {
  bw_trace2();
  bw_backtrace();
  $selector = bw_array_get_from( $atts, "selector,0", null );
  $method = bw_array_get_from( $atts, "method,1", null );
  if ( !$method ) {
    $method = str_replace( ".", "", $selector );
  }
  $script = bw_array_get( $atts, "script", $method );
  if ( $selector && $method ) { 
    $windowload = bw_array_get( $atts, "windowload", false );
    $debug = bw_array_get( $atts, "debug", false );   
    unset( $atts['selector'] );
    unset( $atts['method'] );
    unset( $atts['debug'] );
    unset( $atts['windowload'] );
    unset( $atts[0] );
    unset( $atts[1] );
    unset( $atts['script'] );
    $parms = bw_jkv( $atts );
    bw_jquery_enqueue_script( $script, $debug );
    bw_jquery_enqueue_style( $script );
    bw_jquery( $selector, $method, $parms, $windowload );
  } elseif ( $script ) {
    $debug = bw_array_get( $atts, "debug", false ); 
    bw_jquery_enqueue_script( $script, $debug );
  } elseif ( "?" == $method ) {
    bw_list_wp_scripts();
  } else {
    bw_jquery_src( $atts ); 
  }    
  return( bw_ret() );
}

*/

	

}
