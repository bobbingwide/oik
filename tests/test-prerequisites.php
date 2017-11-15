<?php // (C) Copyright Bobbing Wide 2017


class Tests_prerequisites extends BW_UnitTestCase {

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are NOT rolled back
	 * - if you don't call parent::setUp then $this->setExpectedDeprecated() won't work; as the deprecation checking is not enabled
	 */
	function setUp() {
		// parent::setUp();
		oik_require( "includes/bw_posts.inc" );
		
	}
	
	/**
	 * The dummy posts that we create will be pages with "post title" and optional suffix n
	 * We don't want these to be in the database otherwise we'll get invalid suffices in the permalinks
	 * 
	 * They can't be in trash either!
	 */
	function test_delete_pages_with_post_title() {
	
		$this->delete_posts_with_post_title( "post title" );
		$this->delete_posts_with_post_title( "post title", "trash" );
		$this->delete_posts_with_post_title( "post title 1" );
		$this->delete_posts_with_post_title( "post title 1", "trash" );
		$this->delete_posts_with_post_title( "post title 2" );
		$this->delete_posts_with_post_title( "post title 2", "trash" );
		$this->delete_posts_with_post_title( "post title 3" );
		$this->delete_posts_with_post_title( "post title 3", "trash" );
		
		$this->test_no_posts();
	}
	
	function test_no_posts() {
		$this->check_no_posts( "post title" );
		$this->check_no_posts( "post title", "trash" );
		$this->check_no_posts( "post title 1" );
		$this->check_no_posts( "post title 1", "trash" );
		$this->check_no_posts( "post title 2" );
		$this->check_no_posts( "post title 2", "trash" );		
		$this->check_no_posts( "post title 3" );
		$this->check_no_posts( "post title 3", "trash" );
	}
	
	
	function get_posts( $post_title, $post_status="any" ) {
		$atts = array( "post_type" => "page" 
								 , "numberposts" => -1
								 , "title" => $post_title
								 , "post_parent" => "."
								 , "post_status" => $post_status
								 );
		$posts = bw_get_posts( $atts );
		return $posts;
	}
	
	/**
	 * Checks there are no matching posts
	 */
	function check_no_posts( $post_title, $post_status="any" ) {
		$posts = $this->get_posts( $post_title, $post_status );
		$this->assertEquals( array(), $posts );
	}
	
	/**
	 *  post_status = "any" does not include trash auto-draft or spam
	 *  wp_posts.post_status <> 'trash' AND wp_posts.post_status <> 'auto-draft' AND wp_posts.post_status <> 'spam')
	 *
	 * If this method finds a post it will issue a message, which makes this test Risky.
	 * But we commit, so the next run and subsequent runs should be OK.
	 */
	function delete_posts_with_post_title( $post_title, $post_status="any" ) {
		$posts = $this->get_posts( $post_title, $post_status );
		foreach ( $posts as $post ) {
			if ( $post->post_title == $post_title ) {
				echo "Deleting {$post->ID} {$post->post_title} " . PHP_EOL;
				wp_delete_post( $post->ID, true );
			}
		}
		$this->commit_transaction();
	}
	
	/** 
	 * Forcibly commit the transaction
	 */
	public static function commit_transaction() {
		global $wpdb;
		$wpdb->query( 'COMMIT' );
		bw_trace2( $wpdb, "wpdb" );
	}
	
	/** 
	 * For some reason qw/phphants returned /files/ instead of /wp-content/uploads
	 * Don't know why but it broke 3 tests.
	 * 
	 * `
    [path] => C:\apache\htdocs\wpms/wp-content/uploads/2017/11
    [url] => https://qw/wpms/wp-content/uploads/2017/11
    [subdir] => /2017/11
    [basedir] => C:\apache\htdocs\wpms/wp-content/uploads
    [baseurl] => https://qw/wpms/wp-content/uploads
    [error] =>
	 * `
	 * 
	 * But they're not broken any more
	 (
    [path] => C:\apache\htdocs\wpms/wp-content/blogs.dir/9/files/2017/11
    [url] => https://qw/wpms/phphants/files/2017/11
    [subdir] => /2017/11
    [basedir] => C:\apache\htdocs\wpms/wp-content/blogs.dir/9/files
    [baseurl] => https://qw/wpms/phphants/files
    [error] =>
)

	 */
	function test_wp_upload_dir() {
		$upload_dir = wp_upload_dir();
		//print_r( $upload_dir );
		
		
		if ( is_multisite() && !is_main_site() ) {
      $this->assertContains( "/files", $upload_dir['basedir'] );
		} else {
			$this->assertContains( "/wp-content/uploads", $upload_dir['basedir'] );
		}
	}
		
	
	
	
}
	
