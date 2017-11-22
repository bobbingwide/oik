<?php // (C) Copyright Bobbing Wide 2012-2017

/**
 * Returns the next selector for [bw_tabs]
 *
 * $inc  | action | return
 * ----  | ------ | ------
 * true  | $tabs_id++ | next value
 * false | nop    | current value
 * null  | 0    | current value	= 0
 * 
 * @param bool|null $inc - increment the id?
 * @return string - tab selector ID
 */
function bw_tabs_id( $inc=true ) { 
  static $tabs_id = 0;
	if ( $inc ) {
		$tabs_id++;
	} elseif ( null === $inc ) {
		$tabs_id = 0;
	}	
  return( "bw_tabs-$tabs_id" );
}

/**
 * Format a post as link within the same document
 */
function bw_format_tabs_list( $post, $atts, $selector ) {
  stag( "li" );
  $url = "#" . $selector . "-" . $post->ID;
  BW_::alink( NULL, $url, get_the_title( $post->ID ) );
  etag( "li" );
}

/**
 * Implement the [bw_tabs] shortcode to display posts or pages styled for jQuery tabs
 *
 * Basically we achieve what we can do manually using [bw_jq], [divs], lists and more divs for the each post's body
 *
 *
 
   [bw_jq selector="#tabs" method=tabs script=jquery-ui-tabs]
   [div id=tabs]
   <ul>
   <li><a href="#tab1">First</a></li>
   <li><a href="#tab2">Second</a></li>
   <li><a href="#tab3">Third</a></li>
   </ul>
   <div id="tab1">I am the first part of the tabs</div>
   <div id="tab2">I am the second part of the tabs</div>
   <div id="tab3">I am the third part of the tabs</div>
   [ediv]
 *
 */
function bw_tabs( $atts=null, $content=null, $tag=null ) {
  oik_require( "includes/bw_posts.php" );
  $posts = bw_get_posts( $atts );
  if ( $posts ) {
    oik_require( "shortcodes/oik-jquery.php" );
    $debug = bw_array_get( $atts, "debug", false );
    bw_jquery_enqueue_script( "jquery-ui-tabs", $debug );
    bw_jquery_enqueue_style( "jquery-ui-tabs" );
    $selector = bw_tabs_id();
    bw_jquery( "#$selector", "tabs" );
    $class = bw_array_get( $atts, "class", "bw_tabs" );
    sdiv( $class, $selector );
    $cp = bw_current_post_id();
    sul();
    foreach ( $posts as $post ) {
      bw_format_tabs_list( $post, $atts, $selector );
    }
    eul();
    foreach ( $posts as $post ) {
      bw_current_post_id( $post->ID );
      bw_format_tabs( $post, $atts, $selector );
    }
    ediv( $class );
    bw_current_post_id( $cp );
    bw_clear_processed_posts();
  }
  return( bw_ret() );
}

/** 
 * Format a tabs block - for jQuery UI tabs 1.9.2 or higher
 *
 * @param object $post - A post object
 * @param array $atts - Attributes array - passed from the shortcode
 * @param string $selector 
 *
 * Should we not do this using 
 *  apply_filters( "bw_format_tabs", $post, $atts );
 *  or even do_action( "bw_format_tabs", ... ); ?
 */
function bw_format_tabs( $post, $atts, $selector ) {
  $atts['title'] = get_the_title( $post->ID );
  $thumbnail = bw_thumbnail( $post->ID, $atts );
  $id = $selector . '-' . $post->ID; 
  sdiv( "group", $id );
    if ( $thumbnail ) {
      bw_format_thumbnail( $thumbnail, $post, $atts );
    }   
    e( bw_excerpt( $post ) );
    bw_format_read_more( $post, $atts );
    sediv( "cleared" );
  ediv();
}

function bw_tabs__syntax() {
  return( _sc_posts() );
}

/**
 * Example for [bw_tabs] shortcode
 */
function bw_tabs__example( $shortcode="bw_tabs" ) {
  $text = __( "Example: Display the two most recent pages.", "oik" );
  $example = "numberposts=2 post_type=page orderby=date order=DESC post_parent=0";
  bw_invoke_shortcode( $shortcode, $example, $text );
}

function bw_tabs__snippet( $shortcode="bw_tabs" ) {
  e( sprintf( __( 'Snippet not produced for this shortcode: %1$s', "oik" ), $shortcode ) );
}
