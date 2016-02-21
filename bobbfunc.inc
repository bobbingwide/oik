<?php // Copyright Bobbing Wide 2009-2016
bw_trace2( __FILE__ , "loaded" ) ;

$bobbfunc_loaded = oik_require_lib( "bobbfunc" );
if ( $bobbfunc_loaded && $bobbfunc_loaded instanceof WP_Error ) {
	bw_trace2( $bobbfunc_loaded, "bobbfunc error", false, BW_TRACE_ERROR );
}
 
/**
 * Original bobbfunc functions not in the 'bobbfunc' library
 * 
 * The following functions have been moved to the 'bobbfunc' library:
 * - bw_oik_version
 * - bw_echo
 * - bw_flush
 * - bw_ret
 * - bw_push
 * - bw_pop
 * - bw_do_shortcode
 * - retimage
 * - kv
 * - atitle
 * - aname
 * - _alink
 * - alink
 * - retlink
 * - retstag
 * - nullretstag
 * - stag
 * - sol
 * - sul
 * - sdiv
 * - eol
 * - eul
 * - ediv
 * - sediv
 * - ep
 * - nullretetag
 * - retetag
 * - etag
 * - sp
 * - p
 * - p_
 * - hn
 * - h1
 * - h2
 * - h3
 * - h4
 * - h5
 * - h6
 * - bwt
 * - e
 * - br
 * - hr
 * - lit
 * - li
 * - span
 * - epan
 * - sepan
 * - td
 * - th
 * - bw_get_docroot_suffix
 * - bw_expand_link
 * - strong
 * - em
 * - bw_quote
 * - cite - renamed to _bw_cite
 * - abbr - renamed to _bw_abbr
 * - acronym - renamed to _bw_acronym
 * - c - copied to _bw_c for oik, but not deprecated yet
 * - bw_debug_on
 * - bw_debug_off
 * - bw_debug
 * - bw_format_date
 * - bw_validate_torf
 * - bw_plugin_namify
 * - bw_funcname
 * - oik_url
 * - bw_sc_help
 * - bw_sc_syntax
 * - bw_sc_example
 * - bw_sc_snippet
 * - bw_jquery
 * - bw_jq_flush
 * - bw_jq
 * - bw_json_encode
 * - bw_jqv
 * - bw_jtorf
 * - bw_file_to_url
 * - bw_get_image_link
 * - bw_recreate_options
 * - bw_append
 * - bw_pick_one
 * - bw_load_plugin_textdomain
 * - bw_get_theme
 * - bw_wp_error
 * - bw_global_post_id
 * - bw_current_post_id
 * - bw_context
 * - bw_translate
 * - bw_translation_off
 * - bw_translation_on
 * - bw_dtt
 * - bw_get_dtt
 * - bw_tt
 * - bw_is_loaded
 *
 * Note: The above functions will be further stripped down as the oik admin interface is improved to use tabs.
 * 
 * Functions moved elsewhere
 *
 * Function | New location
 * -------- | ----------------- 
 * bw_blockquote | shortcodes/oik-blockquote.php
 * bw_cite | shortcodes/oik-cite.php
 * bw_abbr | shortcodes/oik-abbr.php
 * bw_acronym | shortcodes/oik-acronym.php
 * 
 * Any remaining functions are NOT in the 'bobbfunc' library
 * To use these function the calling routine will need to continue to oik_require( "bobbfunc.inc" ); as before
 */	
 

/**
 * Return the styled "Bobbing Wide" string
 * 
 * Note: In this instance Bobbing Wide is not translatable
 */
function bw( $bw_class_prefix=NULL ) {  
  if ( is_array( $bw_class_prefix ) ) {
    $bwcp = bw_array_get( $bw_class_prefix, "cp", null);
    $cp = ' class="' . $bwcp;
  } else {   
    if ( is_null( $bw_class_prefix )) 
       $cp = ' class="';
    else
       $cp = ' class="' . $bw_class_prefix;
  }         
  $bw  = '<em' .$cp .'bw_b1">B</em>';
  $bw .= '<em' .$cp .'bw_o">o</em>'; 
  $bw .= '<em' .$cp .'bw_b2">b</em>';
  $bw .= '<em' .$cp .'bw_b3">b</em>';
  $bw .= '<em' .$cp .'bw_i1">i</em>';
  $bw .= '<em' .$cp .'bw_n">n</em>';
  $bw .= '<em' .$cp .'bw_g">g</em>';
  $bw .= '<em' .$cp .'bw_space">&nbsp;</em>';
  $bw .= '<em' .$cp .'bw_W">W</em>';
  $bw .= '<em' .$cp .'bw_i2">i</em>';
  $bw .= '<em' .$cp .'bw_d">d</em>';
  $bw .= '<em' .$cp .'bw_e">e</em>';
  return $bw;
}


/** 
 * echo Bobbing Wide inside a theme's template file
 *
 * Note: We don't use bw_echo here.
 */
function ebw( $bw_class_prefix=NULL ) {
	echo bw( $bw_class_prefix );
}

// Herb 2015/06/28	
//oik_require( "bobbcomp.inc" );
// Moved logic for art_button to bobbcomp.inc

/** 
 * Return a $default value if the $value is not set
 * 
 * @param string $value - may be null or empty
 * @param string $default - the default value to return
 * @return string
 */
function bw_default( $value, $default=NULL ) {
  $val = $value; 
  //bw_trace( $val, __FUNCTION__, __LINE__, __FILE__, "value before" );
  if ( empty( $val ))
    $val = $default;
  //bw_trace( $val, __FUNCTION__, __LINE__, __FILE__, "value returned" );
  return( $val );
}    
  
/** 
 *  
 * Gallery logic
 * 
 * The gallery shortcode allows media to be displayed
 * Here we take values from the page's metadata
 *  gallery-columns = 1,2, etc
 *  gallery-size = 'thumbnail', 'medium', 'large', 'full'
 * and apply them to the gallery shortcode
 * If the meta data is not set in the post the default values are:
 * 
 * 
 * [gallery columns="1" size="medium"] 
 * 
 * For more information on gallery see http://codex.wordpress.org/Gallery_Shortcode
 * For more information on sizes see WordPress admin panel under Settings > Media
 */
if ( !function_exists( "bw_gallery" ) ) {
function bw_gallery() {
  _bw_c( "gallery processing");
  global $post;
  bw_trace( $post, __FUNCTION__, __LINE__, __FILE__, "post" );
 
  $columns = get_post_meta( $post->ID, "gallery-columns", true );
  $columns = bw_default( $columns, "1");
  $size = get_post_meta( $post->ID, "gallery-size", true );
  $size = bw_default( $size, "medium" );
   
  $gallery_options = array( 'columns' => $columns, 'size' => $size );
  e( gallery_shortcode( $gallery_options ));
}
}
