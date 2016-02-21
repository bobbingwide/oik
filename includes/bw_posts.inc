<?php
/*
    Copyright 2011-2016 Bobbing Wide (email : herb@bobbingwide.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2,
    as published by the Free Software Foundation.

    You may NOT assume that you can use any other version of the GPL.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    The license for this software can likely be found here:
    http://www.gnu.org/licenses/gpl-2.0.html

*/
oik_require( "includes/bw_images.inc" );

// bw_global_post_id moved to bobbfunc.inc 2013/07/05

/** 
 * Return the global post's post_type
 * @return string/NULL - the global post's post_type or NULL
 */
if ( !function_exists( "bw_global_post_type" ) ) { 
function bw_global_post_type() {
  if ( isset( $GLOBALS['post'] )) {
    $post_type = $GLOBALS['post']->post_type;
  } else {  
    $post_type = NULL;
  }  
  return( $post_type ) ;
}
}

/** 
 * Return the value of the GLOBAL post's excerpt field setting it to the new value
 * 
 * Use this function in pairs to save and then reset the global value.
 
 * @param string $excerpt - the new value that we want to set
 * @return string the previously stored value
 * 
 * 
 */
function bw_global_excerpt( $excerpt=null ) {
  if ( isset( $GLOBALS['post'] )) {
    $excerpt_to_return = $GLOBALS['post']->post_excerpt;
    $GLOBALS['post']->post_excerpt = $excerpt;
  } else {
    $excerpt_to_return = null;
  }  
  return( $excerpt_to_return );   
}

/**
 * Determine whether or not to process this post
 *
 * @param integer $id - the post ID
 * @return bool - true if the post has not been processed. false otherwise
 */
function bw_process_this_post( $id ) {
  global $processed_posts;
  $processed = bw_array_get( $processed_posts, $id, false );
  if ( !$processed ) {
    $processed_posts[$id] = $id ;
  }
  bw_trace2( $processed_posts, "processed posts", true, BW_TRACE_DEBUG );
  return( !$processed );
}

/**
 * Clear the array of processed posts
 * 
 * 
 */
function bw_clear_processed_posts() {
  global $processed_posts;
  $processed_posts = array();
  bw_trace2( $processed_posts, "cleared", false, BW_TRACE_DEBUG );
}

/**
 * Expand shortcodes in the content
 * 
 * Originally entitled 'Safely invoke the "the_content" filter'
 * 
 * We only really want shortcode expansion to take place against the content
 * So why not use "get_the_excerpt" instead?
 * 
 * It's probably because some shortcodes don't work for that filter. 
 * OR is that a load of bolleaux?
 *
 * Why not just invoke do_shortcode() ?
 *  
 */
function bw_get_the_content( $content ) { 
  $doit = strpos( $content, "[" );
  if ( $doit !== false ) {
    bw_push(); 
    //$content = apply_filters( "the_content", $content );
    //$content = apply_filters( "get_the_excerpt", $content );
    $content = do_shortcode( $content );
    bw_pop();
  }  
  return( $content );
}

/**
 * Expands shortcodes in the excerpt
 * 
 * Originally entitles, 'Safely invoke the "get_the_excerpt" filter'
 * 
 * The reason we apply filters here is to expand shortcodes.
 * And we want to expand only those shortcodes that are allowed to expand in excerpts.
 * 
 * There is no need to do this if there aren't any shortcodes. So we do a simple check for the starting character '['
 *
 * Note: We don't want other filters to try doing things based on the global post, so we use bw_global_excerpt() to make it look as if there isn't an excerpt.
 * And because we're expanding shortcodes that may use bw_echo() we need to make use of bw_push() and bw_pop().
 *
 */
function bw_get_the_excerpt( $excerpt ) { 
  $doit = strpos( $excerpt, "[" );
  if ( $doit !== false ) {
    bw_push(); 
    $saved_excerpt = bw_global_excerpt( null ); 
    //$excerpt = apply_filters( "get_the_excerpt", $excerpt );
    $excerpt = do_shortcode( $excerpt );
    // bw_trace2( $excerpt, "excerpt after", false );
    bw_global_excerpt( $saved_excerpt );
    bw_pop();
  }  
  return( $excerpt );
}

/**
 * Set and retrieve the custom "read more" text
 *
 * Whenever we call bw_excerpt() call bw_more_text() to set the custom "read more" text
 * The custom "read more" text can then be accessed using the same function when creating the read more link
 * 
 * @param string $more_text - the new value for the read more text
 * @param array $atts - contains the default "read_more" text. This is null when setting and not null when retrieving 
 * @return string the current value of $bw_more_text or the default value when null
 *  
 */
function bw_more_text( $more_text=null, $atts=null ) {   
  static $bw_more_text = null;
  if ( ( $atts !== null ) && !$bw_more_text ) { 
    $bw_more = bw_array_get_dcb( $atts, "read_more", "read more" );
  } else {
    $bw_more = $bw_more_text;
  }
  $bw_more_text = $more_text; 
  bw_trace2( $bw_more, "bw_more", true, BW_TRACE_DEBUG );
  return( $bw_more );
}

/**
 * Return the excerpt from the $post 
 * @param  post $post- post from which to extract the excerpt
 * @return string $excerpt the excerpt from the post
 *
 * Note: Most of the routines that implement the "get_the_excerpt" filter assume we're dealing with the excerpt of the main post
 * Here we are getting the excerpt of something else. We only want shortcodes to be expanded. See bw_get_the_excerpt();
 * In order to achieve this we have to make sure that the global post's excerpt is not set
 */
function bw_excerpt( $post ) {
  //bw_current_post_id( $post->ID );
  if ( bw_process_this_post( $post->ID ) ) {
    if ( empty( $post->post_excerpt ) ) {
      $excerpt = $post->post_content;
    } else {
      $excerpt = $post->post_excerpt;
    }
    $content = get_extended( $excerpt ); 
    $excerpt = $content['main'];
    bw_more_text( $content['more_text'] );
    
    $excerpt = bw_get_the_excerpt( $excerpt );
  } else {
    $excerpt = "post already processed: " . $post->ID;
  }
  // bw_current_post_id();  
  return( $excerpt );
}

/**
 * Format the "post" - basic first version
 *
 * Format the 'post' in a block or div with title, image with link, excerpt and read more button
 *
 * @param object $post - A post object
 * @param array $atts - Attributes array - passed from the shortcode
 * 
 *
 */
function bw_format_post( $post, $atts ) {
  bw_trace( $post, __FUNCTION__, __LINE__, __FILE__, "post", BW_TRACE_DEBUG );
  $atts['title'] = get_the_title( $post->ID );
  $thumbnail = bw_thumbnail( $post->ID, $atts );
  $in_block = bw_validate_torf( bw_array_get( $atts, "block", TRUE ));
  if ( $in_block ) {
    oik_require( "shortcodes/oik-blocks.php" );
    e( bw_block( $atts ));
    if ( $thumbnail ) {
      bw_format_thumbnail( $thumbnail, $post, $atts );
    }  
  } else {
    $class = bw_array_get( $atts, "class", "" );
    sdiv( $class );
    if ( $thumbnail ) {
      bw_format_thumbnail( $thumbnail, $post, $atts );
    }
    span( "title" );
    strong( $atts['title'] );
    epan();
    br();
  }
  e( bw_excerpt( $post ) );
  bw_format_read_more( $post, $atts );  
  if ( $in_block )
    e( bw_eblock() ); 
  else {  
    sediv( "cleared" );
    ediv();  
  }    
}

/** 
 * Produce a read_more link as required ( block )
 *
 * If the read_more parameter is blank then we don't include the read more link
 * otherwise, we do using the value given, 
 * allowing each post's excerpt to have overridden the value through the <!--more custom_read_more --> tag
 * 
 * @param post $post - the actual post from which we extract the ID 
 * @param array $atts - shortcode parameters - which may contain read_more=
 */
function bw_format_read_more( $post, $atts ) {
  $read_more = bw_array_get( $atts, "read_more", true );
  if ( trim( $read_more ) !== '') {
    sp();
    $read_more = bw_more_text( null, $atts );
    art_button( get_permalink( $post->ID ), $read_more, $read_more ); 
    ep(); 
  }  
}

/** 
 * Produce a more link as required ( inline )
 *
 * If the read_more parameter is blank then we don't include the read more link
 * otherwise, we do using the value given, 
 * allowing each post's excerpt to have overridden the value through the <!--more custom_read_more --> tag
 * 
 * @param post $post - the actual post from which we extract the ID 
 * @param array $atts - shortcode parameters - which may contain read_more=
 */
function bw_format_more( $post, $atts ) {
  $read_more = bw_array_get( $atts, "read_more", true );
  if ( trim( $read_more ) !== '') {
    //sp();
    $read_more = bw_more_text( null, $atts );
    alink( "bw_more", get_permalink( $post->ID ), $read_more, $read_more ); 
    //ep(); 
  }  
}

/**
 * Format the thumbnail when displayed in a block
 *
 * This code was originally written to display the thumbnail image left aligned in the block
 * so that the text would go to the right
 * There are problems with IE and certain themes where the alignleft styling 
 * seems to override attempts to make the image scalable.
 * So, from 2013/05/13, I'm removing the "alignleft" class, but leaving "avatar"
 * This fixed it for TwentyTwelve but not for my Artisteer theme! 
 * So I removed avatar as well.
 * This has resolved some problems with oik-plugins but it may have adverse effects on other sites.
 * 
 */
function bw_format_thumbnail( $thumbnail, $post, $atts ) {
  sdiv( "bw_thumbnail" );
  bw_link_thumbnail( $thumbnail, $post->ID, $atts );
  ediv();
}

/**
 * Format the "post" - in a simple list item list
 *
 * If there is a thumbnail parameter we include the image as well! 
 * We originally expected the thumbnail parameter to be a small number e.g. thumbnail=80
 * but now we support any size since the list may be displayed with the Flexslider jQuery
 * 
 * @param post $post - the post object to be displayed
 * @param array $atts - shortcode parameters
 */
function bw_format_list( $post, $atts ) {
  // bw_trace( $post, __FUNCTION__, __LINE__, __FILE__, "post" );
  $atts['title'] = get_the_title( $post->ID );
  $attachment = ( $post->post_type == "attachment" ) ;
  $thumbnail = bw_thumbnail( $post->ID, $atts, $attachment );
  if ( $thumbnail ) {
    $title = $thumbnail . $atts['title'];
  } else {
    $title = $atts['title'];
  }
  if ( !$title ) {
    $title = __( "Post: " ) . $post->ID;
  } 
  stag( 'li' );
  alink( NULL, get_permalink( $post->ID ), $title );
  etag( 'li' );
}

/**
 * Get the list of categories for this "post" as a string of slugs separated by commas
 * 
 * @return string comma separated list of categories for the global post 
 */
function bw_get_categories() {
  global $post;
  bw_trace2( $post, "global post", false, BW_TRACE_DEBUG );
  //  bw_backtrace();
  if ( $post ) {
    $categories = get_the_category( $post->ID );
    $cats = '';
    foreach ( $categories as $category ) {
      $cats .= $category->slug;
      $cats .= ' ';
    }  
    $cats = trim( $cats );
    $cats = str_replace( ' ',',', $cats );
  } else {
    $cats = null;
  }    
  return bw_trace2( $cats, null, false, BW_TRACE_DEBUG );
}

/**
 * Wrapper to get_posts() 
 * 
 * When no parameters are passed processing should depend upon the context
 * e.g for a 'page' it should list the child pages
 *     for a 'post' it should show related posts in the same category as the current post
 * 
 * Nos | $atts['post_type']  | $post->post_type | Default processing
 * --- | ---------  | ---------  | -----------------------------------
 * 1   | -          | page       | list child pages - first level only
 * 2   | -          | post       | list related posts - same categories
 * 3   | -          | custom     | none
 * 4   | page       |  page      |  as 1.
 * 5   | page       | post       | ?
 * 6   | page       | custom     | ?
 * 7   | post       | page       | ?
 * 8   | post       | post       | as 2.
 * 9   | post       | custom     | ?
 * 10-12 | custom | any          | ?
 *
 * As you can see from the table above the default behaviour for listing posts on pages and vice-versa is not (yet) defined
 * 
 *
 * @param array $atts - shortcode parameters
 * @return array - posts
 */
function bw_get_posts( $atts=null ) {
  // Copy the atts from the shortcode to create the array for the query
  // removing the class and title parameter that gets passed to bw_block()    **?** 2013/07/01 - is this still done?
  $attr = $atts;
  bw_trace( $atts, __FUNCTION__, __LINE__, __FILE__, "atts", BW_TRACE_DEBUG );
  //bw_trace( $attr, __FUNCTION__, __LINE__, __FILE__, "attr" );    
  
  /* Set default values if not already set */
  $attr['post_type'] = bw_array_get_dcb( $attr, 'post_type', NULL, "bw_global_post_type"  );
  $post_types = bw_as_array( $attr['post_type'] );
  
  /* Allow specific post IDs to be defined in a variety of ways. */
  $id = bw_array_get_from( $attr, array( "id", "post__in", "p", "page_id" ),  null );
  
  if ( !$id ) {
    if ( count( $post_types ) > 1 ) {
      $attr['post_type'] = $post_types;
    } else {
    
      // Only default post_parent for post_type of 'page' 
      // This allows [bw_pages] to be used without parameters on a page
      // and to be used to list 'page's from other post types.
      //
      // Note: Pass a non-numeric value of post_parent to cause this parameter to not be used by get_posts()
      
      // 
      if ( $attr['post_type'] == 'page' || $attr['post_type'] == 'attachment' ) {
        $attr['post_parent'] = bw_array_get_dcb( $attr, "post_parent", NULL, "bw_current_post_id" );
      }
      
      /** If we're listing posts and the category is not specified then determine the category from the global post
          This finds "related" posts. 
          Not sure how to find ANY post if that's what we want **?** 2013/06/21
      */ 
      if ( $attr['post_type'] == 'post' ) {
        $attr['category_name'] = bw_array_get( $attr, "category_name", NULL );
        $attr['category'] = bw_array_get( $attr, "category", null );
        if ( NULL == $attr['category_name'] && null == $attr['category'] ) {
          $categories = bw_get_categories();
          if ( $categories ) {
            $attr['category_name'] = $categories;
          } else {
            // What do we do now? 
          }    
        }  
      }
            
      $attr['numberposts'] = bw_array_get( $attr, "numberposts", -1 );
      $attr['orderby'] = bw_array_get( $attr, "orderby", "title" );
      $attr['order'] = bw_array_get( $attr, "order", "ASC" );
    }
  } else {
    /* Allow the shortcode to specify a set of post IDs to load. id=1,2,3,4 or id="1 2 3 4" should load posts IDs 1, 2, 3 and 4
     * Note: This currently overrides the "post__in" parameter 
     * If $id was already an array then we must use 'post__in'
    */
    $ids = bw_as_array( $id );
    if ( is_array( $id ) || count( $ids ) > 1 ) {
      $attr['post__in'] = $ids; 
      $attr['orderby'] = bw_array_get( $attr, "orderby", "post__in" );
      unset( $attr['p'] );
    } else { 
      $attr['p'] = $id; 
      $attr['page_id'] = $id; 
      // Normally we don't need to worry about orderby or numberposts when we're only getting one post
    }  
    
    if ( count( $post_types ) > 1 ) {
      $attr['post_type'] = $post_types;
    }
  }
  // Regardless of the post type, exclude the current post, if exclude= parameter NOT specified
  // If you want to retrieve the current post use exclude=-1
  //
  // Note: This supports multiple IDs, comma separated 
  $attr['exclude'] = bw_array_get_dcb( $attr, "exclude", NULL, "bw_global_post_id" );
  
  // set suppress_filters to false when global bw_filters is set
  global $bw_filter;
  if ( isset( $bw_filter ) ) {
    $attr['suppress_filters'] = false;
  }
  
  bw_trace( $attr, __FUNCTION__, __LINE__, __FILE__, "attr", BW_TRACE_INFO );
  
  $bw_query = bw_array_get( $atts, "bw_query", null );
  if ( $bw_query ) {
    $posts = _bw_get_posts( $attr );
  } else {
    $posts = get_posts( $attr );
  }   
  bw_trace( $posts, __FUNCTION__, __LINE__, __FILE__, "posts", BW_TRACE_INFO );
  return( $posts );
}

/**
 * Wrapper to WP_query::query()
 * 
 * Sometimes we need to know more about the results of the query than just the posts
 * 
 * We need to find the total number of posts in order to work out how many pages there are.
 * This information is available from WP_Query when displaying paged content.
 *
 * This function implements most of get_posts() but it returns the WP_Query object that was used. 
 *
 * @param array $args - parameters to get_posts or WP_query::query()
 * @return array $posts - the posts
 * 
 */
function _bw_get_posts( $args=null ) {
 $defaults = array(
    'numberposts' => 5, 
		'category' => 0, 'orderby' => 'date',
    'order' => 'DESC', 'include' => array(),
    'exclude' => array(), 'meta_key' => '',
    'meta_value' =>'', 'post_type' => 'post',
    'suppress_filters' => true
  );

  $r = wp_parse_args( $args, $defaults );
  if ( empty( $r['post_status'] ) )
    $r['post_status'] = ( 'attachment' == $r['post_type'] ) ? 'inherit' : 'publish';
  if ( ! empty($r['numberposts']) && empty($r['posts_per_page']) )
    $r['posts_per_page'] = $r['numberposts'];
  if ( ! empty($r['category']) )
    $r['cat'] = $r['category'];
  if ( ! empty($r['include']) ) {
    $incposts = wp_parse_id_list( $r['include'] );
    $r['posts_per_page'] = count($incposts);  // only the number of posts included
    $r['post__in'] = $incposts;
  } elseif ( ! empty($r['exclude']) )
    $r['post__not_in'] = wp_parse_id_list( $r['exclude'] );

  $r['ignore_sticky_posts'] = true;
  
  // no_found_rows means don't count the number of found rows.
  // We need to set this to true most of the time - as we either get the lot or limit ourselves to numberposts
  // AND set it to false when we do want to know how many rows there are when paging.
  // no_found_rows = true - don't count the found rows
  // no_found_rows = false - do count the found rows
  // 
  if ( $r['posts_per_page'] ) {
    $r['no_found_rows'] = bw_array_get( $args, "no_found_rows", false );
  } else {
    $r['no_found_rows'] = bw_array_get( $args, "no_found_rows", true ); 
  }
  
  $get_posts = bw_array_get( $args, "bw_query", null );
  $posts = $get_posts->query( $r );
  bw_trace2( $get_posts->post_count, "new WP_Query post_count", false, BW_TRACE_INFO );
  bw_trace2( $get_posts, "new WP_Query", false, BW_TRACE_INFO );
  bw_trace2( $get_posts->found_posts, "new WP_Query found_posts", false, BW_TRACE_INFO );
  bw_trace2( $get_posts->max_num_pages, "new WP_Query max_num_pages", false, BW_TRACE_INFO );
  //$result = array( $posts
  //               , $get_posts
  //               );
  return( $posts );
}

/**
 * Get the post / custom post type identified by the ID and post type
 * 
 * Note: If we know the ID then why not just call get_post()? **?**  2012/11/03
 * Changed from is_int() to is_numeric() but not yet done above 2012/12/03
 *
 * @param integer $post_id - the ID of the post to retrieve
 * @param string $post_type - the post type of the post ( could be "any" )
 * @param array $atts - shortcode parameters
 * @return post - a single post
 */
function bw_get_post( $post_id, $post_type, $atts=null ) {
  if ( null == $atts ) {
    $atts = array();
  }  
  if ( is_numeric( $post_id ) ) { 
    $atts['include'] = $post_id;
  } else {
    $atts['name'] = $post_id;  
  }    
  $atts['numberposts'] = 1;
  $atts['post_type'] = $post_type;
  $posts = get_posts( $atts ); 
  if ( $posts ) {
    $post = bw_array_get( $posts, 0, null );
  } else {
    // gobang() or doingitwrong or something
    $post = null;
  }  
  bw_trace2( $post, "post", true, BW_TRACE_INFO );
  return( $post );
}

/**
 * Load posts by meta_key array 
 *
 * Note: If there is only one value in the value_array we try to improve performance
 * by simplifying the request to use meta_key rather than meta_query
 *
 * @param string $post_type - the post type required
 * @param string $meta_key - the name of the meta value to match
 * @param array $value_array - the set of values to load. There may be only one 
 * @return array $posts - the array of posts returned
 */
function bw_get_by_metakey_array( $post_type, $meta_key, $value_array ) { 
	$atts = array();
	$atts['post_type'] = $post_type;
	$atts['numberposts'] = -1; 
	if ( is_array( $value_array ) ) {
		if ( count( $value_array ) > 1 ) {
			$meta_query = array();
			$meta_query[] = array( "key" => $meta_key
													 , "value" => $value_array 
													 , "compare" => "IN"  
													 );
			$atts['meta_query'] = $meta_query;
		} else {
			$atts['meta_key'] = $meta_key;
			$atts['meta_value'] = $value_array[0];
		}
	} else { 
		$atts['meta_key'] = $meta_key;
		$atts['meta_value'] = $value_array;
		/*
		 * @TODO Is it safe to limit ourselves to a single API? 
		 * Or should this be controlled by the application?
		 */
		//$atts['numberposts'] = 1; 
	}
	$atts['exclude'] = -1;
	$posts = bw_get_posts( $atts ); 
	return( $posts );
} 

