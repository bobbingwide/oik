<?php // (C) Copyright Bobbing Wide 2016,2017

/**
 * @package oik-sc-help
 * 
 * Tests for logic in includes/oik-sc-help.inc
 * Tests for logic in libs/oik-sc-help.php are in oik-libs
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
	
	/**
	 * Test shortcode snippet logic for a shortcode that enqueues scripts and/or styles
	 * 
	 * Notes: 
	 * - We can't use bw_follow or bw_github since they're not actually shortcodes
	 * - The test is dependent upon oik-css being active - it alters automatic paragraph creation
	 * - the oik-option valuue for Twitter is expected to be herb_miller
	 * 
	 -'<p lang="HTML" escaped="true">&lt;p&gt;&lt;a href=&quot;https://www.twitter.com/herb_miller&quot; title=&quot;Follow me on Twitter&quot;&gt;&lt;span class=&quot;generi
con genericon-twitter bw_follow_me &quot;&gt;&lt;/span&gt;&lt;/a&gt;&lt;/p&gt;
-</p><p>a:4:{s:7:"scripts";a:0:{}s:14:"queued_scripts";a:0:{}s:6:"styles";a:1:{s:10:"genericons";O:14:"_WP_Dependency":6:{s:6:"handle";s:10:"genericons";s:3:"src";s:72:
"http://qw/wordpress/wp-content/plugins/oik/css/genericons/genericons.css";s:4:"deps";a:0:{}s:3:"ver";b:0;s:4:"args";s:3:"all";s:5:"extra";a:0:{}}}s:13:"queued_styles";
a:1:{i:0;s:10:"genericons";}}</p>'
+'something with serialized stuff'
   * 
	 */
	function test_sc__snippet() {
		do_action( "oik_add_shortcodes" );
		_sc__snippet( "bw_twitter", "theme=gener alt=0" );
		$html = bw_ret();
		$expected_output = '<p lang="HTML" escaped="true">&lt;p&gt;&lt;a href=&quot;https://www.twitter.com/herb_miller&quot; title=&quot;Follow me on Twitter&quot;&gt;&lt;span class=&quot;genericon genericon-twitter bw_follow_me &quot;&gt;&lt;/span&gt;&lt;/a&gt;&lt;/p&gt;';
		$expected_output .= "\n</p>";
		$this->assertEquals( $expected_output, $html );
		//'/genericons.css";s:4:"deps";a:0:{}s:3:"ver";b:0;s:4:"args";s:3:"all";s:5:"extra";a:0:{}}}s:13:"queued_styles";a:1:{i:0;s:10:"genericons";}}</p>', $html );
	}
	
	function test_div__help() {
		$this->switch_to_locale( "en_GB" );
		$html = div__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_ediv__help() {
		$this->switch_to_locale( "en_GB" );
		$html = ediv__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
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
	
	function test_sdiv__syntax() {
		$this->switch_to_locale( "en_GB" );
		$array = sdiv__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_ediv__syntax() {
		$this->switch_to_locale( "en_GB" );
		$array = ediv__syntax();
		$this->assertNull( $array );
	}
	
}
