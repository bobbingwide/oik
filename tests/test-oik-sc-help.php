<?php // (C) Copyright Bobbing Wide 2016,2017

/**
 * @package oik-sc-help
 * 
 * Tests for logic in includes/oik-sc-help.inc
 * Tests for logic in libs/oik-sc-help.php are in oik-libs
 */
class Tests_oik_sc_help extends BW_UnitTestCase {

	function setUp() { 
	
		parent::setUp();
		oik_require( "admin/oik-admin.inc" );	 
		oik_require( "includes/oik-sc-help.inc" ); 														
		$this->_url = oik_get_plugins_server();
		oik_require_lib( "oik_plugins" );
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
	
	/**
	 * Test shortcode snippet logic for a shortcode that enqueues scripts and/or styles
	 * 
	 * Notes: 
	 * - We can't use bw_follow or bw_github since they're not actually shortcodes
	 * - The test is dependent upon oik-css being active - it alters automatic paragraph creation
	 * - the oik-option value for Twitter is expected to be herb_miller
	 * - test updated for Issue #68 to report the generated stylesheet link
	 * - deregisters genericons as it may have been enqueued by Jetpack
	 */
	function test_sc__snippet() {
		wp_deregister_style( "genericons" );
		bw_update_option( "contact", null );
		bw_update_option( "twitter", "herb_miller" );
		do_action( "oik_add_shortcodes" );
		_sc__snippet( "bw_twitter", "theme=gener alt=0" );
		$html = bw_ret();
		$html = $this->replace_oik_url( $html );
		$html = $this->replace_wp_version( $html );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_div__help() {
		$this->switch_to_locale( "en_GB" );
		$html = div__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_div__help_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = div__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_ediv__help() {
		$this->switch_to_locale( "en_GB" );
		$html = ediv__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_ediv__help_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = ediv__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	/**
	 * Reduce a print_r'ed string
	 *
	 * print_r's an array then removes unwanted white space
	 */
	function arraytohtml( $array ) {
		$string = print_r( $array, true );
		$again = explode( "\n", $string );
		$again = array_map( "trim", $again );
		$string = implode( "\n", $again );
		return $string;
	}
	
	function test_div__syntax() {
		$this->switch_to_locale( "en_GB" );
		$array = div__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_div__syntax_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$array = div__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_sdiv__syntax() {
		$this->switch_to_locale( "en_GB" );
		$array = sdiv__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_sdiv__syntax_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$array = sdiv__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	
	function test_ediv__syntax() {
		$this->switch_to_locale( "en_GB" );
		$array = ediv__syntax();
		$this->assertNull( $array );
	}
	
	function test_ediv__syntax_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$array = ediv__syntax();
		$this->assertNull( $array );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_stag__syntax() {
		$this->switch_to_locale( "en_GB" );
		$array = stag__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_stag__syntax_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$array = stag__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_etag__syntax() {
		$this->switch_to_locale( "en_GB" );
		$array = etag__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_etag__syntax_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$array = etag__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_copyright__syntax() {
		bw_update_option( "company", "Bobbing Wide" );
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = bw_copyright__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_copyright__syntax_bb_BB() {
		//$this->setExpectedDeprecated( "bw_translate" );
		bw_update_option( "company", "Bobbing Wide" );
		$this->switch_to_locale( "bb_BB" );
		$array = bw_copyright__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_ad__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = ad__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_ad__syntax_bb_BB() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "bb_BB" );
		$array = ad__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_post_link__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = post_link__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_post_link__syntax_bb_BB() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "bb_BB" );
		$array = post_link__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_collage__syntax() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "en_GB" );
		$array = collage__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
  function test_collage__syntax_bb_BB() {
		//$this->setExpectedDeprecated( "bw_translate" );
		$this->switch_to_locale( "bb_BB" );
		$array = collage__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function replace_wp_version( $html ) {
		global $wp_version; 
		$html = str_replace( $wp_version, "x.y.z", $html );
		return $html;
  }
	
}
