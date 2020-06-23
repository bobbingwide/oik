<?php
/*
    Copyright 2011-2017,2020 Bobbing Wide (email : herb@bobbingwide.com )

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

/**
 * Return an array suitable for passing to image functions to determine the size
 * 
 * @param mixed $size string representing the size.
 * if a single integer then make the array square
 * otherwise it's widthxheight or width,height or some other way of specifying width and height
 * so we split at the non numeric value(s) and take the first two integer bits
 * @return array containing width and height
 */
function bw_get_image_size( $size=100 ) {
  $pattern = "/([\d]+)/";
  preg_match_all( $pattern, $size, $thumbnail );
  //bw_trace2( $thumbnail );
  if ( count( $thumbnail[0] ) < 2 ) 
    $thumbnail[0][1] = $thumbnail[0][0];
    
  $size = array( $thumbnail[0][0], $thumbnail[0][1] );  

  // bw_trace( $size, __FUNCTION__, __LINE__, __FILE__, "size" );
  return( $size ); 
}

/**
 * Force the thumbnail size to be what we asked for
 * 
 * wp_attachment_get_image_src() can sometimes attempt to return a $thumbnail where the
 * width and height don't match what we asked for.
 * 
 * The values of $thumbnail[1] and $thumbnail[2] are used to set the width and height values on the <img> tag
 * We choose to reset these to the values first thought of. 
 * 
 * Note: The side effect of this is that the images get reshaped rather than being cropped.
 * It's up to the web designer to set the sizes for thumbnail, small, medium and large
 * and/or upload images that will scale nicely for the chosen figures. 
 *
 * 2013/05/08 When either the required width or height is set to 0 then the size of the image is set by scaling, maintaining the aspect ratio
 * In the shortcode this can be specified using thumb=0xnn to set the height or thumb=nnx0 to set the width.
 * Set the height when you want the images laid out in a row, and the width when you want them in a column.
 * When the $size is NOT an array then we do not attempt to force the size, allowing the images to display responsively.
 *
 */
function bw_force_size( $thumbnail, $size ) {
  if ( is_array( $size ) ) {
    $aw = $thumbnail[1];
    $ah = $thumbnail[2];
    $thumbnail[1] = _bw_new_width( $aw, $ah, $size[0], $size[1] );
    $thumbnail[2] = _bw_new_height( $aw, $ah, $size[0], $size[1] );
  } else {
    $thumbnail[1] = null;
    $thumbnail[2] = null;
  }  
  return( $thumbnail );
}

/**
 * Return the new width - maintaining aspect ratio
 * @param integer $aw - actual width
 * @param integer $ah - actual height
 * @param integer $rw - required width
 * @param integer $rh - required height
 * @return integer - the new width
 */
function _bw_new_width( $aw, $ah, $rw, $rh ) {
  if ( $rw ) {
    $nw = $rw; 
  } else {
    $nw = ( $aw * $rh ) / $ah;
  }
  return( (int) $nw );  
}

/**
 * Return the new height - maintaining aspect ratio
 * @param integer $aw - actual width
 * @param integer $ah - actual height
 * @param integer $rw - required width
 * @param integer $rh - required height
 * @return integer - the new height
 */
function _bw_new_height( $aw, $ah, $rw, $rh ) {
  if ( $rh ) {
    $nh = $rh; 
  } else {
    $nh = ( $ah * $rw ) / $aw;
  }                   
  return( (int) $nh );  
}

/**
 * Validate the thumbnail size, returning appropriate value for subsequent calls
 *
 * @param array $atts - containing the required specification for "thumbnail"
 * @return mixed - as below
 * false      for thumbnail="none" 
 * string     for thumbnail="thumbnail|medium|large|full"
 * thumbnail  for "Y" or "T" ( from torf = True or False )
 * array      for anything else e.g. 150 or 250x150 or 250x0
 * 
 */
function bw_get_thumbnail_size( $atts ) {
  $thumbnail = bw_array_get( $atts, 'thumbnail', 'thumbnail' );
  // post_id = bw_array_get( $atts, "post_id", null ); **?** unused 2013/06/21
  switch ( $thumbnail ) {
    case 'none':
      $thumbnail = false;
    break; 
     
    case 'full':
    case 'thumbnail':
    case 'medium':
    case 'large':
    break;
           
    default:
      $torf = bw_validate_torf( $thumbnail );
      if ( $torf ) {
        $thumbnail = 'thumbnail';
      } else {
        $thumbnail = bw_get_image_size( $thumbnail ); 
      } 
  } 
  //bw_trace2( $thumbnail );
  return( $thumbnail );
}  
 
/**
 * Get the thumbnail of the specified size
 *
 * @param integer $post_id - ID of the post
 * @param array $atts - shortcode attributes
 * @param bool $attachment - true if we're finding the thumbnail for an attachment
 * @return $thumbnail - formatted <img> tag
 */
function bw_thumbnail( $post_id, $atts=NULL, $attachment=false ) {
  $atts['post_id'] = $post_id; 
  $thumbnail = bw_get_thumbnail_size( $atts );
  if ( $thumbnail ) {
    //bw_trace( $post_id, __FUNCTION__, __LINE__, __FILE__, "post_id" );
    
    switch ( $thumbnail ) {
      case 'full';
        // From oik v1.17 onwards we do get here.
        if ( $attachment ) {
          $thumbnail = bw_get_attachment_thumbnail( $post_id, $thumbnail, $atts );
        } else {
          $thumbnail = bw_get_fullimage( $post_id, $thumbnail, $atts );
        }  
      break;     
      case 'thumbnail':
      case 'medium':
      case 'large':
      default: 
        if ( $attachment ) {
          $thumbnail = bw_get_attachment_thumbnail( $post_id, $thumbnail, $atts );
        } else {
          $thumbnail = bw_get_thumbnail( $post_id, $thumbnail, $atts );
        }
      break;
    }
  }  
  return( $thumbnail ); 
}

/**
 * Return a full sized image for the post
 *
 *
 * Prior to oik version 2.0 the logic was like this
 * 
 * First we look for an attached image to the post
 * we choose one image randomly from those attached
 * If found we use that.
 *
 * In oik v2.1 this has been switched
 * First get the featured image ( using bw_get_thumbnail()  )
 * If that's not set then access an image randomly.
 *
 * Should this be an option that we can set?
 *
 * 
 * @param ID $post_id - post ID
 * @param string $size - the image size required
 * @param array $atts - array of key value pairs
 */
function bw_get_fullimage( $post_id, $size='full', $atts=null ) {
  $return_value = bw_get_thumbnail( $post_id, $size, $atts );
  if ( !$return_value ) {
    $return_value = bw_get_random_image( $post_id, $size, $atts );
  }     
  //bw_trace( $return_value, __FUNCTION__, __LINE__, __FILE__, "return_value" ); 
  return( $return_value );
}

/**
 * Return a random image from the images attached to the post
 *
 * @param ID $post_id - the ID of the post to which the images are expected to be attached
 * @param string $size 
 */
function bw_get_random_image( $post_id, $size, $atts ) {
  $arr_thumb = bw_get_attached_image( $post_id, 1, 'rand', $size );
  //bw_trace( $arr_thumb, __FUNCTION__, __LINE__, __FILE__, "arr_thumb" );
  $thumbnail = bw_array_get( $arr_thumb, 0, null );
  $img = bw_array_get( $thumbnail, 0, null );
  if ( $img ) {
    $text = bw_array_get( $atts, "title", NULL );
    $classes = "bw_thumbnail " . bw_get_post_class( $post_id );
    $return_value = retimage( $classes, $img, $text, null, null, kv( "data-thumb", $img) );
  } else { 
    $return_value = null; 
  }
  return( $return_value );     
}

/** 
 * Create a thumbnail link
 *
 * Create a thumbnail with a link to the post_id specified, either via $post_id or the $atts['link']
 * otherwise just create the image.
  
 * As of v1.13 this supports custom image links
 *
 * @param string $thumbnail full HTML for the thumbnail image
 * @param id     $post_id   default post id if not specified in $atts
 * @param array  $atts      shortcode attributes array
 *
 */
function bw_link_thumbnail( $thumbnail, $post_id=NULL, $atts=NULL )  {
  $link_id = bw_array_get( $atts, "link", $post_id );
  if ( $link_id ) {
    $text = bw_array_get( $atts, "title", NULL );
    $class = bw_array_get( $atts, "imgclass", null );  /* changed from class to imgclass **?** 2012/03/22 */
    BW_::alink( $class, bw_get_image_link( $link_id ), $thumbnail, $text, "link-".$link_id );  
  } else {
    e( $thumbnail );
  }
}

/**
 * Return the source file name for the appropriately sized thumbnail image
 * 
 * @TODO - handle the post_id being the actual post ID of the image 
 * 
 * @param ID $post_id - ID of the attached image
 * @param string|array $size - the required thumbnail size
 * @return array - array of the attached images array, which consists of url, width, height
 */
function bw_get_thumbnail_src( $post_id, $size ) {
  $thumb_id = get_post_thumbnail_id( $post_id );
  if ( $thumb_id ) {
    $thumbnail =  wp_get_attachment_image_src( $thumb_id, $size ) ;
  } elseif ( $arr_thumb = bw_get_attached_image( $post_id, 1, 'rand', $size )) {
    //bw_trace( $arr_thumb, __FUNCTION__, __LINE__, __FILE__, "arr_thumb" );
    $thumbnail = $arr_thumb[0];
  } else {
    $thumbnail = false; 
  }  
  return( $thumbnail );
}

/** 
 * Get the post thumbnail 
 * 
 * Returns the HTML for the thumbnail image which can then be wrapped in a link if required
 * 
 * @param integer $post_id - the id of the content for which the thumbnail is required
 *                defaults to the current post id
 * @param mixed  $size - the required image size: either a preset or specified in an array
 * @param array $atts - array of key value pairs that may be needed
 * @return string HTML image tag
 */
function bw_get_thumbnail( $post_id = null, $size = 'thumbnail', $atts=NULL ) {
  $return_value = FALSE;
  if ($post_id == null) { 
    $post_id = get_the_id();
  } 
  //bw_trace( $post_id, __FUNCTION__, __LINE__, __FILE__, "post_id" );
  $thumbnail =  bw_get_thumbnail_src( $post_id, $size );
  //bw_trace2( $thumbnail, "thumbnail" ); 
  if ( bw_array_get( $thumbnail, 0, FALSE)  ) {
    $text = bw_array_get( $atts, "title", NULL );
    $thumbnail = bw_force_size( $thumbnail, $size );
    $data_thumb = kv( "data-thumb", $thumbnail[0] );
    $classes = "bw_thumbnail " . bw_get_post_class( $post_id );
    $return_value = retimage( $classes, $thumbnail[0], $text, $thumbnail[1], $thumbnail[2], $data_thumb ); 
  }
  //bw_trace( $return_value, __FUNCTION__, __LINE__, __FILE__, "return_value" ); 
  return( $return_value );
}

/**
 * Get the attached image 
 *
 * Return an array of images attached to a specific post ID
 *   
 * Return Value: An array containing:
 * `
 *       $image[0] => url
 *       $image[1] => width
 *       $image[2] => height
 *       $image[3] => attachment id
 * `
 * Fix: In v2.4-beta.1218 we incorrectly tried using $args['ID'] instead og $args['post_parent'].
 * A lot of the images displayed were incorrect. This has now been reverted to using $args['post_parent']
 *  
 *
 * @param integer $post_id - the parent post ID, or null
 * @param integer $number - the number of posts to retrieve
 * @param string $orderby - how to select the image
 * @param string $image_size
 * @return array - the selected image 
 *
 */
function bw_get_attached_image( $post_id = null, $number = 1, $orderby = 'rand', $image_size = 'thumbnail') {
  $args = array( 'post_type'      => 'attachment'
               , 'numberposts'    => $number
               , 'post_mime_type' => 'image'
               , 'orderby'        => $orderby 
               );
  if ( $post_id == null) { 
    $post_id = get_the_id();  
    $args['post_parent'] = $post_id;
  } else {
    //$args['ID'] = $post_id;
    $args['post_parent'] = $post_id;
  }  
  //bw_trace2( $args, "args" ); 
  //bw_backtrace();
  $number = intval( $number );
  $arr_attachment = get_posts( $args );
  
  if ( count( $arr_attachment ) ) {
    foreach ( $arr_attachment as $index => $attachment ) {
      $arr_attachment[$index] = array_merge ( (array) wp_get_attachment_image_src($attachment->ID, $image_size), array($attachment->ID) );
    }
  }
  //bw_trace( $arr_attachment, __FUNCTION__, __LINE__, __FILE__, "arr_attachment" );
  return $arr_attachment;
}

/**
 * Gets the "thumbnail" image for an attachment
 * 
 * This no longer sets the width and height of the image. Does this work when we want a really small image?
 * 
 * @param int $post_id - ID of the attachment
 * @param mixed $size - size of the required image
 * @param array $atts - contains the title for the attachment
 * @return string $thumbnail - either an image or just the attachment file name
 * 
 */ 
function bw_get_attachment_thumbnail( $post_id, $size, $atts ) {
	$thumbnail_arr = wp_get_attachment_image_src( $post_id, $size, false );
	$thumbnail = bw_array_get( $thumbnail_arr, 0, null);
	$text = bw_array_get( $atts, "title", NULL );
	if ( $thumbnail ) {
		$thumbnail = bw_verify_file_from_url( $thumbnail );
	}
	if ( $thumbnail ) {
		$thumbnail_arr = bw_force_size( $thumbnail_arr, $size );
		$classes = "bw_thumbnail " . bw_get_post_class( $post_id );
		$thumbnail = retimage( $classes, $thumbnail, $text, $thumbnail_arr[1], $thumbnail_arr[2] );
	} else {
		$thumbnail = wp_get_attachment_link( $post_id, $size, false, false );
	}
	bw_trace2( $thumbnail, 'thumbnail', true, BW_TRACE_VERBOSE );
	return $thumbnail;
}

/**
 * Return the post classes given the post id
 * @param ID $id 
 * @return string of post classes
 */
function bw_get_post_class( $id ) {
  $classes = get_post_class( null, $id ); 
  $classlist = implode( " ", $classes );
  return( $classlist );
}

/**
 * Returns true if the filename for an uploaded file's URL exists
 *
 * We check that the $url is prefixed with 'baseurl' then
 * convert it to 'basedir' to get the file name then that the file exists.
 *
 * Note: This code only checks the file if it's prefixed with the baseurl!
 *
 * @param $url - URL for the upload
 * @param bool $placeholder - future use
 * @return string|null
 */
function bw_verify_file_from_url( $url, $placeholder=false ) {
	//bw_trace2();
	$upload_dir = wp_get_upload_dir();
	if ( 0 === strpos( $url, $upload_dir['baseurl'] ) ) {
		$file = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $url );
		bw_trace2( $file, "file", true, BW_TRACE_VERBOSE );
		if ( file_exists( $file )) {
			// OK the link should work
		} else {
			$url = null;
		}
	}
	return $url;
}






