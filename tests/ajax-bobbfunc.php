<?php // (C) Copyright Bobbing Wide 2016


class ajax_bobbfunc extends BW_UnitTestCase {

	function setUp(): void {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			// That's OK then
		} else {
			echo DOING_AJAX; 
			echo "constant not defined or not true"; 
			bw_trace2( get_defined_constants(), "constants" );
			gob();
		}
	}
	
	/**
	 * @group ajax
	 * 
	 * Nothing should be enqueued when DOING_AJAX
	 * so bw_jq_flush() should't be echoing anything
	 */
  function test_bw_jquery_noop_when_doing_ajax() {
		bw_jq_flush();
		bw_jquery( "selector", "method" );
		bw_jq_flush();
    $this->assertTrue( true );
		//echo "Well that would have worked" ;
  }
	
	/**
	 * @group ajax
	 * 
	 * This test confirms that bw_jq() does enqueue something
	 * and bw_jq_flush() echoes it and clears it.
	 */
	function test_bw_jq_and_bw_jq_flush() {
		bw_jq( "Enqueue" );
		bw_jq( " " );
		bw_jq( "this" );
		bw_jq_flush();
		bw_jq( "." );
		bw_jq_flush();
		$this->expectOutputString( "Enqueue this." );
	}
}
