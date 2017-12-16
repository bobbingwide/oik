<?php // (C) Copyright Bobbing Wide 2012-2017
/**
 * Logic to create metaboxes for each of the custom fields defined for a particular post type
 *
 * Note: These functions are prefixed bw_effort for historical reasons 
 * 
 */

/** 
 * Function to invoke when includes/bw_metadata.php is loaded
 *
 * We create generic meta boxes for fields
 * with a generic function to save fields
 * 
 */
function bw_metadata_loaded() {
  add_action( 'add_meta_boxes', 'bw_effort_meta_boxes', 10, 2 );
  /* Do something with the data entered */
  add_action( 'save_post', 'bw_effort_save_postdata', 10, 3 );
}

/**
 * Create "Fields" meta box for each field associated with each object type in the mapping
 *
 * We only need to create a Fields box if we have fields for the active post_type.
 * The $bw_mapping['fields'][$post_type] array will not exist until a field has been added. for the post type.
 * We also check that $bw_mapping contains 'fields'
 *
 * @param string $post_type - the post type being edited
 * @param object $post - the post being edited
 */
function bw_effort_meta_boxes( $post_type, $post ) {
  oik_require( 'bobbforms.inc' );
  global $bw_mapping;
  bw_trace2( $bw_mapping );
  if ( is_array( $bw_mapping ) && count( $bw_mapping['field'] ) ) {
    $fields = bw_array_get( $bw_mapping['field'], $post_type, null );
    if ( $fields ) {
      add_meta_box( 'bw_effort', __( "Fields", "oik"), 'bw_effort_box', $post_type, 'normal' , 'high' , $fields );
    }
  }  
}

/**
 * Return a default value from $args if $value is not set
 *
 * @param string $value the current value
 * @param array $args an array containing the default value
 * @returns string $value
 */
function bw_default_value( $value, $args ) {
  if ( !$value ) {
    $value = bw_array_get( $args, '#default', null );
  }
  return( $value );
}

/**
 * bw_form_field_ - default formatting for a field - as a textfield
 *
 * e.g. textfield( '_bw_header_image', 80, "Custom header image", $value );
 *
 * @param string $name Field name
 * @param string $type Field type
 * @param string $title Field label - l10n'ed
 * @param string $value Field value
 * @param array $args a load of options
 */
function bw_form_field_( $name, $type, $title, $value, $args ) {
  $length = bw_array_get( $args, '#length', 40 );
  $value = bw_default_value( $value, $args );
  $class = bw_array_get( $args, "#class", null );
  $extras = bw_array_get( $args, "#extras", null );
  BW_::bw_textfield( $name, $length, $title, $value, $class, $extras, $args );
} 

/**
 * bw_form_field_email - formatting for an email entry field
 * 
 * @param string $name Field name
 * @param string $type Field type
 * @param string $title Field label - l10n'ed
 * @param string $value Field value
 * @param array $args a load of options
 */
function bw_form_field_email( $name, $type, $title, $value, $args ) {
  $length = bw_array_get( $args, '#length', 40 );
  $value = bw_default_value( $value, $args );
  $class = bw_array_get( $args, "#class", null );
  $extras = bw_array_get( $args, "#extras", null );
  BW_::bw_emailfield( $name, $length, $title, $value, $class, $extras );
}

/**
 * bw_form_field_select - formatting for a field as a select list
 *
 * The options come from $args[#options] 
 */
function bw_form_field_select( $name, $type, $title, $value, $args ) {
  bw_select( $name, $title, $value, $args );
}

/** 
 * Build a simple ID, title array from an array of $post objects
 * @param array $post - array of post objects
 * @return array - associative array of post ID to post_title
 */
function bw_post_array( $posts ) {
  $options = array();
  foreach ($posts as $post ) {
    $options[$post->ID] = $post->post_title; 
  }
  return bw_trace2( $options );
}

/** 
 * Build a simple ID, title array from an array of $term objects
 * @param array $term - array of term objects
 * @return array - associative array of term_id to name
 */
function bw_term_array( $terms ) {
  $options = array();
  if ( count( $terms ) ) {
    foreach ($terms as $term ) {
      $options[$term->term_id] = $term->name; 
    }
  }
  return bw_trace2( $options );
}

/**
 * Load an array of node references
 *
 * @param string/array $args - array of args containing the #type of the node to load
 * Note: #type may also be an array of post_types
 * @returns array $options - array of nodes keyed by ID with value of title
 *
 * Note: You can simply pass this as a string if you so wish 
 * 
 * **?** This is probably an inefficient use of get_posts, especially for very large lists
 * **?** Could we not just pass $args to bw_get_posts to allow the returned list to be fine tuned
 * 
 * 
 */
function bw_load_noderef( $args ) {
  oik_require( "includes/bw_posts.php" );
  $post_types = array();
  $post_type = bw_array_get( $args, '#type', $args );
  if ( is_array( $post_type ) ) { 
    $post_types = $post_type;
  } else {
    $post_types[] = $post_type;
  }   
    
  $options = array();
  foreach ( $post_types as $post_type ) {    
    if ( $post_type !== "attachment" ) {
      $args['post_parent'] = 0; 
    } else {
      unset( $args['post_parent'] );
    }
    $args['post_type'] = $post_type;
    $posts = bw_get_posts( $args );
    $options += bw_post_array( $posts );
  }
  return( $options );
}

/**
 * Return a noderef ID from a post
 * 
 * Temporary fix: if the $value is an object then we've got it wrong creating it. Try to extract the ID.
 * If this fails to get the ID then don't worry. Something else might go wrong later.
 *
 * @param ID|post - if the $value is an object then we get the ID property
 * @return ID - we hope we can return the ID of the object
 */
function bw_get_noderef_id( $value ) {
  if ( is_object( $value ) ) {
    if ( property_exists( $value, "ID" ) ) {
      $value = $value->ID;
    }
  }
  return( $value );
}

/**
 * Form a 'noderef' field type
 * 
 * bw_form_field_noderef - formatting for a field as a select list of a list of nodes of a particular type
 * The options come from $args[#type] 
 *
 * 
 */
function bw_form_field_noderef( $name, $type, $title, $value, $args ) {
  oik_require( "includes/bw_noderef2.php" );
  $options = bw_load_noderef2( $args );
  $args['#options'] =  $options;
  $value = bw_get_noderef_id( $value );
  bw_select( $name, $title, $value, $args );
}
   
/**
 * Enqueue the datepicker script
 * 
 * Enqueue the debug script if needed otherwise enqueue the minified (packed) one
 */
if ( !function_exists( "bw_datepicker_enqueue_script" ) ) {
function bw_datepicker_enqueue_script( ) {
  if ( defined('SCRIPT_DEBUG' ) && SCRIPT_DEBUG == true) {
    wp_enqueue_script( 'jquery-ui-datepicker' );
  } else {
    wp_enqueue_script( 'jquery-ui-datepicker' );
  } 
}
} 

/** 
 * bw_form_field_date - format a date field
 *
 * **?** oik v2.0 was delivered with jquery-ui-1.9.2.custom.css but originally expected jquery.ui.theme.css. How do we manage this in the future? 2013/06/26
 *
 */
if ( !function_exists( "bw_form_field_date" ) ) { 
function bw_form_field_date( $name, $type, $title, $value, $args ) {
  $args['#length'] = bw_array_get( $args, '#length', 10 );
  wp_enqueue_style( "jquery-ui-datepicker-css", plugin_dir_url( __FILE__). "css/jquery.ui.datepicker.css" ); 
  //wp_enqueue_style( "jquery-ui-theme-css", plugin_dir_url( __FILE__). "css/jquery.ui.theme.css" );
  wp_enqueue_style( "jquery-ui-theme-css", oik_url( "css/jquery-ui-1.9.2.custom.css" ) );
   
  bw_datepicker_enqueue_script();
  bw_jquery( "#${name}", "datepicker", bw_jkv( "dateFormat : 'yy-mm-dd', changeMonth: true, changeYear: true" ) );
  if ( $value ) {
    $value = bw_format_date( $value );
  }
  bw_form_field_( $name, $type, $title, $value, $args ); 
}
}

/** 
 * Format a numeric field metabox
 *
 */
function bw_form_field_numeric( $name, $type, $title, $value, $args ) {
  $args['#length'] = bw_array_get( $args, '#length', 10 );
  $args['#type'] = bw_array_get( $args, '#type', $type );
  bw_form_field_( $name, $type, $title, $value, $args ); 
}
 
/* 
 * Format a checkbox metabox
 */
function bw_form_field_checkbox( $name, $type, $title, $value, $args ) {
  bw_checkbox( $name, $title, $value );
}

/**
 * Format a textarea metabox
 *
 * @param string $name field name
 * @param string $type field type
 * @param string $title field title
 * @param string $value field value
 * @param array $args additional parameters
 */
function bw_form_field_textarea( $name, $type, $title, $value, $args ) {
  $len = bw_array_get( $args, "#length", 80 );
  BW_::bw_textarea( $name, $len, $title, $value, 10, $args ); 
}

/**
 * Request plugins to load their field forming functions
 *
 * @uses action "oik_pre_form_field" 
 * Any plugin that provides a field forming function should respond to the "oik_pre_form_field" action to dynamically load the hook that 
 * will be called to form the field. 
 * @See bw_form_function() for hook name suggestions.
 * 
 */
function bw_pre_form_field() {
  static $bw_pre_form_field = 0;
  if ( 0 === $bw_pre_form_field ) {

      /**
       * Request plugins to load field forming functions
       *
       * Any plugin that provides a field forming function should respond to this action by loading the hooks that may be called to form fields.
       */
      do_action( "oik_pre_form_field" );
  }
  $bw_pre_form_field++;
}

/** 
 * Return the function name to be used to 'form' the field
 * 
 * 
 * We append to the $prefix function name to find the most precise name that exists:
 * 
 * $prefix            - default function if the field type is not known
 * $prefix$field_type - e.g. bw_form_field_text or bw_form_field_url
 * $prefix$field_name - e.g. bw_form_field__task_ref
 *
 * @param string $prefix - the prefix for the function name
 * @param string $field_type - type of the field to form
 * @param string $field_name - name of the field to form
 
 * @returns string $funcname - the name of the function to invoke
 * 
 */
function bw_form_function( $prefix="bw_form_field_", $field_type= 'text', $field_name = NULL ) {
  bw_pre_form_field();
  $funcname = $prefix;
  
  $testname = $funcname . $field_type;
  if ( function_exists( $testname ) ) 
    $funcname = $testname;
    
  $testname = $prefix . $field_name; 
  if ( function_exists( $testname ))
    $funcname = $testname;
    
  return bw_trace2( $funcname );
} 

/**
 * Append any 'hint' to the field_title 
 * 
 * - If there is a 'hint' or '#hint' in the $args then append it to the $field_title within a span with class "bw_hint" 
 * - Don't translate 'hint' 
 * - but do translate '#hint'
 * - '#hint' is being deprecated.
 * 
 * @param string $field_title - the title of the field
 * @param array $args - array of args, possibly containing a key of 'hint' or '#hint' (deprecated)
 * @return string $field_title - the updated field title. Note: A single non-blank space character is prepended to the hint text.
 */
function _bw_form_field_title( $field_title, $args ) {
	$hint = bw_array_get( $args, "hint", null );
	if ( $hint ) {
		$sphint = "&nbsp;";
		$sphint .= retstag( "span", "bw_hint" );
		$sphint .= $hint;
		$sphint .= retetag( "span" ); 
		$field_title .= $sphint;
	} else {
		$hint = bw_array_get( $args, "#hint", null ); 
		if ( $hint ) {
			$sphint = "&nbsp;";
			$sphint .= retstag( "span", "bw_hint" );
			$sphint .= bw_tt( $hint );
			$sphint .= retetag( "span" ); 
			$field_title .= $sphint;
		}
	}   
  return( $field_title );
} 

/**
 * Customizes the field title
 * 
 * - Applies the "oik_form_field_title_${field_name}" filter(s)
 * - Appends any 'hint'
 * 
 * @TODO Or use the value specified in the $args
 *
 * @param string $field_name The field name
 * @param string $field_title the translated field title
 * @param string $field_type the field type
 * @param string $field_value the current field value
 * @param array $args additional parameters
 * @return string customized field title
 */
function bw_l10n_field_title( $field_name, $field_title, $field_type, $field_value, $args=null ) {
  $field_title = apply_filters( "oik_form_field_title_${field_name}", $field_title, $field_type );
  $field_title = _bw_form_field_title( $field_title, $args ); 
  return( $field_title );
}

/** 
 * Display a field in a form for the user to enter/choose a value
 * 
 * @param string $field_name - eg. post_title or 
 * @param string $field_type - eg. text, checkbox
 * @param string $field_title - e.g. "Title" 
 * @param string $field_value - current value to display
 * @param array $args - any additional arguments for the field formatter
 */
function bw_form_field( $field_name, $field_type, $field_title, $field_value, $args=NULL ) {
  $field_title = bw_l10n_field_title( $field_name, $field_title, $field_type, $field_value, $args );
  $funcname = bw_form_function( "bw_form_field_", $field_type, $field_name );
	bw_trace2( $funcname, "funcname", true, BW_TRACE_VERBOSE );
	if ( $funcname ) {
		$funcname( $field_name, $field_type, $field_title, $field_value, $args );
	}
}

/**
 * Check if the field should be displayed in the metabox
 *
 * Currently we only prevent "taxonomy" fields from appearing in the metabox
 * This may need to be extended to 'fieldref' fields, which are forms of virtual fields
 * though we may choose to provide a different interface to allow these to be set.
 * 
 * @param array $data - Containing the #field_type and other values in #args
 * @return bool - true if the field should be 'formed' in the metabox
 */
function bw_check_metabox_field( $data ) {
  bw_trace2();
  $metabox_field = true;
  switch ( $data['#field_type'] ) {
    case 'taxonomy':
      $metabox_field = false;
  }
  return( $metabox_field );
}

/**
 * Create fields in the meta box to accept data for the fields
 *
 * This should not display 'taxonomy' fields as they appear in their own metaboxes.
 * 
 * @TODO Similarly 'virtual' fields should not have their values loaded using get_post_meta()
 * @TODO Worry about Fields metaboxes which don't have any fields simply because they're all (custom) taxonomies
 *
 * @param post $post - the post object
 * @param array $args -	contains 'args' - array of fields to display
 */
function bw_effort_box( $post, $args ) {
  global $bw_fields; 
  $fields = $args['args'];
  //bw_trace2( $fields );
  stag( 'table', "form-table" );
  foreach ( $fields as $field ) {
    $data = $bw_fields[$field];
    //bw_trace2( $field );
    $metabox_field = bw_check_metabox_field( $data );
    if ( $metabox_field ) {
      $multiple = bw_array_get( $data['#args'], "#multiple", false );
      $value = get_post_meta( $post->ID, $field, !$multiple );
      bw_form_field( $field, $data['#field_type'], $data['#title'], $value, $data['#args'] );
    }  
  }
  etag( "table" );
  echo( bw_ret());
}

/**
 * Perform simple authorization
 *
 * **?** Is this function actually used? 
 *
 * @return book - true if authorized
 */
if ( !function_exists( "bw_authorized" ) ) {
  function bw_authorized() {
    $authorized = TRUE;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        $authorized = FALSE;
        
    // if ( !wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename( __FILE__ ) ) )
    return $authorized;
  }
}

/**
 * Saves the custom fields for this post when called for the 'save_post' action
 *
 * From the $post we can determine the $post_type of the object being saved
 * and therefore determine the custom fields from the $bw_mapping global.
 * 
 * For each field defined for the post type we obtain the value from the $_POST and
 * update the post meta data. 
 * 
 * Implementers of 'save_post_$post_type' should expect to be called multiple times
 * first by wp_insert_post() then again by this routine.
 * 
 * @param integer $post_id The ID of this post
 * @param object $post the post object
 * @param bool $update
 */
function bw_effort_save_postdata( $post_id, $post, $update ) {
  if ( bw_authorized() ) {
    $post_type = $post->post_type; 
    
    //bw_trace2( $_POST, "_POST" );
    //bw_trace( $post_type, __FUNCTION__, __LINE__, __FILE__, "post->post_type" );
    /**
     * Save the custom fields for this post
     * @param integer $post_id The ID of this post
     * @param object $post the post object
     */
    do_action( "save_post_${post_type}", $post_id, $post, $update );
  
    global $bw_mapping; 
    //bw_trace( $bw_mapping, __FUNCTION__, __LINE__, __FILE__, "bw_mapping" );
    if ( isset(  $bw_mapping['field'][$post_type] )) {
      foreach ( $bw_mapping['field'][$post_type] as $field ) {
        bw_effort_update_field( $post_id, $field );
      }
    }
  } else {
  }   
}

/**
 * Update post_meta for fields with multiple values
 * 
 * Delete any existing meta data values then add the new ones, if any
 *  
 * @param ID $post_id
 * @param string $field - the field name - often preceded with an '_'
 * @param array $mydata - the set of new values 
 */
function bw_update_post_meta( $post_id, $field, $mydata ) {
  delete_post_meta( $post_id, $field );
  if ( is_array( $mydata) && count( $mydata ) ) {
    foreach ( $mydata as $key => $value ) {
      add_post_meta( $post_id, $field, $value, false ); 
    }
  }
}

/**
 * Update the value/values for a field 
 * 
 * What happens if the field is blank **?** 
 * 
 * @param ID $post_id - the ID of the post being saved
 * @param  
 * 
 */
function bw_effort_update_field( $post_id, $field ) {
  global $bw_mapping;
  global $bw_fields;
  //bw_trace2( $bw_fields, "bw_fields" ); 
  $mydata = bw_array_get( $_POST, $field, NULL ) ;
  //bw_trace( $mydata, __FUNCTION__, __LINE__, __FILE__, "mydata" );
  if ( $mydata != null ) {
    if ( is_array( $mydata ) ) {
      bw_update_post_meta( $post_id, $field, $mydata );
    } else {
      update_post_meta( $post_id, $field, $mydata );
    }
  } else {
    //
  } 
}

/* 

    [_oik_api_calls] => Array
        (
            [#field_type] => noderef
            [#title] => Uses APIs
            [#args] => Array
                (
                    [#type] => oik_api
                    [#multiple] => 1
                )

        )
*/        

bw_metadata_loaded();
