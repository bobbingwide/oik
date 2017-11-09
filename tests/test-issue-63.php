<?php // (C) Copyright Bobbing Wide 2017

class Tests_issue_63 extends BW_UnitTestCase {



  /**
	 * Tests that our "Howdy %s", replacement logic still works in the latest version of WordPress
	 * 
	 * - We need to get WordPress to create the my-account node 
	 * - testing that it contains what we expect
	 * - Note: The UK English version is "Hi, %s" with %s being replaced by a span surrounding the user id
	 * - we then modify it using oik_admin_bar_menu()
	 * - and check it contains our replacement for the Howdy, part... which is Hi, in UK English ( en_GB)
	 * 
	 * `
		(
    [id] => my-account
    [title] => Hi, <span class="display-name">vsgloik</span><img alt='' src='http://1.gravatar.com/avatar/1c32865f0cfb495334dacb5680181f2d?s=26&#038;d=mm&#038;r=g' srcse
2&amp;d=mm&amp;r=g 2x' class='avatar avatar-26 photo' height='26' width='26' />
    [parent] => top-secondary
    [href] => https://qw/wordpress/wp-admin/profile.php
    [group] =>
    [meta] => Array
        (
            [class] => with-avatar
        )
		`
	 * If the test fails when checking for "Hi," then it could be because the language files do not match the version of WordPress.
	 * This was the case in the qw/wpms environment. 		
	 */
	function tests_oik_admin_bar_menu() {
		require_once( ABSPATH . WPINC . '/class-wp-admin-bar.php' );
		oik_require_lib( "oik_plugins" );
		bw_update_option( "howdy", "omga:" );
		$this->switch_to_locale( 'en_GB' );
		$user = wp_set_current_user( 1 );
		$wp_admin_bar = new WP_admin_bar();
		wp_admin_bar_my_account_item( $wp_admin_bar );
		$node = $wp_admin_bar->get_node( 'my-account' ); 
		$this->assertEquals( 'my-account', $node->id );
		$this->assertStringStartsWith( 'Hi, <span class="display-name">', $node->title );
		oik_admin_bar_menu( $wp_admin_bar );
		$node = $wp_admin_bar->get_node( 'my-account' );
		$this->assertStringStartsWith( 'omga: <span class="display-name">', $node->title );
		
	}																									
	


}
