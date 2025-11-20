<?php // (C) Copyright Bobbing Wide 2017, 2025

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

		// Note: This test can fail if a plugin such as simple-lightbox is activated.
        // I got "Error: Call to a member function add_theme_page() on null" from that plugin.
		// 2025/11/19 - also "Warning: foreach() argument must be of type array|object, null given"
		// in C:\apache\htdocs\wordpress\wp-admin\includes\plugin.php on line 1854
		// due to WooCommerce attempting to delete a menu item when no menus items were present.
		// Workaround: deactivate WooCommerce
		do_action( "admin_menu" );
		
		$exists = function_exists( "oik_load_plugins" );
		$this->assertTrue( $exists );
		
		do_action( "activate_plugin", "issue-69" );
		
		
		
		
		
	}																									
	


}
