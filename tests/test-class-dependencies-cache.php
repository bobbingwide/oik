<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package class-dependencies-cache
 * 
 * Tests for logic in libs/class-dependencies-cache.php
 */
class Tests_class_dependencies_help extends BW_UnitTestCase {

	function setUp(): void {
		$dependencies = oik_require_lib( "class-dependencies-cache" );
		
	}

	/**
	 * When adding items to an array using square bracket syntax
	 * the new index that's used depends on when the array was last indexed.
	 *
	 * See the Note: in https://www.php.net/manual/en/language.types.array.php
	 * mentioning 'need not currently exist in the array'
	 *
	 * This demonstrates that the index is 1 even after removing index 0.
	 * 
	 * But will reset to 0 after re-indexing using array_values();
	 * @return void
	 */
	function test_square_bracket_syntax() {
		$queued = [];
		$queued[] = 'woo-tracks';
		$this->assertEquals( [ 'woo-tracks'], $queued );
		unset( $queued[ 0 ] );
		$queued[] = 'towci1';
		$this->assertEquals( [1 => 'towci1'], $queued );
		unset( $queued[1] );
		$queued = array_values( $queued );
		$queued[] = 'towci1';
		$this->assertEquals( [0 => 'towci1'], $queued );
	}

	
	/**
	 * Tests for oik-widget-cache Issue #1
	 * 
	 * We need to be able to detect calls to wp_enqueue_script and wp_enqueue_style
	 * so that we can cache that output as well.
	 *
	 * This test used to fail if WooCommerce was activated.
	 * - There's another script called woo-tracks that wasn't catered for.
	 * - It's enqueued by WooCommerce during `init`.
	 * - This caused the entry in $serialized['queued_scripts'] for 'towci1' to be indexed by 1 not 0.
	 * - Deactivating WooCommerce resolved the problem... but there's another solution.
	 * - I tried to dequeue 'woo-tracks' to get 'towci1' to be indexed as 0, but due to the way square bracket syntax
	 * assignment works ( see test_square_bracket_syntax()) this is not possible.
	 * - So we re-index the queued_scripts array before the assertion.
	 *
	 * This test should now work regardless of WooCommerce activation status
	 */
	function test_oik_widget_cache_issue_1() {

		$dependencies_cache = dependencies_cache::instance();
		$dependencies_cache->save_dependencies();
		$serialized = $dependencies_cache->serialize_dependencies();
		bw_trace2( $serialized, "serialized before", false );
		wp_enqueue_script( "towci1", "towci1.js", array(), "1.0", null, false );
		$dependencies_cache->query_dependencies_changes();
		$serialized = $dependencies_cache->serialize_dependencies();
		// Re-index the queue_scripts array in case 'woo-tracks' was previously queued.
		$serialized['queued_scripts'] = array_values( $serialized['queued_scripts']);
		bw_trace2( $serialized, "serialized after enqueue", false );
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
