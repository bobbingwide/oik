<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package 
 * 
 * Tests for logic in shortcodes/oik-jquery.php
 */
class Tests_shortcodes_oik_jquery extends BW_UnitTestCase {

	public $wp_scripts;

	function setUp(): void {
		parent::setUp();
		
		oik_require( "includes/oik-sc-help.php" );
		oik_require( "shortcodes/oik-jquery.php" ); 														
	}
	
	function test_bw_jq__help() {
		$this->switch_to_locale( "en_GB" );
		$html = bw_jq__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_jq__help_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$html = bw_jq__help();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function test_bw_jq__syntax() {
		$this->switch_to_locale( "en_GB" );
		$array = bw_jq__syntax();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_jq__syntax_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$array = bw_jq__syntax();
		$html = $this->arraytohtml( $array, true );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	function dont_test_bw_list_wp_scripts() {
		$this->switch_to_locale( "en_GB" );
		$this->save_scripts();
		$this->setup_test_scripts();
		$html = bw_ret( bw_list_wp_scripts() );
		$this->restore_scripts();
    //$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function dont_test_bw_list_wp_scripts_bb_BB() {
		$this->switch_to_locale( "bb_BB" );
		$this->save_scripts();
		$this->setup_test_scripts();
		$html = bw_ret( bw_list_wp_scripts() );
		$this->restore_scripts();
    //$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( "en_GB" );
	}
	
	/**
	 * Similar logic to http://develop.wp-a2z.org/oik_api/tests_dependencies_scriptssetup/
	 * This needed updating for WordPress 5.0 - wp_default_packages is new.
	 */
	function setup_test_scripts() {
		remove_action( 'wp_default_scripts', 'wp_default_scripts' );
		remove_action( 'wp_default_scripts', 'wp_default_packages' );
		$scripts = new WP_Scripts();
		//print_r( $scripts );
		$GLOBALS['wp_scripts'] = $scripts;
		//$wp_scripts->registered = array( "test1" => "script1" );
		wp_register_script( "handle", "src", null, "42" );
	}
	
	function save_scripts() {
		global $wp_scripts;
		$this->wp_scripts = $wp_scripts;
	}
	
	function restore_scripts() {
		global $wp_scripts;
		$wp_scripts = $this->wp_scripts;
	}

	
}
	
