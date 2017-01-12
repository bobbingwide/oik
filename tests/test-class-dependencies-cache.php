<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package class-dependencies-cache
 * 
 * Tests for logic in libs/class-dependencies-cache.php
 */
class Tests_class_dependencies_help extends BW_UnitTestCase {

	function setUp() { 
		$dependencies = oik_require_lib( "class-dependencies-cache" );
		
	}

	
	/**
	 * Tests for oik-widget-cache Issue #1
	 * 
	 * We need to be able to detect calls to wp_enqueue_script and wp_enqueue_style
	 * so that we can cache that output as well.
	 * 
	 */
	function test_oik_widget_cache_issue_1() {
		$dependencies_cache = dependencies_cache::instance();
		$dependencies_cache->save_dependencies();
		wp_enqueue_script( "towci1", "towci1.js", array(), "1.0", null, false );
		$dependencies_cache->query_dependencies_changes();
		$serialized = $dependencies_cache->serialize_dependencies();
		bw_trace2( $serialized, "serialized", false );
		$this->assertEquals( $serialized['queued_scripts'], array( "towci1" ) );
		
		$this->assertEquals( $serialized['scripts']['towci1']->src, "towci1.js" );
		$this->assertEquals( $serialized['scripts']['towci1']->ver, "1.0" );
		
		
		wp_dequeue_script( "towci1" );
		
		$dependencies_cache->reload_dependencies( $serialized );
		
		$dependencies_cache->replay_dependencies();
		
		$serialized_again = $dependencies_cache->serialize_dependencies();
		
		$this->assertEquals( $serialized['queued_scripts'], $serialized_again['queued_scripts'] );
		
		$this->assertEquals( $serialized_again['scripts']['towci1']->src, "towci1.js" );
		$this->assertEquals( $serialized_again['scripts']['towci1']->ver, "1.0" );
	}

}
