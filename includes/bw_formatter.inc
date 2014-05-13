<?php // (C) Copyright Bobbing Wide 2013

/**
 * Format the post as specified by the user  
 *
 * The format= parameter is used to specify the fields to be displayed.
 * Each field or metadata has a single digit code.
 * The output is written to the internal buffer used by all shortcodes.
 *
 * @param object $post - the post to format
 * @param array $atts - the attributes
 */
function bw_format_as_required( $post, $atts ) {
  $format = $atts['format'];
  $fs = str_split( $format );
  $in_block = bw_format_block_start( $post, $atts, false );
  foreach ( $fs as $f ) {
    $function = bw_field_function( $f );
    $function( $post, $atts, $f );
  }
  bw_format_block_end( $post, $atts, $in_block );
}

/**
 * Return the field function to invoke
 *
 * Returns the function to invoke to format the field 
 * <code>
 * A Field               Other atts            Notes
 * - ------------------  -------------------   ------------------------------
 * T Title                                     This is NOT a link
 * I Image               thumbnail=
 * F Featured image      thumbnail=            Currently same as I-Image
 * C Content                                   
 * E Excerpt
 * M Read more           readmore=
 * R Read more           readmore=             same as M-Read more
 * L Link 
 * A Anchor                                    currently same as L - Link
 *
 * / div                                       Dummy <div></div>             
 *   space                                     Add a &nbsp; 
 * 
 * Meta data - lower case
 * c  categories
 * o  comments
 * t  tags
 * a  author
 * d  date
 * e  edit
 * 
 * _ Fields              fields=               only works when oik-fields enabled
 * 
 * Future use?
 * ! span                                      Americans know this as bang, sounds like span
 * + span                                      Dummy <span></span>
 * - span
 * $ Price                                     
 * ? Caption                                   For attachments
 * S Status                                    Displays the post_status
 * Y tYpe                                      Displays the post_type
 * P Parent                                    Displays the post_parent as a link
 * B block                                     Creates an Artisteer block
 * 
 * , reserved for field name separator when the full field names are used
 *
 * Characters we can't use, since these would mess up the shortcode logic:
 * = ' "  [ ] 
 * </code>
 *
 */    
function bw_field_function( $abbrev ) {
  $fields = _bw_field_functions();
  $function = bw_array_get( $fields, $abbrev, "bw_field_function_undefined" );
  return( $function );
}

/**
 * Field format function for an unrecognised value
 * 
 */
function bw_field_function_undefined( $post, &$atts, $f ) {
  e( "Undefined formatting function for field: " );
  e( $f );
}

/**
 * Return the array of field formatting functions
 *
 * @return array of field formatting functions keyed by the field abbreviation 
 */
function _bw_field_functions() {
  static $fields;
  if ( is_null( $fields) ) {
    $fields = array();
    $fields['T'] = "bw_field_function_title";
    $fields['I'] = "bw_field_function_image"; 
    $fields['F'] = "bw_field_function_image"; 
    $fields['C'] = "bw_field_function_content"; 
    $fields['E'] = "bw_field_function_excerpt"; 
    $fields['M'] = "bw_field_function_readmore"; 
    $fields['R'] = "bw_field_function_readmore"; 
    $fields['L'] = "bw_field_function_link"; 
    $fields['A'] = "bw_field_function_anchor"; 
    $fields['/'] = "bw_field_function_div"; 
    $fields[' '] = "bw_field_function_nbsp"; 
    $fields['c'] = "bw_field_function_categories"; 
    $fields['o'] = "bw_field_function_comments"; 
    $fields['t'] = "bw_field_function_tags"; 
    $fields['a'] = "bw_field_function_author"; 
    $fields['d'] = "bw_field_function_date"; 
    $fields['e'] = "bw_field_function_edit"; 
    // Apply_filters to allow other formatting functions provided by other plugins 
    $fields = apply_filters( "bw_field_functions", $fields );
  }
  return( $fields );
} 

/**
 * Format the title (format=T) 
 * 
 * The initial formatting is as coded in [bw_pages]
 * **?** 2013/06/08 Should the title be styled as h3 rather than strong?
 */
function bw_field_function_title( $post, &$atts, $f ) {
  $atts['title'] = get_the_title( $post->ID );
  span( "title" );
  strong( $atts['title'] );
  epan();
} 

/**
 * Format the 'thumbnail' image (format=I)
 * 
 * Applies the thumbnail= parameter to determine the size of the image
 *
 */ 
function bw_field_function_image( $post, &$atts, $f ) {
  $thumbnail = bw_thumbnail( $post->ID, $atts );
  if ( $thumbnail ) {
    bw_format_thumbnail( $thumbnail, $post, $atts );
  }
}

/**
 * Format the full content of a post 
 *
 * @todo ensure that other "the_content" filters are NOT applied we only want to process shortcodes.
 *
 * @param object $post - a post object
 * @return string - the full content of the post after shortcode expansion.
 * 
 */
function bw_content( $post ) {
  $content = $post->post_content;
  bw_trace2(); 
  $content = bw_get_the_content( $content ); 
  return( $content );
}  

/**
 * Format the content (format=C)
 *
 * Note that this is supposed to display the full content AFTER shortcode expansion
 * BUT excluding other filters that may be applied during "the_content" processing
 * 
 */
function bw_field_function_content( $post, &$atts, $f ) {
  $content = bw_content( $post );
  span( "content" );
  e( $content );
  epan( "content" );
}

/**
 * Format the excerpt (format=E)
 *
 * @uses bw_excerpt();
 */
function bw_field_function_excerpt( $post, &$atts, $f ) {
  $excerpt = bw_excerpt( $post );
  span( "excerpt" );
  e( $excerpt );
  epan( "excerpt" );
}

/**
 * Format the "read more" link
 */
function bw_field_function_readmore( $post, &$atts, $f ) {
  bw_format_read_more( $post, $atts );  
}

/**
 * Format the link
 */
function bw_field_function_link( $post, &$atts, $f ) {
  oik_require( "shortcodes/oik-parent.php" );
  bw_post_link( $post->ID );
}

/**
 * Format a dummy div /ediv (format=/)
 */
function bw_field_function_div( $post, &$atts, $f ) {
  sediv( "bw_div" );
}

/**
 * Format a non-blank space
 *
 * **?** should this be within a span so that it can be styled with CSS?
 */
function bw_field_function_nbsp( $post, &$atts, $f ) {
  sepan( "nbsp", "&nbsp;" );
}


function bw_field_function_metadata( $class, $label, $value ) {
 span( $class );
 sepan( "label", $label );
 bw_format_sep();
 sepan( "value", $value );
 epan();
}

/**
 * Format the Categories (format=c)
 */
function bw_field_function_categories( $post, &$atts, $f ) {
  $categories_list = get_the_category_list( ",", "", $post->ID );
  bw_field_function_metadata( "bw_categories", "Categories", $categories_list );
}

/**
 * Format the Comments count (format=o)
 */
function bw_field_function_comments( $post, &$atts, $f ) {
  $comments_number = get_comments_number( $post->ID );
  bw_field_function_metadata( "bw_comments", "Comments", $comments_number );
}

/**
 * Format the Tags (format=t )
 */
function bw_field_function_tags( $post, &$atts, $f ) {
  $tag_list = get_the_tag_list( "", ",", "", $post->ID );
  bw_field_function_metadata( "bw_tags", "Tags", $tag_list );
}

/**
 * Format the Author (format=a )
 
 Artisteer produces author links like this
 

<span class="art-postauthoricon">
  <span class="author">By</span> 
  <span class="author vcard">
    <a class="url fn n" href="http://www.oik-plugins.com/author/vsgloik/" title="View all posts by vsgloik">vsgloik</a>
  </span>
</span> 

 We do something similar


 */
function bw_field_function_author( $post, &$atts, $f ) {
  $author_posts_url = get_author_posts_url( $post->post_author );
  $author_name =  get_the_author_meta( "nicename", $post->post_author );
  $author_link = retlink( "url fn n", $author_posts_url, $author_name );
  bw_field_function_metadata( "bw_author", "By", $author_link );
}

/**
 * Format the Date (format=d)
 */
function bw_field_function_date( $post, &$atts, $f ) {
  static $date_format;
  $date_format = get_option('date_format');
  
  $date = get_post_time( $date_format, false, $post->ID, false );
  bw_field_function_metadata( "bw_date", "Date", $date );
}

/**
 * Format the Edit post link (format=e)
 *
 */
function bw_field_function_edit( $post, &$atts, $f ) {
 $link = get_edit_post_link( $post->ID );
 if ( $link ) {
   alink( "bw_edit", $link, "[Edit]" ); 
 }
}

/**
 * Format the starting HTML for the object
 *
 * Note: When "block" is true (default) then the title is automatically included in the header so may not be NOT needed in the content
 *
 */
function bw_format_block_start( $post, $atts, $default_block=true ) {
  $in_block = bw_validate_torf( bw_array_get( $atts, "block", $default_block ));
  if ( $in_block ) {
    $atts['title'] = get_the_title( $post->ID );
    oik_require( "shortcodes/oik-blocks.php" );
    e( bw_block( $atts ));
  } else {
    $class = bw_array_get( $atts, "class", "" );
    sdiv( $class );
  }
  return( $in_block );
}

/**
 * Format the ending HTML for the object
 */
function bw_format_block_end( $post, $atts, $in_block ) {
  if ( $in_block )
    e( bw_eblock() ); 
  else {  
    sediv( "cleared" );
    ediv();  
  }    
}

/**
 * Return the default separator between the field label and field value
 * @return string - separator - with spaces if required
 */
if ( !function_exists( "bw_default_sep" ) ) {
function bw_default_sep() {
  $sep = bw_get_option( "bw_separator", "bw_fields" );
  if ( !$sep ) {
    $sep = __( ": " );
  }  
  return( $sep);
} 
}

/**
 *
 */
if ( !function_exists( "bw_format_sep" ) ) {
function bw_format_sep( $type="field" ) {
  static $sep = null;
  if ( !$sep ) {
    $sep = bw_default_sep( $type );
  }
  span( "sep" );
  e( $sep );
  epan();
}
}

  


