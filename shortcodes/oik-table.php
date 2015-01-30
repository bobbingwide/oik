<?php 
if ( defined( 'OIK_TABLE_SHORTCODES_INCLUDED' ) ) return;
define( 'OIK_TABLE_SHORTCODES_INCLUDED', true );
/*

    Copyright 2012-2014 Bobbing Wide (email : herb@bobbingwide.com )

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


oik_require( "includes/bw_posts.inc" );
oik_require( "includes/bw_images.inc" );
oik_require( "bobbforms.inc" );

/**
 * Display a table header
 * 
 * @param array $title_arr - array of title header fields
 */
function bw_table_header( $title_arr ) {
  stag( "table", "bw_table" );
  stag( "thead" );
  bw_tablerow( $title_arr, "tr", "th" );
  etag( "thead" );
  stag( "tbody" );
}

/**
 * Build a default title_arr from the field_arr
 *
 * @param array $field_arr
 * @return array - title array
 */ 
function bw_default_title_arr( $field_arr ) {
  $title_arr =array();
  oik_require( "includes/bw_register.inc" );
  if ( count( $field_arr) ) {
    foreach ( $field_arr as $key => $name ) {
      //$title_arr[$name] = bw_titleify( $name );
      $title_arr[$name] = bw_query_field_label( $name ); 
    }
  }
  return( $title_arr ); 
} 

/**
 * Determine the columns for the table
 * 
 * Finds the field names of the columns for the table, determines the table title for each field and creates a table heading
 * @uses "oik_table_fields_${post_type} - filter to determine fields to display in the table
 * @uses "oik_table_titles_${post_type} - filter to determine titles to display in the table
 *
 * Default fields, if not set are title and description (excerpt) 
 * 
 * @param array $atts - shortcode parameters including "fields="
 * @param string $post_type - the post type being displayed
 * @return bool - true if one of the columns is "excerpt"
 *
 */
function bw_query_table_columns( $atts=null, $post_type ) {
  global $field_arr, $title_arr;
  $field_arr = array();
  $title_arr = array();


  $fields = bw_array_get( $atts, "fields", null );
  if ( $fields ) { 
    $field_arr = explode( ",", $fields ); 
    $field_arr = bw_assoc( $field_arr );
  } else {
    $field_arr = apply_filters( "oik_table_fields_${post_type}", $field_arr, $post_type );
    if ( empty( $field_arr ) ) {
      $field_arr['title'] = 'title';
      $field_arr['excerpt'] = 'excerpt'; 
    }  
  }
  bw_trace2( $field_arr, "field_arr", false );
  $title_arr = bw_default_title_arr( $field_arr ); 
  $title_arr = apply_filters( "oik_table_titles_${post_type}", $title_arr, $post_type, $field_arr );
 
  bw_table_header( $title_arr );  
  $excerpts = in_array( "excerpt", $field_arr);
  return( $excerpts );
    
}

/**
 * Format a table row
 * 
 * @param post $post - the current post object
 * @param array $atts - shortcode parameters
 * 
 */
function bw_format_table_row( $post, $atts ) {
  global $field_arr; 
  $atts['post'] = $post;
  //bw_trace2( $field_arr, "field_arr", false );
  stag( "tr" );
  foreach ( $field_arr as $key => $value ) {
    //bw_trace2( $key, "key", false );
    //bw_trace2( $value, "value", false );
    stag( "td", $value, $key );
    
    if ( property_exists( $post, $value ) ) {
      $field_value = $post->$value ;
      bw_theme_field( $value, $field_value, $atts ); 
      
    } elseif ( property_exists( $post, "post_$value" ) ) {       
      $field_name = "post_" . $value;  
      $field_value = $post->$field_name;
      bw_theme_field( $field_name, $field_value, $atts );
      
       // e( $post->$field );
    } else {
       // e( "key==value" . $key . $value . $key==$value );
       bw_trace2( $value );
       bw_custom_column( $value, $post->ID );   
       //bw_flush();
    }   
    etag( "td" );
  }  
  // bw_tablerow( $field_arr );
  etag( "tr");
}

/**
 * Format the data in a table 
 * 
 * The titles are returned by the post type... what if it's not a custom post type
 * The fields are returned by the post type... ditto
 * 
 * If oik-fields (v1.18 or higher) is not loaded then we need to load the functions to "theme" fields. See bw_format_table_row()
 * If the version loaded still doesn't have bw_theme_field() then we can't continue.
 *
 * @param array $posts - array of post objectgs
 * @param array $atts - shortcode parameters
 */
function bw_format_table( $posts, $atts ) {
  $atts['post_type'] = $posts[0]->post_type;
  $post_type = $posts[0]->post_type; 
  
  $excerpts = bw_query_table_columns( $atts, $post_type );
  
  if ( !function_exists( "bw_theme_field" ) ) {
    oik_require2( "includes/bw_fields.inc", "oik-fields" );
    if ( !function_exists( "bw_theme_field" ) ) {
      bw_trace2( "Please upgrade oik-fields" );
      p( "oik-fields plugin version must be version 1.31 or higher" );
      return;
    }
  }
  $cp = bw_current_post_id();
  foreach ( $posts as $post ) {
    bw_current_post_id( $post->ID );
    if ( $excerpts )
      $post->excerpt = bw_excerpt( $post );    
    bw_format_table_row( $post, $atts );
  }
  bw_current_post_id( $cp );
  etag( "tbody" );
  etag( "table" );
}

/** 
 * Display a table of information showing custom data and other content  
 * 
 * @param mixed $atts - parameters to the shortcode 
 * @return string the "raw" content - that could be put through WP-syntax
 */
function bw_table( $atts=null, $content=null, $tag=null ) {
  //bw_trace2();
  $atts['numberposts'] = bw_array_get( $atts, 'numberposts', 10 );
  $posts = bw_get_posts( $atts );
  if ( $posts ) { 
    bw_format_table( $posts, $atts );   
  }
  bw_clear_processed_posts();
  return( bw_ret() );
}

/**
 * Syntax hook for [bw_table] shortcode
 */
function bw_table__syntax( $shortcode="bw_table" ) {
  $syntax = _sc_posts(); 
  $syntax = array_merge( $syntax, _sc_classes() );
  $syntax['fields'] = bw_skv( "title,excerpt", "<i>fields</i>", "CSV of field names" );
  return( $syntax );   
}

/**
 * Help hook for [bw_table] shortcode
 */
function bw_table__help( $shortcode="bw_table" ) {
  return( "Display custom post data in a tabular form" );
}

/**
 * Example hook for [bw_table] shortcode
 */
function bw_table__example( $shortcode="bw_table" ) {
 $text = "To display a table of the 4 most recent posts" ;
 $example = 'post_type="post" orderby="post_date" order=DESC numberposts=4';
 // oops it went into a loop! 
 //bw_invoke_shortcode( $shortcode, $example, $text );
 p( "No example for $shortcode" );
} 

/**
 * Snippet hook for [bw_table] shortcode
 */
function bw_table__snippet( $shortcode="bw_table" ) {
 p( "No snippet for $shortcode" );
}

