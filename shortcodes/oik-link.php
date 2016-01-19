<?php // (C) Copyright Bobbing Wide 2013-2015


/**
 * Performs the reverse of ltrim(), up to a point
 *
 * Prepends the specified $char if it's not already there
 * 
 * @param string $path - the string to be checked
 * @param char $char - the character to prepend if not already present
 * @return string - the string with the character prepended if required
 */
function unltrim( $path, $char='/' ) {
  //bw_backtrace();
  //bw_trace2();
  if ( $path[0] != $char ) {
    $path = $char . $path;
  }
  return( $path );
}

/**
 * Simulate http_build_url 
 *
 * but we don't bother with adding port, user or pass
 * since this is really not expected? Or is it?
 * Does set_url_scheme cater for this?
 * or perhaps having it in the "domain" or "siteurl" ?
 * 
 * @param array $parts - URL components
 * @return string - URL 
 */ 
function bw_build_url( $parts ) {
  bw_trace2();
  $newurl = $parts['scheme'];
  $newurl .= $parts['host'];
  if ( !empty( $parts['path'] ) ) {
    $newurl .= unltrim( $parts['path'] );
  }  
  if ( isset( $parts['query'] ) ) {
    $newurl .= "?";
    $newurl .= $parts['query'];
  }
  if ( isset( $parts['fragment'] ) ) {  
    $newurl .= "/#";
    $newurl .= $parts['fragment'];
  }
  return( $newurl );

}

/**
 * Return the domain for the site
 *
 * We obtain this either from the oik settings field "domain"
 * OR WordPress "siteurl" setting.
 *  
 * @TODO Should we used network_site_url() to obtain this?
 * @TODO When we get siteurl we need to remove the scheme part.
 * We probably need to do this with domain as well... belt and braces
 * 
 * @return string - the domain for the site
 */
function bw_get_domain() {
  $domain = bw_get_option( "domain" );
  if ( !$domain ) { 
    $domain = get_option( "siteurl" );
  }
  return( $domain );
}

/**
 * See if this is a permalink 
 *
 * If so, we need to 
 * - ensure the domain is set
 * - set the text label for the link ( add a new entry to the $parts array )
 *  
 */  
function bw_check_permalink( $parts, $path0, $path1 ) {
  global $wp_rewrite;
  //bw_trace2( $wp_rewrite );
  
  $rewrite = $wp_rewrite->wp_rewrite_rules();
  $_SERVER['PATH_INFO'] =  $parts['path'];
  //unset( $_SERVER['REQUEST_URI'] );
  
  
  $bwWP = new WP( $parts['path'] );
  $bwWP->parse_request();
  
  bw_trace2( $bwWP, "bwWP" );
  return( $parts );
}

/**
 * See if the domain is required
 * 
 */

/**
 * Check if this is a valid domain
 *
 * Note: This function can be very slow. Up to 10 seconds
 * We avoid doing it at all costs if there is a suitable alternative.
 * 
 * Could it actually be quicker to do a get request?
 
 * @TODO See how the the broken link checker works?
 * 
 * @param string $domain - a potential domain name, could be null
 * @return string domain if valid else null
 */  
function bw_check_domain( $domain ) {
  // $recordexists = dns_check_record( $domain, "ANY");
  bw_trace2();
  if ( $domain ) {
    $recordexists = checkdnsrr( $domain, "ANY" );
    if ( !$recordexists ) { 
      $domain = false;
    }
  }  
  bw_trace2( $recordexists, "$domain: $recordexists" );
  return( $domain );
}

/**
 * See if domain required?
 *
 * This is now a lot simpler. If it starts with '/' then path[0] will be null
 * So we use this as the domain ( host )
 * otherwise... we treat as a link within the current site
 * 
 * 
 * path[0]   path[1]   e.g.                Processing
 * --------  --------  ----------          ----------------------------------------------
 * set       set       post_type/etcetera
 * set       null      post_type           Is this a permalink? 
 * null      set       /somewhere
 * null      null      /                   domain required. They just entered '/' - so domain required. A bit of a silly link? No; it depends on what's in query/fragment
 * 
 * @param array $parts - the URL's component parts
 * @param array $paths - an array of 2 paths
 * @return array part -
 */ 
function bw_see_if_domain_required( $parts, $path ) {
  $path0 = bw_array_get( $path, 0, null );
  $path1 = bw_array_get( $path, 1, null );
  if ( $path0 ) {
    //$parts = bw_check_permalink( $parts, $path0, $path1 );
    $parts['host'] = $path0;
    $parts['path'] = $path1;
    if ( $path1 ) {
      // we don't yet know if we need to set the host 
    } else {
      // we still don't know
    }
  } else { 
    $parts['host'] = bw_get_domain();
    if ( $path1 ) {
      //
    } else {
      //
    }
  } 
  return( $parts );
}

/**
 * Check if the path is a path or host
 *
 * The URL has been parsed into the parts array but we don't have a host.
 * Perhaps we can use the path part as the host... or it could be something else
 * 
 * @TODO can we implement [bw_link "?=search_string"] using this?
 * @TODO should this be a filter? 
 * 
 * @param array $parts
 * @return array - updated parts array
 */
function bw_host_or_path( $parts ) {
  $path = bw_array_get( $parts, "path", null );
  if ( $path ) {
    $paths = explode( "/", $path, 2 );
    $parts = bw_see_if_domain_required( $parts, $paths );
    if ( !$parts['host'] ) {
      $domain = bw_get_domain();
      // don't change the path but set the domain
      $parts['host'] = $domain;
    } else {
      // It is a domain! Set the host and the remainder of the path
      // we should already have done this
      //$parts['host'] = $domain;
      $parts['path'] = bw_array_get( $paths, 1, null );
    } 
  } else {
    // No path set either! 
    $domain = null;
    $parts['host'] = bw_get_domain();
    // leave path as is... there may be a query string?
  }
  return( $parts );
} 

/**
 * Construct a suitable URL
 
 scheme - e.g. http
 host
 port
 user
 pass
 path
 query - after the question mark ?
 fragment - after the hashmark #
 *
 * We've been given what may be an URL
 * what can we do to make the link really easy to use?
 
   [bw_link 18356] &#8211; link to post 18356
   [bw_link child] &#8211; current logic links to the child page named child

   [bw_link http://qw/wordpress/oik_todo/bw_link-enhancements/]

   [bw_link /somewhere] &#8211; link to somewhere in the current bw_domain. Can we check the permalink exists?

   [bw_link post_type] &#8211; link to the Post type archive

   [bw_link example.com] &#8211; link to example.com
   [bw_link example.com/path - link to example.com path

   [bw_link child] &#8211; current logic links to the child page named child

   [bw_link s://p ] &#8211; multisite &#8211; link to a multisite ID
 * 
 * @param string $url - an URL - or part thereof
 * @param array $atts - shortcode parameters 
 * @return string - the newly constructed url
 * 
 */
function bw_link_url( $url, $atts ) {
  $newurl = $url;
  $parts = parse_url( $url );
  bw_trace2( $parts, "parts" );
  $scheme = bw_array_get( $parts, "scheme", null );
  if ( !$scheme ) {
    $parts['scheme'] = "http://" ;
    $parts['host'] = bw_array_get( $parts, "host", null );
    
    if ( $parts['host'] ) {
      // No need to check if this is a domain, just use it.
      // OR do we?
      //gobang();
    } else {
      // No host... 
      // check to see if the path is a domain?
      $parts = bw_host_or_path( $parts );
      
      //$newurl = set_url_scheme( "$domain/$url" );
      
    }
    $newurl = bw_build_url( $parts );
  } else {
    // Let whatever they've typed be used
  } 
  //$newurl = set_url_scheme( $newurl ); 
  //e("bw_link_url: $newurl" );
  return( $newurl );
}


/**
 * Implement [bw_link] shortcode for a link to a post or an external URL
 * 
 * If the id or first unnamed parameter is numeric we treat this as a post ID
 * else we interpret the given parameter to find what could be a suitable URL.
 * If nothing is specified we simply link to ourselves.
 * 
 * @param array $atts - array of shortcode parameters
 * @param string $content - 
 * @param string $tag -
 * @return string - the expanded shortcode
 */
function bw_link( $atts=null, $content=null, $tag=null ) {
  $id = bw_array_get_from( $atts, "id,0", null );
  $class = bw_array_get( $atts, "class", "bw_link" );
  $text = bw_array_get( $atts, "text", null );
  $title = bw_array_get( $atts, "title", null );
  if ( $id && is_numeric( $id ) ) {
    $url = get_permalink( $id );
    if ( !$text ) {
      $text = get_the_title( $id );
    }
  } else { 
    $url = bw_array_get_from( $atts, "href,url,src,link", $id );
    if ( !$url ) {
      $url = get_permalink();
      $text = get_the_title();
    }
    if ( !$text ) {
      $text = $url;
    }
    $url = bw_link_url( $url, $atts ); 
  }
  alink( $class, $url, $text, $title );
  return( bw_ret()); 
}

/**
 * Help hook for [bw_link] shortcode 
 */
function bw_link__help( $shortcode="bw_link" ) {
  return( "Display a link to a post." );
}

/**
 * Syntax hook for [bw_link] shortcode
 */
function bw_link__syntax( $shortcode="bw_link" ) {
  $syntax = array( "id" => bw_skv( "id", "<i>ID</i>|<i>URL</i>", "ID of the post to link to or external URL" )
                 , "text" => bw_skv( "<i>post title</i>", "<i>text</i>", "Text for the link" )
                 , "title" => bw_skv( "<i>post title</i>", "<i>tool tip string</i>", "Tool tip text" )
                 , "href,url,src,link" => bw_skv( null, "<i>href</i>", "URL to link to" )
                 );
  $syntax += _sc_classes();
  return( $syntax );
}

/**
 * Return a post ID for bw_link example
 * 
 * If the global post ID is not set try for a recently published post ID#
 *
 */  
function _bw_get_an_id() {
  oik_require( "includes/bw_posts.inc" );
  $id = bw_global_post_id();
  if ( !$id ) {
    $posts = wp_get_recent_posts( array( "numberposts" => 1, "post_status" => "published" ) );
    $post = bw_array_get( $posts, 0, null );
    if ( $post )
      $id = $post['ID'];
      
    // bw_trace2( $posts );  
  } 
  return( $id );
}

/**
 * Example hook for [bw_link] shortcode
 */
function bw_link__example( $shortcode="bw_link" ) {
  $id = _bw_get_an_id();
  $text = "Example: Display a link to post with ID=$id";
  $example = $id;
  bw_invoke_shortcode( $shortcode, $example, $text );
} 

/**
 * Snippet hook for [bw_link] shortcode
 */                 
function bw_link__snippet( $shortcode="bw_link" ) {
  _sc__snippet( $shortcode, _bw_get_an_id() );
}                   
