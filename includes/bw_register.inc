<?php // (C) Copyright Bobbing Wide 2012-2015

/**
 * Register functions for custom post types, taxonomies and fields
 */
 
/**
 * Singularize a plural string 
 * 
 * @uses bw_porter_stemmer() if it exists
 * 
 * @param string $plural - a plural string - or anything with a 'stem'
 * @return string $singular - the singularized version of the string
 */
function bw_singularize( $plural ) {
  //bw_backtrace();
  if ( function_exists( "bw_porter_stemmer" ) ) {
    $singular = bw_porter_stemmer( $plural ) ;
  } else {
    $singular = rtrim( $plural, 's' );
  }
  //bw_trace2( $singular, "Singular" );
  return( $singular );
}

/**
 * Convert a field name to a title
 *
 * @param string $name - field name e.g. _cc_custom_category
 * @return string $title - title returned e.g. Cc Custom Category
 * 
 * Converts underscores to blanks, trims and uppercases first letter of each word
 * DOES NOT remove a prefix that may match the post_type's slug 
 * So the field title may be quite long. 
 *
 * @param string $name field name to titleify
 * @return string title
 */
if ( !function_exists( "bw_titleify" ) ) {
function bw_titleify( $name ) {
  $title = str_replace( "_", " ", $name );
  $title = trim( $title ); 
  $title = ucwords( $title );  
  return( $title ); 
} 
}

/**
 * Return the Upper case first letter version of the string
 *
 * Required since ucfirst() only accepts one parameter but sometimes we pass more parameters
 * e.g. when invoked as a callback in bw_array_get_dcb()
 * 
 *
 * @param string $str - the string to pass to ucfirst()
 * @return string - the result of ucfirst()
 */
function bw_ucfirst( $str ) {
  $ucfirst = ucfirst( $str ); 
  return( $ucfirst );
}  

/**
 * Register a custom post type using as many defaults as we can
 *
 * Refer to the WordPress codex or [bw_plug name='oik-types']
 *
 * `
  'capability_type' => $cpt_post_type["capability_type"],
  'description' => esc_html($cpt_post_type["description"]),
  'has_archive' => $cpt_has_archive,
  'hierarchical' => get_disp_boolean($cpt_post_type["hierarchical"]),   
  'label' => __($cpt_label),
  'labels' => $cpt_labels
  'menu_position' => $cpt_menu_position,
  'public' => get_disp_boolean($cpt_post_type["public"]),
  'query_var' => get_disp_boolean($cpt_post_type["query_var"]),
  'rewrite' => array('slug' => $cpt_rewrite_slug),
  'show_in_menu' => $cpt_show_in_menu,
  'show_ui' => get_disp_boolean($cpt_post_type["show_ui"]),
  'singular_label' => $cpt_post_type["singular_label"],
  'supports' => $cpt_supports,
  'taxonomies' => $cpt_taxonomies,
 * `
 * @arguments                         
 * label - optional - default $post_type
 * labels - optional 
 
 * Register a post type. Do not use before init.
 *
 * A function for creating or modifying a post type based on the
 * parameters given. The function will accept an array (second optional
 * parameter), along with a string for the post type name.
 *
 * Optional $args contents:
 *
 * - label - Name of the post type shown in the menu. Usually plural. If not set, labels['name'] will be used.
 * - description - A short descriptive summary of what the post type is. Defaults to blank.
 * - public - Whether posts of this type should be shown in the admin UI. Defaults to false.
 * - exclude_from_search - Whether to exclude posts with this post type from search results.
 *     Defaults to true if the type is not public, false if the type is public.
 * - publicly_queryable - Whether post_type queries can be performed from the front page.
 *     Defaults to whatever public is set as.
 * - show_ui - Whether to generate a default UI for managing this post type. Defaults to true
 *     if the type is public, false if the type is not public.
 * - show_in_menu - Where to show the post type in the admin menu. True for a top level menu,
 *     false for no menu, or can be a top level page like 'tools.php' or 'edit.php?post_type=page'.
 *     show_ui must be true.
 * - menu_position - The position in the menu order the post type should appear. Defaults to the bottom.
 * - menu_icon - The url to the icon to be used for this menu. Defaults to use the posts icon.
 * - capability_type - The string to use to build the read, edit, and delete capabilities. Defaults to 'post'.
 *   May be passed as an array to allow for alternative plurals when using this argument as a base to construct the
 *   capabilities, e.g. array('story', 'stories').
 * - capabilities - Array of capabilities for this post type. By default the capability_type is used
 *      as a base to construct capabilities. You can see accepted values in {@link get_post_type_capabilities()}.
 * - map_meta_cap - Whether to use the internal default meta capability handling. Defaults to false.
 * - hierarchical - Whether the post type is hierarchical. Defaults to false.
 * - supports - An alias for calling add_post_type_support() directly. See {@link add_post_type_support()}
 *     for documentation. Defaults to none.
 * - register_meta_box_cb - Provide a callback function that will be called when setting up the
 *     meta boxes for the edit form.  Do remove_meta_box() and add_meta_box() calls in the callback.
 * - taxonomies - An array of taxonomy identifiers that will be registered for the post type.
 *     Default is no taxonomies. Taxonomies can be registered later with register_taxonomy() or
 *     register_taxonomy_for_object_type().
 * - labels - An array of labels for this post type. By default post labels are used for non-hierarchical
 *     types and page labels for hierarchical ones. You can see accepted values in {@link get_post_type_labels()}.
 * - permalink_epmask - The default rewrite endpoint bitmasks.
 * - has_archive - True to enable post type archives. Will generate the proper rewrite rules if rewrite is enabled.
 * - rewrite - false to prevent rewrite. Defaults to true. Use array('slug'=>$slug) to customize permastruct;
 *     default will use $post_type as slug. Other options include 'with_front', 'feeds', and 'pages'.
 * - query_var - false to prevent queries, or string to value of the query var to use for this post type
 * - can_export - true allows this post type to be exported.
 * - show_in_nav_menus - true makes this post type available for selection in navigation menus.
 * - _builtin - true if this post type is a native or "built-in" post_type. THIS IS FOR INTERNAL USE ONLY!
 * - _edit_link - URL segement to use for edit link of this post type. THIS IS FOR INTERNAL USE ONLY!
 */
function bw_register_post_type( $post_type, $post_type_args ) {
  bw_trace2( null, null, true, BW_TRACE_DEBUG );
  $post_type_args['public'] = bw_array_get( $post_type_args, 'public', true );
  $post_type_args['query_var'] = bw_array_get( $post_type_args, 'query_var', $post_type );
  $post_type_args['label'] = bw_array_get_dcb( $post_type_args, 'label', $post_type, "bw_ucfirst"  );
  $post_type_args['singular_label'] = bw_array_get_dcb( $post_type_args, 'singular_label', $post_type_args['label'] , "bw_singularize" );
  $post_type_args['labels'] = bw_array_get_dcb( $post_type_args, 'labels', array( 'singular_name' => $post_type_args['singular_label'], 'name' => $post_type_args['label'] ) , "bw_default_labels" );
  //bw_trace2();
  register_post_type( $post_type, $post_type_args );
}

/**
 * Attempt to set labels given the 'name' and/or 'singular_name'
 * 
 * When the singular_name is not just the name without the last 's' then you need to set the singular_name yourself 
 * OR let the Porter Stemmer algorithm come up with a solution.
 *
 * @uses bw_singularize
 * 
 * @param array $labels subset of the total set
 * @return array hopefully all of the required labels
 */
function bw_default_labels( $labels= array() ) {
  $ucplural = bw_array_get_dcb( $labels, 'name', 'Post', "bw_ucfirst" );
  $lcplural = strtolower( $ucplural );
  $labels['name'] = bw_array_get( $labels, 'name', $ucplural );
  $labels['menu_name'] = bw_array_get( $labels, 'menu_name', $ucplural );
  $ucsingular = bw_array_get( $labels, 'singular_name', null );
  if ( !$ucsingular ) {
    $ucsingular = bw_singularize( $ucplural );
  }
  $labels['singular_name'] = $ucsingular; 
  $labels['all_items'] = bw_array_get( $labels, 'all_items', "All $ucplural" );
  $labels['add_new'] = bw_array_get( $labels, 'add_new', "New $ucsingular" );
  $labels['add_new_item'] = bw_array_get( $labels, 'add_new_item', "Create New $ucsingular" );
  $labels['edit'] = bw_array_get( $labels, 'edit', "Edit" );
  $labels['edit_item'] = bw_array_get( $labels, 'edit_item', "Edit $ucsingular" );
  $labels['new_item'] = bw_array_get( $labels, 'new_item', "New $ucsingular" );
  $labels['view'] = bw_array_get( $labels, 'view', "View $ucsingular" );
  $labels['view_item'] = bw_array_get( $labels, 'view_item', "View $ucsingular" );
  $labels['search_items'] = bw_array_get( $labels, 'search_items', "View $ucplural" );
  $labels['not_found'] = bw_array_get( $labels, 'not_found', "No $lcplural found" );
  $labels['not_found_in_trash'] = bw_array_get( $labels, 'not_found_in_trash', "No $lcplural found in Trash" ); 
	// These labels were added in WordPress 4.3.0
  $labels['featured_image'] = bw_array_get( $labels, 'featured_image', 'Featured Image' );
	$labels['set_featured_image'] = bw_array_get( $labels, 'set_featured_image', 'Set featured image' );
	$labels['remove_featured_image'] = bw_array_get( $labels, 'remove_featured_image', 'Remove featured image' );
	$labels['use_featured_image'] = bw_array_get( $labels, 'use_featured_image', 'Use as featured image' );
  // should each array element now be translated? 
  bw_trace2( $labels, "labels", true, BW_TRACE_VERBOSE );
  return( $labels );
} 

/**
 * Set default custom taxonomy labels
 * 
 * It looks like these are the same as for post types - let's check
 * OK, there are a few more... we'll call default_labels first then.
 *
 * By default tag labels are used for non-hierarchical types and category labels for hierarchical ones.
 *
 * Default: if empty, name is set to label value, and singular_name is set to name value
 * `
x 'name' - general name for the taxonomy, usually plural. The same as and overridden by $tax->label. Default is _x( 'Post Tags', 'taxonomy general name' ) or _x( 'Categories', 'taxonomy general name' ). When internationalizing this string, please use a gettext context matching your post type. Example: _x('Writers', 'taxonomy general name');
x 'singular_name' - name for one object of this taxonomy. Default is _x( 'Post Tag', 'taxonomy singular name' ) or _x( 'Category', 'taxonomy singular name' ). When internationalizing this string, please use a gettext context matching your post type. Example: _x('Writer', 'taxonomy singular name');
x 'menu_name' - the menu name text. This string is the name to give menu items. Defaults to value of name
x 'all_items' - the all items text. Default is __( 'All Tags' ) or __( 'All Categories' )
x 'edit_item' - the edit item text. Default is __( 'Edit Tag' ) or __( 'Edit Category' )
x 'view_item' - the view item text, Default is __( 'View Tag' ) or __( 'View Category' )

  'update_item' - the update item text. Default is __( 'Update Tag' ) or __( 'Update Category' )
x 'add_new_item' - the add new item text. Default is __( 'Add New Tag' ) or __( 'Add New Category' )
  'new_item_name' - the new item name text. Default is __( 'New Tag Name' ) or __( 'New Category Name' )
  'parent_item' - the parent item text. This string is not used on non-hierarchical taxonomies such as post tags. Default is null or __( 'Parent Category' )
  'parent_item_colon' - The same as parent_item, but with colon : in the end null, __( 'Parent Category:' )
x 'search_items' - the search items text. Default is __( 'Search Tags' ) or __( 'Search Categories' )
  'popular_items' - the popular items text. Default is __( 'Popular Tags' ) or null
  'separate_items_with_commas' - the separate item with commas text used in the taxonomy meta box. This string isn't used on hierarchical taxonomies. Default is __( 'Separate tags with commas' ), or null
  'add_or_remove_items' - the add or remove items text and used in the meta box when JavaScript is disabled. This string isn't used on hierarchical taxonomies. Default is __( 'Add or remove tags' ) or null
  'choose_from_most_used' - the choose from most used text used in the taxonomy meta box. This string isn't used on hierarchical taxonomies. Default is __( 'Choose from the most used tags' ) or null
  'not_found' (3.6+) - the text displayed via clicking 'Choose from the most used tags' in the taxonomy meta box when no tags are available. This string isn't used on hierarchical taxonomies. Default is __( 'No tags found.' ) or null
 * `
 * 	
 * @param array $labels - An array of labels for this taxonomy. 
 * @return array labels with defaults applied
 */
function bw_default_taxonomy_labels( $labels = array() ) {
  $labels = bw_default_labels( $labels );
  $ucplural = bw_array_get( $labels, "name", null );
  $ucsingular =  bw_array_get( $labels, "singular_name", $ucplural );
  $labels['update_item'] = bw_array_get( $labels, "update_item" , "Update $ucplural" ); 
  $labels['new_item_name'] = bw_array_get( $labels, "new_item_name" , "New $ucsingular" ); 
  $labels['parent_item'] = bw_array_get( $labels, "parent_item" , "Parent $ucsingular" ); 
  $labels['parent_item_colon'] = bw_array_get( $labels, "parent_item_colon" , "Parent $ucsingular:"  ); 
  $labels['popular_items'] = bw_array_get( $labels, "popular_items" , "Popular $ucplural" ); 
  $labels['separate_items_with_commas'] = bw_array_get( $labels, "separate_items_with_commas", "Separate $ucplural with commas" ); 
  $labels['add_or_remove_items'] = bw_array_get( $labels, "add_or_remove_items", "Add or remove $ucplural" ); 
  $labels['choose_from_most_used'] = bw_array_get( $labels, "choose_from_most_used", "Choose from most used $ucplural" );
  // $labels['not_found'] = bw_array_get( $labels, "not_found", "No $ucplural found" );
  //bw_trace2();
  return( $labels );
}  

/**
 * Set default args for a taxonomy
 * 
 * @param string $taxonomy - the taxonomy name e.g. "country" 
 * @param array $args - parameters for the taxonomy
 * @return array - updated args
 */
function bw_default_taxonomy_args( $taxonomy, $arg ) {
  //bw_trace2();
  //bw_backtrace();
  if ( !is_array( $arg ) ) {
    $labels = array( "name" => $arg );
  } else { 
    $labels = bw_array_get( $arg, "labels", null );
    if ( null == $labels ) { 
      $labels = array( "name" => bw_titleify( $taxonomy ) );
    }  
  }   
  $args["labels"] = bw_default_taxonomy_labels( $labels );
  return( $args ); 
} 

/**
 * Register a custom taxonomy 
 * 
 * This function registers a custom $taxonomy for an $object_type with $args as specified
 * If the $args parameter is a simple string then it's used to auto generate the "label" and "labels" values for the custom taxonomy
 * You can set $args yourself or let the system create sensible default values
 *
 * @param string $taxonomy - custom taxonomy name
 * @param string $object_type - the post type for which this taxonomy applies
 * @param mixed $args - array of arguments passed to register_taxonomy() 
 */
function bw_register_taxonomy( $taxonomy, $object_type=NULL, $args=NULL ) {
  //bw_trace2( $args );  
  if ( !bw_query_taxonomy( $taxonomy ) ) {
    register_taxonomy( $taxonomy, $object_type, $args );
  }  
  $field_title = bw_array_get( $args['labels'], 'name', $taxonomy );
  bw_register_field( $taxonomy, "taxonomy", $field_title, $args );
}

/**
 * Query the registered taxonomies to see if this is registered
 *
 * @param string $taxonomy - the taxonomy name
 * @return bool - true if registered, false if not. 
 *   
 */
function bw_query_taxonomy( $taxonomy=null ) {
  global $wp_taxonomies;
  //bw_trace2( $wp_taxonomies );
  $exists = taxonomy_exists( $taxonomy );
  return( $exists );
}

/** 
 * Register a custom "tags" taxonomy
 * 
 * @param string $taxonomy - the taxonomy name
 * @param array|string $object_type - post types to associate the taxonomy to
 * @param mixed - $args for the taxonomy
 */
function bw_register_custom_tags( $taxonomy, $object_type=null, $arg=null ) {
  //bw_trace2();
  $args = bw_default_taxonomy_args( $taxonomy, $arg );
  $args['hierarchical'] = false;
  bw_register_taxonomy( $taxonomy, $object_type, $args) ; 
}

/**
 * Register a custom "category" taxonomy
 *
 * @param string $taxonomy - the taxonomy name
 * @param array|string $object_type - post types to associate the taxonomy to
 * @param mixed - $args for the taxonomy
 */
function bw_register_custom_category( $taxonomy, $object_type=null, $arg=null ) {
  $args = bw_default_taxonomy_args( $taxonomy, $arg );
  $args['hierarchical'] = true;
  bw_register_taxonomy( $taxonomy, $object_type, $args ); 
}

/** 
 * Register a field named $field_name for object type $object_type
 *
 * @TODO - determine is this works for "taxonomy" field types as well
 * 
 * @param string $field_name - meta_key of the field - precede with an underscore so it's not in custom fields
 * @param string $object_type - object type
 */
function bw_register_field_for_object_type( $field_name, $object_type ) {
  global $bw_mapping;
  $bw_mapping['field'][$object_type][$field_name] = $field_name;
  $bw_mapping['type'][$field_name][$object_type] = $object_type;
}  

/** 
 * Register a field named $field_name of type $field_type with title $field_title and additional values $args
 *
 * Arg name | Purpose
 * --- | -------
 * #length | for text fields
 * #validate | for any field
 * #type | for noderef type fields 
 * #options | for select type fields
 * #multiple | for multiple select fields
 * #form | bool - whether or not to display the field on an "Add New" form - defaults to true 
 * #theme | bool - whether or not to display the field during "theming" - defaults to true
 * #theme_null | bool - whether or not to display the field when null - defaults to true
 * #hint | Field hint
 *
 * Additional parameters for virtual fields
 *
 * Arg name | Purpose
 * --------- | ---------
 * #callback | callback function for virtual fields
 * #parms | metadata field to pass to callback function
 * #plugin | implementing plugin for #callback
 * #file | file in implementing plugin for #callback
 *
 * @param string $field_name - meta_key of the field - precede with an underscore so it's not in custom fields
 * @param string $field_type - type of field e.g. text, textarea, radio button
 * @param string $field_title - title of the field
 * @param string $args - $field_type specific values
 */
function bw_register_field( $field_name, $field_type, $field_title, $args=NULL ) {
  global $bw_fields;
  $data = array( '#field_type' => $field_type,
                 '#title' => $field_title, 
                 '#args' => $args, 
               );
  $bw_fields[$field_name] = $data;
}

/**
 * Return a list of defined fields 
 *
 * @return array associated array of field names
 */
function bw_list_fields() {
  global $bw_fields;
  $fields = bw_assoc( array_keys( $bw_fields ));
  return( $fields ); 
}



 
  

