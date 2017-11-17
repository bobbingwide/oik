<?php // (C) Copyright Bobbing Wide 2017

class Tests_issue_69 extends BW_UnitTestCase {

	/**
	 * Issue #69 - Warning issued during plugin activation under WP-cli

  /**
	 * We need to invoke the 'activate_plugin' action when we know that oik_load_plugins is NOT a defined function.
	 * Then invoke 'admin_menu' which will load oik-admin.php and try 'activate_plugin' again 
	 */
	function test_no_warning_for_activate_plugin() {
		$exists = function_exists( "oik_load_plugins" );
		$this->assertFalse( $exists );
		
		do_action( "activate_plugin" );
		
		$exists = function_exists( "oik_load_plugins" );
		$this->assertFalse( $exists );
		
		do_action( "admin_menu" );
		
		$exists = function_exists( "oik_load_plugins" );
		$this->assertTrue( $exists );
		
		do_action( "activate_plugin", "issue-69" );
		
		
		
		
		
	}																									
	


}
