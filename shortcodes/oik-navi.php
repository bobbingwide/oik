<?php // (C) Copyright Bobbing Wide 2014-2019, 2023, 2024

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
 * If they've chosen another link the value is not set.
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
  /* Some requests from bots contain trailing slashes either as '/' or '%2F'
     The paged value needs to be numeric.
  */
  $page = trim( $page,'/' );
  $page = (int) $page;
  /* Cater for a non numeric value for page.
  */
  if ( !$page ) {
	  $page = 1;
  }
  return $page;
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
	//	bw_trace2();
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
 * - since each shortcode produces its own shortcode ID ( bwscid$id ), 
 * - then the URL will slowly build up the pagination information for each shortcode. 
 * - This avoids the need for cookies and/or jQuery to remember the page state of each shortcode.
 * 
 * Theoretically, this solution works on archive pages.
 * 
 * Things can go awry if the displayed content changes, such that a nested shortcode
 * with pagination suddenly appears. That's some complicated page we'd rather not attempt to deal with right now.
 *
 * Note: In WordPress 4.1.1 a fix was introduced that caused a new problem when you return to the first page.
 * The workaround below is to remove the special query arg ( bwscid$id ) from $_SERVER['REQUEST_URI']
 * 
 * @param integer $id - the 'unique' shortcode ID for this instance of a paged shortcode
 * @param integer $page - the current page number ( starts from 1 )
 * @param integer $pages - the total number of pages
 */ 
function bw_navi_paginate_links( $id, $page, $pages ) {
  //bw_trace2(  $_SERVER['REQUEST_URI'], "request_URI" );
	$saved_request_uri = $_SERVER['REQUEST_URI'];
  $string = remove_query_arg( "bwscid$id" );
  $_SERVER['REQUEST_URI'] = $string; 
  //bw_trace2( $string, "removed request_URI", false );
	$base = add_query_arg( "bwscid$id", "%_%" );
	
  //bw_trace2( $base, "base", false );
  $base = esc_url_raw( $base );
  //bw_trace2( $base, "base", false );
  $format = "%#%";
  $args = array( "base" => $base
               , "format" => $format
               , "total" => $pages
               , "current" => $page
               , "before_page_number" => "["
               , "after_page_number" => "]"
               , "add_args" => false
	            , "type" => "plain"
               );
  // We don't need to worry about these yet             
  //  'show_all' => false,
  //  'prev_next' => true,
  //  'prev_text' => __('� Previous'),
  //  'next_text' => __('Next �'),
  //  'end_size' => 1,
  //  'mid_size' => 2,
  //  'type' => 'plain',
  //  'add_args' => false, // array of query args to add
  //  'add_fragment' => '',
  $links = paginate_links( $args );
  $links = bw_navi_add_rel( $links);
  //bw_trace2( $args, "args" );
  //bw_trace2( $links, "links", false );
	sdiv( "page-numbers pagination");
    e( $links );
    ediv();
	$_SERVER['REQUEST_URI'] = $saved_request_uri;
}

/**
 * Adds rel attr to each link.
 *
 * @param $links
 * @param $rel string default noindex.
 *
 * @return array|string|string[]
 */
function bw_navi_add_rel( $links, $rel="noindex" ) {
	if ( $links ) {
		$links=str_replace( '<a class', '<a rel=' . $rel . ' class', $links );
	}
	return $links;
}

/**
 * Display posts using the [bw_navi] shortcode
 *  
 * 
 * @TODO: If we want an ordered list then we should add the start number to the list.
 * So we need to know the page number and posts_per_page
 *
 * @param array $posts
 * @param array $atts
 * @param integer $start
 * 
 */
function bw_navi_posts( $posts=null, $atts=null, $start=null ) {
  oik_require( "shortcodes/oik-list.php" );
	if ( !$start ) {
		$start = bw_navi_start_from_atts( $atts );
	}
  $ol = bw_sl( $atts, $start );
  foreach ( $posts as $post ) {
    bw_format_list( $post, $atts );
  }
  bw_el( $ol );
  return( $posts );
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
      BW_::br( $prefix );
    }
    sdiv( "bw_s2eofn" );
    /* translators: %1 start page number, %2 end page number, %3 total page count */
    e( sprintf( __( '%1$s to %2$s of %3$s', 'oik') , $start, $end, $count ) );
    ediv();
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
  $ol = bw_sl( $atts, $start );
  for ( $i = $start; $i<= $end; $i++ ) {
    bw_list_id( $posts[$i] );
  }
  bw_el( $ol );
  bw_navi_paginate_links( $bwscid, $page, $pages ); 
} 

/**
 * Display "s to e of n" 
 * 
 * @param array $atts 
 * @return ID start number (defaults to 1 )
 */
function oik_navi_s2eofn_from_query( $atts ) {
  $bw_query = bw_array_get( $atts, "bw_query", null );
	$start = 1;
  bw_trace2( $bw_query, "bw_query", false, BW_TRACE_VERBOSE );
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
	return( $start );
}

/**
 * Return the start item for a paginated ordered list
 * 
 * @param array $atts
 * @return integer the start index
 */
function bw_navi_start_from_atts( $atts ) {
	$start = 1;
  $page = bw_array_get( $atts, "paged", 1 );
	if ( $page > 1 ) {
    $posts_per_page = bw_array_get( $atts, "posts_per_page", null );
		if ( $posts_per_page ) {
      $start = ( $page-1 ) * $posts_per_page;
			$start++;
		}
	} 
	return( $start );
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
function bw_navi( $atts=null, $content=null, $tag="bw_navi" ) {
    if ( oik_is_rest() ) {
        return null;
    }
    oik_require( "includes/oik-shortcodes.php");
	bw_push();
	$posts_per_page = bw_array_get( $atts, "posts_per_page", null );
	if ( !$posts_per_page ) {
		$atts['posts_per_page'] = get_option( "posts_per_page" ); 
		$atts = oik_navi_shortcode_atts( $atts );
	}
	$atts['numberposts'] = bw_array_get( $atts, "numberposts", -1 );
	$atts['thumbnail'] = bw_array_get( $atts, "thumbnail", "none" );
	
	$field = bw_array_get( $atts, "field", null );
	
	if ( $field ) {
		bw_navi_field( $field, $atts, $posts_per_page );
	} else {
		oik_require( "includes/bw_posts.php" );
		$posts = bw_get_posts( $atts ); 
		if ( !$posts_per_page ) {
			$start = oik_navi_s2eofn_from_query( $atts );
		} else {
			$start = null;
		}
		$posts = bw_navi_posts( $posts, $atts, $start );
		if ( !$posts_per_page ) {
			oik_navi_lazy_paginate_links( $atts );
		}	
	}
	
	$result = bw_ret();
	bw_pop();
	if ( defined('DOING_AJAX') && DOING_AJAX ) {
	} else {
		if ( !$field ) {
			$result = apply_filters( "oik_navi_result", $result, $atts, $content, $tag );  
		}
	}	
	return( $result );  
}
   
/**
 * Help hook for [bw_navi] shortcode
 */   
function bw_navi__help( $shortcode="bw_navi" ) {
  return( __( "Simple paginated list", "oik" ) ); 
}

/**
 * Syntax hook for [bw_navi] shortcode
 * 
 */
function bw_navi__syntax( $shortcode="bw_navi" ) {
  oik_require( "shortcodes/oik-list.php" );
  $syntax = bw_list__syntax();
  $syntax['posts_per_page'] = BW_::bw_skv( get_option( "posts_per_page" ), "<i>" . __( "integer", "oik" ) . "</i>|.", __( "Number of posts per page. Default from Reading Settings.", "oik" ) );
  return( $syntax );
}

/**
 * Paginate a multivalue field
 * 
 * Processing depends on the field type
 * Type     | Processing
 * -------- | ------------------------
 * textarea | treat each line as a separate entry
 * other    | handle multiple entries
 * 
 * 
 * @param string $field the name of the post_meta_field to paginate
 * @param array $atts shortcode parameters
 */																				
function bw_navi_field( $field, $atts, $posts_per_page ) {
	
	$content_array = bw_navi_fetch_field_content( $field, $atts );
	$bwscid = bw_get_shortcode_id( false );
	$page = bw_check_paged_shortcode( $bwscid );
	$count = count( $content_array ); 
	if ( $posts_per_page ) {  
		$pages = ceil( $count / $posts_per_page );
		$start = ( $page-1 ) * $posts_per_page;
		$end = min( $start + $posts_per_page, $count ) -1  ;                              
		bw_navi_s2eofn( $start, $end, $count );
		$content_array = array_slice( $content_array, $start, $posts_per_page  );
		bw_trace2( $content_array, "content_array" );
	}
	foreach ( $content_array as $content ) {
		if ( false === strpos( $content, "[" ) ) {
			e( $content );
		} else {
			e( bw_do_shortcode( $content ) ); 
		}
	}
	
	if ( $posts_per_page ) {
		bw_navi_paginate_links( $bwscid, $page, $pages ); 
	}
	
}

/**
 * Load the requested page for the field 
 * 
 * @param string $field the field name e.g. _oik_rq_hooks
 * @param array $atts parameters
 * @return array the selected page
 */
function bw_navi_fetch_field_content( $field, $atts ) {
	$id = bw_array_get( $atts, "id", bw_global_post_id() );
	$values = get_post_meta( $id, $field, false );
	bw_trace2( $values, "values" );
	$field_type = bw_query_field_type( $field );
	add_filter( "bw_navi_filter_textarea", "bw_navi_filter_textarea", 10, 3 );
	add_filter( "bw_navi_filter_sctextarea", "bw_navi_filter_textarea", 10, 3 );
	$content_array = apply_filters( "bw_navi_filter_{$field_type}", $values, $field, $field_type );
	return( $content_array );
}

/**
 * Filter multiple textarea field values
 *
 * @param array $values array of values to be exploded 
 * @param string $field field name
 * @param string $field_type field type. e.g. textarea, sctextarea
 * @return array exploded results
 */
function bw_navi_filter_textarea( $values, $field, $field_type ) {
	//bw_trace2();
	$result = array();
	foreach ( $values as $value ) {
		$lines = explode( "\n", $value );
		foreach ( $lines as $line ) {
			$result[] = $line;
		}
	}
	return( $result );
}