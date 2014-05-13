<?php
if ( defined( 'OIK_HEADER_INC_INCLUDED' ) ) return;
define( 'OIK_HEADER_INC_INCLUDED', true );

oik_require( 'bobbforms.inc' );
  
add_action( 'add_meta_boxes', 'bw_oik_header_meta_boxes' );

/* Do something with the data entered */
add_action( 'save_post', 'bw_oik_header_save_postdata' );


function bw_oik_header_meta_boxes() {
  // add_meta_box( 'bw_oik_header', 'Custom header image', 'bw_oik_header_box', 'page' );
  add_meta_box( 'bw_oik_header2', 'Custom header image', 'bw_oik_header_box2', 'page' );
  add_meta_box( 'bw_oik_header2', 'Custom header image', 'bw_oik_header_box2', 'post' );
}

/**
 * Initially we'll just start off with an entry field
 * ... just like on the links page
 * Then we'll make it work like "Choose featured image"
 
 */
function bw_oik_header_box( $post ) {

  $value = get_post_meta( $post->ID, '_bw_header_image', TRUE );
  textfield( '_bw_header_image', 80, "Custom header image", $value );
  echo( bw_ret());


}

if ( !function_exists( "bw_authorized" ) ) {
  function bw_authorized() {
    $authorized = TRUE;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        $authorized = FALSE;
        
    // if ( !wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename( __FILE__ ) ) )
    return $authorized;
  }
}

function bw_oik_header_save_postdata( $post_id ) {
  if ( bw_authorized() ) {
    $mydata = bw_array_get($_POST, '_bw_header_image', NULL ) ;
    update_post_meta( $post_id, '_bw_header_image', $mydata );
    bw_trace2( $mydata );
  } 
}


/**
 * Output the HTML for the custom header image page
 *
 * we're working towards building a meta box that will
 * display the current image - if defined
 * allow selection of an image using the thickbox dialog ( based on set-post-thumbnail ) 
 * allow deletion of the image using JavaScript ( based on WPRemoveThumbnail )
 */
function bw_oik_header_box2( $post ) {
  $value = get_post_meta( $post->ID, '_bw_header_image', TRUE );
  textfield( '_bw_header_image', 80, "Custom header image", $value );
  
  if ( $value ) {
    $image_link = retimage( "bw-header-box", $value, "Custom header image" );  
    sp( "hide-if-no-js" );
    alink( "thickbox", esc_url( get_upload_iframe_src('image')), $image_link, "Set custom header", 'set-post-thumbnail'  ) ;
    
    // Don't include the Delete link yet
    //$ajax_nonce = wp_create_nonce( "set_post_thumbnail-$post->ID" );
    //$extra = 'onclick="WPRemoveThumbnail(\'' . $ajax_nonce . '\');return false;"';
    //alink( NULL, '#', "Remove custom header", NULL, 'remove-post-thumbnail', $extra ); 
    ep();
  }
  
  echo( bw_ret());
}


/*
	global $content_width, $_wp_additional_image_sizes, $post_ID;
        
        
	$set_thumbnail_link = '<p class="hide-if-no-js">
        <a title="' . esc_attr__( 'Set featured image' ) . '" href="' . esc_url( get_upload_iframe_src('image') ) . '" id="set-post-thumbnail" class="thickbox">%s</a></p>';
	$content = sprintf($set_thumbnail_link, esc_html__( 'Set featured image' ));

	if ( $thumbnail_id && get_post( $thumbnail_id ) ) {
		$old_content_width = $content_width;
		$content_width = 266;
		if ( !isset( $_wp_additional_image_sizes['post-thumbnail'] ) )
			$thumbnail_html = wp_get_attachment_image( $thumbnail_id, array( $content_width, $content_width ) );
		else
			$thumbnail_html = wp_get_attachment_image( $thumbnail_id, 'post-thumbnail' );
		if ( !empty( $thumbnail_html ) ) {
			$ajax_nonce = wp_create_nonce( "set_post_thumbnail-$post_ID" );
			$content = sprintf($set_thumbnail_link, $thumbnail_html);
			$content .= '<p class="hide-if-no-js"><a href="#" id="remove-post-thumbnail" onclick="WPRemoveThumbnail(\'' . $ajax_nonce . '\');return false;">' . esc_html__( 'Remove featured image' ) . '</a></p>';
		}
		$content_width = $old_content_width;
	}

	return apply_filters( 'admin_post_thumbnail_html', $content );
}
*/
