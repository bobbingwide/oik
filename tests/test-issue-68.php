<?php // (C) Copyright Bobbing Wide 2017

class Tests_issue_68 extends BW_UnitTestCase {

	public $captured = null;

	/**
	 * Issue #68 - Shortcode snippets should be captured on first invocation
	 * 
	 * Requirement | Tested ?
	 * ----------- | ----------------------------
	 * A number of shortcodes enqueue jQuery which we'd like to see in the snippet | see tests\test-oik-sc-help.php for a test of _sc_snippet which now produces the enqueued style sheet
	 * See enqueued script and styles | See below
	 * Others increment a value which should be the same as in the example | See below 
	 * Others use bw_jq() to create inline jQuery. | Not yet implemented.
	 */

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 */
	function setUp() {
		parent::setUp();
		oik_require_lib( "oik-sc-help" );
		//$dependencies = oik_require_lib( "class-dependencies-cache" );
	}
	
	/**
	 * We want to be able to see the HTML that's generated as a result of the scripts and styles that have been enqueued
	 * On the first invocation there should might not be any latest HTML.
	 * On the second, if nothing's been enqueued, then there shouldn't be any new HTML.
	 */
	function test_bw_save_scripts() {
		bw_save_scripts();
		$html = bw_report_scripts();
		$this->assertEquals( '', $html );
	}
	
	/**
	 * Tests for oik-widget-cache Issue #1
	 * 
	 * This is a copy of the test in test-class-dependencies-cache.php but using a different script handle. 
	 * We have to do this otherwise we don't see the script being enqueued.
	 * We also have to cater for the fact that the result from serialize_dependencies() varies as scripts are enqueued and dequeued.
	 * The values remain the same but the key changes. 
	 */
	function test_oik_widget_cache_issue_1() {
	
		wp_dequeue_script( "issue-68" );
		$dependencies = oik_require_lib( "class-dependencies-cache" );
		$dependencies_cache = dependencies_cache::instance();
		$dependencies_cache->save_dependencies();
		wp_enqueue_script( "issue-68", "issue-68.js", array(), "1.0", null, false );
		$dependencies_cache->query_dependencies_changes();
		$serialized = $dependencies_cache->serialize_dependencies();
		bw_trace2( $serialized, "serialized", false );
		$serialized_queued_scripts = array_values( $serialized['queued_scripts'] );
		$this->assertEquals( $serialized_queued_scripts, array( "issue-68" ) );
		
		$this->assertEquals( $serialized['scripts']['issue-68']->src, "issue-68.js" );
		$this->assertEquals( $serialized['scripts']['issue-68']->ver, "1.0" );
		
		
		wp_dequeue_script( "issue-68" );
		
		$dependencies_cache->reload_dependencies( $serialized );
		
		$dependencies_cache->replay_dependencies();
		
		$serialized_again = $dependencies_cache->serialize_dependencies();
		
		$this->assertEquals( $serialized['queued_scripts'], $serialized_again['queued_scripts'] );
		
		$this->assertEquals( $serialized_again['scripts']['issue-68']->src, "issue-68.js" );
		$this->assertEquals( $serialized_again['scripts']['issue-68']->ver, "1.0" );
	}
	
	/**
	 * - wp_print_footer_scripts() cannot be called multiple times to produce the same output
	 * - _wp_footer_scripts() can't either
	 * 
	 */
	function test_bw_report_scripts_after_enqueue_script() {
		$this->switch_to_locale( 'en_GB' );
		wp_dequeue_script( "issue-68" );
		wp_deregister_script( "issue-68" );
		bw_save_scripts();
		wp_enqueue_script( "issue-68", "http://example.com/issue-68.js", array(), "1.0", null, false );
		bw_save_scripts();
		$html = bw_report_scripts();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_report_scripts_after_enqueue_style() {
		$this->switch_to_locale( 'en_GB' );
		bw_save_scripts();
		wp_enqueue_style( "issue-68", "http://example.com/issue-68.css", array(), "1.0", null, false );
		bw_save_scripts();
		$html = bw_report_scripts();
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	/**
	 * Tests some of the logic in bw_expand_shortcode()
	 *
	 * [bw_show_googlemap] is a shortcode which performs incrementing.
	 * We visually checked the example and snippet when creating the original test data file.
	 */
	function test_html_for_example_and_snippet_is_the_same() {
		oik_require( "shortcodes/oik-googlemap.php" );
		oik_require_lib( "oik_plugins");
	
		bw_gmap_map( null );
	
		bw_update_option( "company", null );
		bw_update_option( "postal-code", null );
		bw_update_option( "gmap_intro", null );
		bw_update_option( "google_maps_api_key", "AIzaSyBU6GyrIrVZZ0auvDzz_x0Xl1TzbcYrPJU" );
		bw_update_option( "contact", null );
		bw_update_option( "lat", null );
		bw_update_option( "long", null );
		$this->switch_to_locale( "en_GB" );
		
		bw_show_googlemap__example();
		_sc__snippet( "bw_show_googlemap" );
		
		$html = bw_ret();
		$html_array = $this->tag_break( $html );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	

}

