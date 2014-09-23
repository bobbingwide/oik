<?php // (C) Copyright Bobbing Wide 2014

/**
 * Return the next unique shortcode ID
 *
 * There may be loads of shortcodes
 * Each of them could potentially display paged content so each one with paged content has to be given a unique ID 
 * @param bool $set - whether or not to increment the ID.  
 * @return integer - the ID suffix
 */
function bw_get_shortcode_id( $set=false ) {
  static $id = 0;
  if ( $set ) {
    $id++;
  }  
  return( $id );
}

/**
 * Return the page for the shortcode ID
 *
 * Example shortcode ID is 123
 * then if they've chosen "page 1" the value will be 1
 * and if they've chosen "page 2" the value will be 2
 * 
 * <a href=url?bwscid123=1> page 1 </a>
 * <a href=url?bwscid123=2> page 2 </a>
 * 
 * If they've chosen another link the value is not set.   *
 *
 * @param integer - the ID of the bwscidnnn field 
 * @return integer - the required page ID, defaults to 1 if not set
 *  
 */
function bw_get_parm_shortcode_id( $id ) {
  $page = bw_array_get( $_REQUEST, "bwscid$id", null );
  if ( !$page ) {
    $page = 1;
  } 
  return( $page );
}

/**
 * Return the required page if this is the shortcode we're processing
 * 
 * Called if this shortcode has a "posts_per_page" parameter
 * Note: Currently this simply calls bw_get_parm_shortcode_id()
 * In the future we may deal with different forms of paging. e.g. by starting letter, date
 *
 * We make a number of assumptions:
 * 1. The result set doesn't change between loadings of the page.
 * 2. As the visitor clicks on different links within the page a new/updated &bwscidnnn= is appended to the query URL
 * 3. Each paginated shortcode starts from page=1  
 * 
 * @param integer $bwscid - the shortcode ID
 * @return integer - required page  
 */
function bw_check_paged_shortcode( $bwscid ) {
  $page = bw_get_parm_shortcode_id( $bwscid );
  return( $page);
}

/**
 * Display pagination links for this shortcode
 *
 * If pagination was required then we'll have set 3 special fields in the shortcode $atts
 * Pass these values to the pagination function.
 * 
 * @param array $atts
 */
function oik_navi_lazy_paginate_links( $atts ) {
  $bwscid = bw_array_get( $atts, "bwscid", null );
  $bwscpage = bw_array_get( $atts, "paged", null );
  $bw_query = bw_array_get( $atts, "bw_query", null );
  if ( $bwscid && $bwscpage && $bw_query ) {  
    bw_navi_paginate_links( $bwscid, $bwscpage, $bw_query->max_num_pages); 
  } else {
    bw_trace2( "No pagination" );
  } 
}

/**
 * Paginate links for shortcode pagination
 *
 * Use paginate_links() to create special pagination links for shortcodes
 * These work independently of WordPress pagination, which uses "page".
 *
 * When the user is visiting the page, 
 * since each shortcode produces its own shortcode ID ( bwscid$id ), 
 * then the URL will slowly build up the pagination information for each shortcode. 
 * This avoids the need for cookies and/or jQuery to remember the page state of each shortcode.
 * 
 * Theoretically, this solution works on archive pages.
 * 
 * Things can go awry if the displayed content changes, such that a nested shortcode
 * with pagination suddenly appears. That's some complicated page we'd rather not attempt to deal with right now.
 * 
 * @param integer $id - the 'unique' shortcode ID for this instance of a paged shortcode
 * @param integer $page - the current page number ( starts from 1 )
 * @param integer $pages - the total number of pages
 *
 
 add_query_arg( "bwscid$id", $page );
 
  //'base' => '%_%', // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
    'format' => '?page=%#%', // ?page=%#% : %#% is replaced by the page number
    'total' => 1,
    'current' => $page
    'show_all' => false,
    'prev_next' => true,
    'prev_text' => __('« Previous'),
    'next_text' => __('Next »'),
    'end_size' => 1,
    'mid_size' => 2,
    'type' => 'plain',
    'add_args' => false, // array of query args to add
    'add_fragment' => '',
    'before_page_number' => '',
    'after_page_number' => ''
  );
 *
 */ 
function bw_navi_paginate_links( $id, $page, $pages ) {
  //bw_trace2();
  $base = add_query_arg( "bwscid$id", "%_%" );
  $format = "%#%";
  $args = array( "base" => $base
               , "format" => $format
               , "total" => $pages
               , "current" => $page
               , "before_page_number" => "["
               , "after_page_number" => "]"
               );
  // We don't need to worry about these yet             
  //  'show_all' => false,
  //  'prev_next' => true,
  //  'prev_text' => __('« Previous'),
  //  'next_text' => __('Next »'),
  //  'end_size' => 1,
  //  'mid_size' => 2,
  //  'type' => 'plain',
  //  'add_args' => false, // array of query args to add
  //  'add_fragment' => '',
  e( paginate_links( $args ) );
}

/**
 * Display posts using the [bw_navi] shortcode
 *  
 * 
 * @TODO: If we want an ordered list then we should add the start number to the list.
 * So we need to know the page number and posts_per_page
 * 
 */
function bw_navi_posts( $posts=null, $atts=null ) {
  oik_require( "shortcodes/oik-list.php" );
  $ol = bw_sl( $atts );
  foreach ( $posts as $post ) {
    bw_format_list( $post, $atts );
  }
  bw_el( $ol );
  return( $posts );
}

/**
 * Determine the number of pages
 */
function bw_determine_pages( $posts, $posts_per_page ) {
  // p( "Post count: $count" );
  // p( "per page: $posts_per_page" );
  // p( "Pages: $pages" );
  return( $pages );
}

/**
 * Display "start to end of count" message
 *
 * Display the start to end of count message if the count is set
 * 
 * @TODO - Add a Show all button.
 *
 * @param integer $start - start index (based on 0 for PHP arrays)
 * @param integer $end - end index (based on 0 for PHP arrays)
 * @param integer $count - total number of items. 
 * @param string $prefix - optional prefix
 */ 
function bw_navi_s2eofn( $start, $end, $count, $prefix=null ) {
  if ( $count ) {
    $start++;
    $end++;
    //$count++;
    if ( $prefix ) {
      br( $prefix );
    }
    span( "bw_s2eofn" );  
    e( sprintf( bw_translate( '%1s to %2s of %3s') , $start, $end, $count ) );
    epan();
  }   
}

/**
 * Paginate an array of post IDs
 *
 * Given an array of post IDs we need to find the first post to display
 * from the passed page ID for this instance
 * and only display posts from start to end 
 * then display the pagination stuff
 *
 * @param array $posts - array of post IDs
 * @param arry $atts - shortcode parameters
 */
function bw_navi_ids( $posts, $atts=null ) {
  $bwscid = bw_get_shortcode_id( true );
  $page = bw_check_paged_shortcode( $bwscid );
  $posts_per_page = get_option( "posts_per_page" );
  $count = count( $posts );
  $pages = ceil( $count / $posts_per_page );
  $start = ( $page-1 ) * $posts_per_page;
  $end = min( $start + $posts_per_page, $count ) -1;
  bw_navi_s2eofn( $start, $end, $count );
  oik_require( "shortcodes/oik-list.php" );
  $ol = bw_sl( $atts );
  for ( $i = $start; $i<= $end; $i++ ) {
    bw_list_id( $posts[$i] );
  }
  bw_el( $ol );
  bw_navi_paginate_links( $bwscid, $page, $pages ); 
} 

/**
 *   
 */
function oik_navi_s2eofn_from_query( $atts ) { 
  $bw_query = bw_array_get( $atts, "bw_query", null );
  bw_trace2( $bw_query, "bw_query", false );
  if ( $bw_query ) {
    $page = bw_array_get( $atts, "paged", 1 );
    $posts_per_page = bw_array_get( $atts, "posts_per_page", null );
    if ( $posts_per_page ) {
      $count =  $bw_query->found_posts;
      bw_trace2( $bw_query->found_posts, "found_posts", false );
      $pages = ceil( $count / $posts_per_page );
      $start = ( $page-1 ) * $posts_per_page;
      $end = min( $start + $posts_per_page, $count ) -1 ;
      bw_navi_s2eofn( $start, $end, $count );
    }
  }  
}

/**
 * Implement [bw_navi] shortcode 
 *
 * This function has two purposes.
 * 1. Used as a simple shortcode to display a paginated list, using default value for posts_per_page
 * 2. Invoked directly from APIs that want to display a paginated list
 *
 * When invoked directly then we need to invoke the pagination logic ourselves.
 * Otherwise the logic is implemented by filters invoked from bw_shortcode_event()
 *
 * Values that can control how many posts are returned per page include:
 *  posts_per_page           (option name "posts_per_page" )
 *  posts_per_archive_page   No option name
 *  posts_per_rss            (option name "posts_per_rss" )
 * 
 * We currently only use "posts_per_page" 
 *
 * @param array $atts
 * @param string $content
 * @param string $tag - shortcode tag
 * @return string - generated HTML
 */
function bw_navi( $atts=null, $content=null, $tag=null ) {
  //bw_trace2();
  $posts_per_page = bw_array_get_dcb( $atts, "posts_per_page", null );
  if ( !$posts_per_page ) {
    $atts['posts_per_page'] = get_option( "posts_per_page" ); 
    $atts = oik_navi_shortcode_atts( $atts );
  }
  $atts['numberposts'] = bw_array_get( $atts, "numberposts", -1 );
  $atts['thumbnail'] = bw_array_get( $atts, "thumbnail", "none" );
  oik_require( "includes/bw_posts.inc" );
  $posts = bw_get_posts( $atts ); 
  if ( !$posts_per_page ) {
    oik_navi_s2eofn_from_query( $atts );
  }
  $posts = bw_navi_posts( $posts, $atts );
  if ( !$posts_per_page ) {
    oik_navi_lazy_paginate_links( $atts );
  }  
  return( bw_ret() );  
}

   
/**
 * Help hook for [bw_navi] shortcode
 */   
function bw_navi__help( $shortcode="bw_navi" ) {
  return( __( "Simple paginated list" ) ); 
}

/**
 * Syntax hook for [bw_navi] shortcode
 * 
 */
function bw_navi__syntax( $shortcode="bw_navi" ) {
  oik_require( "shortcodes/oik-list.php" );
  $syntax = bw_list__syntax();
  $syntax['posts_per_page'] = bw_skv( get_option( "posts_per_page" ), "<i>integer</i>|.", "Number of posts per page. Default from Reading Settings." );
  return( $syntax );
}               

