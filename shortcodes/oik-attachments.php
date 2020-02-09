<?php 
/*
    Copyright 2012-2017 Bobbing Wide (email : herb@bobbingwide.com )

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
oik_require( "includes/bw_posts.php" );
oik_require( "includes/bw_images.inc" );

/**
 * Find the correct file name for this image
 *
 * ` 
  C:\apache\htdocs\wordpress\wp-content\plugins\oik\shortcodes\oik-attachments.php(26:0) 2012-07-16T10:14:35+00:00 397 cf=the_content bw_thumbnail_full(2) attachment_meta Array
    [0] => Array
        (
            [width] => 350
            [height] => 178
            [hwstring_small] => height='65' width='128'
            [file] => 2012/03/nggallery-example1.jpg
            [sizes] => Array
                (
                    [thumbnail] => Array
                        (
                            [file] => nggallery-example1-150x150.jpg
                            [width] => 150
                            [height] => 150
                        )

                    [medium] => Array
                        (
                            [file] => nggallery-example1-256x130.jpg
                            [width] => 256
                            [height] => 130
                        )

                )

            [image_meta] => Array
                (
                    [aperture] => 0
                    [credit] => 
                    [camera] => 
                    [caption] => 
                    [created_timestamp] => 0
                    [copyright] => 
                    [focal_length] => 0
                    [iso] => 0
                    [shutter_speed] => 0
                    [title] => 
                )

        )

  `
 * @param post $post - the post
 * @return string - HTML to display the image
 */
function bw_thumbnail_full( $post ) {
  $attachment_meta = get_post_meta( $post->ID, "_wp_attachment_metadata", false );
  //bw_trace2( $attachment_meta, "attachment_meta", false );
  $first = bw_array_get( $attachment_meta, 0, null );
  
  if ( $first ) { 
    $file = bw_array_get( $first, "file", null );
  }     
  
  if ( $first && $file)  {
  
    $upload_dir = wp_upload_dir();
    $baseurl = $upload_dir['baseurl'];
    $retimage = retimage( "full", $baseurl . '/' . $file );
  } else {
    $retimage = retimage( "full", $post->guid );
  }  
  return( $retimage );  
}

/**
 * Create a direct link to the attached file rather than a permalink to the attachment
 * 
 * @param object $post - the post for the attached file
 * @param array $atts - the shortcode parameters
 *
 * If there is no attached file for the $post then something is wrong - create a trace record
 */
function bw_link_attachment( $post, $atts ) {
  $file = get_post_meta( $post->ID, "_wp_attached_file", true );
  if ( $file ) {
    $upload_dir = wp_upload_dir();
    $file = $upload_dir['baseurl'] . '/' . $file;
    BW_::alink( "bw_attachment", $file , $atts['title'], null, "link-".$post->ID );  
  } else {
    bw_trace2();
  }    
}

/**
 * Format the "attachment" - basic first version
 *
 * Format the 'post' in a block or div with title and link to the attachment
 *
 * @param object $post - A post object
 * @param array $atts - Attributes array - passed from the shortcode
 * 
 * e.g. post_mime_type=image 
 *
 */
function bw_format_attachment( $post, $atts ) {
  bw_trace2();
  $atts['title'] = get_the_title( $post->ID );
  $in_block = bw_validate_torf( bw_array_get( $atts, "block", 'n'));
  if ( $in_block ) { 
    oik_require( "shortcodes/oik-blocks.php" );
    e( bw_block( $atts ));
  } else {
    $class = bw_array_get( $atts, "class", "bw_attachment" );
    sdiv( $class );
  } 
  sp();
  // Display images as thumbnails and other attachments as text links
  // This call seems inefficient since we've already loaded the whole post
  // so wp_get_attachment_link is not doing much really! 
  $atts['thumbnail'] = bw_array_get( $atts, 'thumbnail', 'thumbnail' ); 
  if ( $atts['thumbnail'] == "full" ) { 
    $thumbnail = bw_thumbnail_full( $post );
    // $thumbnail = retimage( "full", $post->guid );
    // bw_link_thumbnail( $thumbnail, $post->ID, $atts );
  } else {
    $thumbnail = bw_thumbnail( $post->ID, $atts, true );
  }
  if ( $thumbnail ) { 
    bw_link_thumbnail( $thumbnail, $post->ID, $atts );
  } else {
    bw_link_attachment( $post, $atts );
  }  
  if ( bw_validate_torf( bw_array_get( $atts, 'titles', 'y' )) ) { 
    span( "title" );
    e( $post->post_title );   // Title
    epan();  
  }
  ep(); 
   
  if ( bw_validate_torf( bw_array_get( $atts, 'captions', 'n' )) ) { 
    BW_::p( $post->post_excerpt, "caption" ); // Caption
    BW_::p( $post->post_content, "description" ); // Description
  }
  
  if ( $in_block )
    e( bw_eblock() ); 
  else {  
    sediv( "cleared" );
    ediv();  
  }
}   

/**
 * List attachments
 *
 * This function is similar to bw_pages but formats attachments
 * It works in conjunction with Artisteer blocks - to enable the page list to be styled as a series of blocks
 * Well, that's the plan
 * `
 * [bw_attachments class="classes for bw_block" 
 *   post_type='attachment'
 *   post_mime_type='
 *     application/pdf
 *      image/gif
 * 	image/jpeg
 * 	image/png
 * 	text/css
 *      video/mp4
 * 
 *   post_parent 
 *   orderby='title'
 *   order='ASC'
 *   posts_per_page=-1
 *   block=true or false
 *   thumbnail=specification - see bw_thumbnail()
 *   customcategoryname=custom category value  
 * `
 */
function bw_attachments( $atts = NULL ) {
  $atts[ 'post_type'] = bw_array_get( $atts, "post_type", "attachment" );
  $atts['id'] = bw_array_get_from( $atts, "id,0", null );
  $atts['post_status'] = 'inherit';
  $posts = bw_get_posts( $atts );
  //bw_trace2( $posts, 'posts', true, BW_TRACE_DEBUG );
  foreach ( $posts as $post ) {
    bw_format_attachment( $post, $atts );
  }
  return( bw_ret() );
}

/**
 * [bw_pdf] shortcode - display attached PDF files
 *
 * @param array $atts - shortcode parameters
 * @return string HTML for the attached PDF file list
 */
function bw_pdf( $atts = NULL ) {
  $atts['post_mime_type'] = 'application/pdf';
  $atts['thumbnail'] = "none";
  $atts['class'] = bw_array_get( $atts, "class", "bw_pdf" );
  return( bw_attachments( $atts ));
}  

/**
 * Display the images attached to a post or page 
 *
 * Note: Since this uses bw_attachments() this does not behave in the same manner as [[bw_posts]], [[bw_thumbs]] or [[bw_pages]]
 * If they want the images attached to a different post from the current one then we'll need to override the 
 * post_parent parent parameter with the id parameter.
 */ 
function bw_images( $atts = NULL ) {
  $atts['post_mime_type'] = bw_array_get( $atts, 'post_mime_type', 'image' );
  $atts['thumbnail'] = bw_array_get( $atts, 'thumbnail', 'full' );
  $atts['class'] = bw_array_get( $atts, "class", "bw_images" );
  return( bw_attachments( $atts ));
}

/** 
 * Return TRUE if the file names of the files are the same and the first is of type $extension
 *
 * We ignore the path information since the files could have been uploaded and attached in different months
 * This is a case sensitive search
 * **?** This should have been defined to pass a third parm of args which is an array of key value pairs
 * that way we can pass args to the $matchfunc
 *
*/
function bw_match_byguid_name( $given, $post, $extension='pdf' ) {
  $given_guid_name = pathinfo( $given->guid );
  $post_guid_name = pathinfo( $post->guid );
  $matched = ( $given_guid_name['extension'] == $extension &&  $given_guid_name['filename'] == $post_guid_name['filename'] );
  return( $matched );
}  

/**
 * Find a post in an array of post using the specified $matchfunc
 * This routine will not find the $given post
 *
*/
function bw_find_post( $posts, $given, $matchfunc="bw_match_byguid_name" ) {
  $matched = NULL;
  foreach ( $posts as $post ) {
    if ( $post->ID <> $given->ID ) {
      if ( $matchfunc( $given, $post ) ) {
        $matched = $post;
        break;
      }  
    }    
  }
  return( $matched );
}

/**
 * Format the matched post link
 * 
 * @param post $post - the .pdf file for the link
 * @param post $matched_post - the image file with the matching name
 * @param array $atts - shortcode parameters  
 */
function bw_format_matched_link( $post, $matched_post, $atts ) {
  $class = bw_array_get( $atts, "class", "" );
  sdiv( $class );
  $image = retimage( "bw_portfolio", $matched_post->guid, $post->post_title );
  $ptspan = "<span>".$post->post_title."</span>";
  BW_::alink( "bw_portfolio", $post->guid, $image.$ptspan );
  ediv( $class );
}

/**
 * Process pairs of attachments
 * @param array $posts - array of posts
 * @param array $atts - shortcode parameters
 * @return output produced by bw_format_matched_link()
 */
function bw_paired_attachments( $posts, $atts ) {
  bw_trace2( $posts, "posts" );
  foreach ( $posts as $post ) {
    $matched_post = bw_find_post( $posts, $post );
    if ( $matched_post )
      bw_format_matched_link( $post, $matched_post, $atts ); 
  }
  return( bw_ret());
} 

/**
 * Display image links to PDF files
 * For each .PDF file that is linked to an image pair them up and display
 * with the image and the PDF file name as the selector and the 
 * PDF file name as the link.
 * 
 * @param array $atts - shortcode parameters
 * @return string expanded shortcode
 */
function bw_portfolio( $atts = NULL ) {
  $atts['post_type'] = bw_array_get( $atts, "post_type", "attachment" );
  $atts['post_mime_type'] = bw_array_get( $atts, "post_mime_type", "image,application/pdf" );
  $atts['orderby'] = bw_array_get( $atts, "orderby", "title" );
  $atts['order'] = bw_array_get( $atts, "order", "ASC" );
  $posts = bw_get_posts( $atts );
  return( bw_paired_attachments( $posts, $atts ));
}

/**
 * Syntax helper for captions= parameter 
 */
function _sc_captions() {
  $syntax = array( "captions" => BW_::bw_skv( "n", "y", __( "Display attachment's Caption and Description", "oik" ) ) );
  return ( $syntax );
}

function bw_attachments__syntax( $shortcode="bw_attachments" ) {
  $syntax = _sc_posts(); 
  $syntax['post_type'] = BW_::bw_skv( "attachment", "<i>" . __( "post type", "oik" ) . "</i>", __( "Post type to display", "oik" ) );
  $syntax += _sc_thumbnail();
  $syntax += _sc_captions();
  $syntax += _sc_classes();
  return( $syntax );   
}

function bw_pdf__syntax( $shortcode="bw_pdf" ) {
  $syntax = _sc_posts(); 
  $syntax['post_type'] = BW_::bw_skv( "attachment", "<i>" . __( "post type", "oik" ) . "</i>", __( "Post type to display", "oik" ) );
  $syntax['post_mime_type'] = BW_::bw_skv( "application/pdf", "", __( "Cannot be overridden", "oik" ) );
  $syntax += _sc_captions();
  $syntax += _sc_classes();
  return( $syntax );   
}

function bw_portfolio__syntax( $shortcode="bw_portfolio" ) {
  $syntax = _sc_posts(); 
  $syntax['post_type'] = BW_::bw_skv( "attachment", "<i>" . __( "post type", "oik" ) . "</i>", __( "Post type to display", "oik" ) );
  $syntax['post_mime_type'] = BW_::bw_skv( "image,application/pdf", "", __( "Attachment types to pair", "oik" ) );
  $syntax['orderby'] = BW_::bw_skv( "title", "date|ID|parent|rand|menu_order", __( "Sort sequence", "oik" ) );
  $syntax['order'] = BW_::bw_skv( 'ASC', "DESC", __( "Sort order.", "oik" ) );
  $syntax += _sc_classes();
  return( $syntax ); 
} 

function bw_images__syntax( $shortcode="bw_images" ) {
  $syntax = _sc_posts(); 
  $syntax['post_type'] = BW_::bw_skv( "attachment", "<i>" . __( "post type", "oik" ) . "</i>", __( "Post type to display", "oik" ) );
  $syntax['post_mime_type'] = BW_::bw_skv( "image", "<i>" . __( "post mime types", "oik" ) . "</i>", __( "Image type", "oik" ) );
  $syntax['thumbnail'] = BW_::bw_skv( "full", "thumbnail|medium|large|nnn|wxh", __( "image size", "oik" ) ); 
	$syntax['link'] = BW_::bw_skv( null, "0|id,p", __("link to", "oik" ) );
  $syntax += _sc_captions();
  $syntax += _sc_classes();
  return( $syntax );
}    
   

