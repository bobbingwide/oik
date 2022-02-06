<?php // (C) Copyright Bobbing Wide 2012-2017,2020

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
 * e.g. when invoked as a callback in `bw_array_get_dcb()`
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
 * Note: Post type registrations should not be hooked before the
 * {@see 'init'} action. Also, any taxonomy connections should be
 * registered via the `$taxonomies` argument to ensure consistency
 * when hooks such as {@see 'parse_query'} or {@see 'pre_get_posts'}
 * are used.
 *
 * Post types can support any number of built-in core features such
 * as meta boxes, custom fields, post thumbnails, post statuses,
 * comments, and more. See the `$supports` argument for a complete
 * list of supported features.
 *
 * Notes: 
 * - Whereas register_post_type accepts a string of arguments as the second parameter we do not.
 * - Do not use before init
 * - Now unsets 'cap' which may have been erroneously passed instead of 'capabilities' 
 * 
 * @since 2.9.0
 * @since 3.0.0 The `show_ui` argument is now enforced on the new post screen.
 * @since 4.4.0 The `show_ui` argument is now enforced on the post type listing
 *              screen and post editing screen.
 * @since 4.6.0 Post type object returned is now an instance of WP_Post_Type.
 *
 * @global array $wp_post_types List of post types.
 *
 * @param string $post_type Post type key. Must not exceed 20 characters and may
 *                          only contain lowercase alphanumeric characters, dashes,
 *                          and underscores. See sanitize_key().
 * @param array|string $post_type_args { Array of arguments for registering a post type.
 *     @type string $label Name of the post type shown in the menu. Usually plural.
 *                                             Default is value of $labels['name'].
 *     @type array       $labels               An array of labels for this post type. If not set, post
 *                                             labels are inherited for non-hierarchical types and page
 *                                             labels for hierarchical ones. See get_post_type_labels() for a full
 *                                             list of supported labels.
 *     @type string      $description          A short descriptive summary of what the post type is.
 *                                             Default empty.
 *     @type bool        $public               Whether a post type is intended for use publicly either via
 *                                             the admin interface or by front-end users. While the default
 *                                             settings of $exclude_from_search, $publicly_queryable, $show_ui,
 *                                             and $show_in_nav_menus are inherited from public, each does not
 *                                             rely on this relationship and controls a very specific intention.
 *                                             Default false.
 *     @type bool        $hierarchical         Whether the post type is hierarchical (e.g. page). Default false.
 *     @type bool        $exclude_from_search  Whether to exclude posts with this post type from front end search
 *                                             results. Default is the opposite value of $public.
 *     @type bool        $publicly_queryable   Whether queries can be performed on the front end for the post type
 *                                             as part of parse_request(). Endpoints would include:
 *                                             * ?post_type={post_type_key}
 *                                             * ?{post_type_key}={single_post_slug}
 *                                             * ?{post_type_query_var}={single_post_slug}
 *                                             If not set, the default is inherited from $public.
 *     @type bool        $show_ui              Whether to generate and allow a UI for managing this post type in the
 *                                             admin. Default is value of $public.
 *     @type bool        $show_in_menu         Where to show the post type in the admin menu. To work, $show_ui
 *                                             must be true. If true, the post type is shown in its own top level
 *                                             menu. If false, no menu is shown. If a string of an existing top
 *                                             level menu (eg. 'tools.php' or 'edit.php?post_type=page'), the post
 *                                             type will be placed as a sub-menu of that.
 *                                             Default is value of $show_ui.
 *     @type bool        $show_in_nav_menus    Makes this post type available for selection in navigation menus.
 *                                             Default is value $public.
 *     @type bool        $show_in_admin_bar    Makes this post type available via the admin bar. Default is value
 *                                             of $show_in_menu.
 *     @type int         $menu_position        The position in the menu order the post type should appear. To work,
 *                                             $show_in_menu must be true. Default null (at the bottom).
 *     @type string      $menu_icon            The url to the icon to be used for this menu. Pass a base64-encoded
 *                                             SVG using a data URI, which will be colored to match the color scheme
 *                                             -- this should begin with 'data:image/svg+xml;base64,'. Pass the name
 *                                             of a Dashicons helper class to use a font icon, e.g.
 *                                             'dashicons-chart-pie'. Pass 'none' to leave div.wp-menu-image empty
 *                                             so an icon can be added via CSS. Defaults to use the posts icon.
 *     @type string      $capability_type      The string to use to build the read, edit, and delete capabilities.
 *                                             May be passed as an array to allow for alternative plurals when using
 *                                             this argument as a base to construct the capabilities, e.g.
 *                                             array('story', 'stories'). Default 'post'.
 *     @type array       $capabilities         Array of capabilities for this post type. $capability_type is used
 *                                             as a base to construct capabilities by default.
 *                                             See get_post_type_capabilities().
 *     @type bool        $map_meta_cap         Whether to use the internal default meta capability handling.
 *                                             Default false.
 *     @type array       $supports             Core feature(s) the post type supports. Serves as an alias for calling
 *                                             add_post_type_support() directly. Core features include 'title',
 *                                             'editor', 'comments', 'revisions', 'trackbacks', 'author', 'excerpt',
 *                                             'page-attributes', 'thumbnail', 'custom-fields', and 'post-formats'.
 *                                             Additionally, the 'revisions' feature dictates whether the post type
 *                                             will store revisions, and the 'comments' feature dictates whether the
 *                                             comments count will show on the edit screen. Defaults is an array
 *                                             containing 'title' and 'editor'.
 *     @type callable    $register_meta_box_cb Provide a callback function that sets up the meta boxes for the
 *                                             edit form. Do remove_meta_box() and add_meta_box() calls in the
 *                                             callback. Default null.
 *     @type array       $taxonomies           An array of taxonomy identifiers that will be registered for the
 *                                             post type. Taxonomies can be registered later with register_taxonomy()
 *                                             or register_taxonomy_for_object_type().
 *                                             Default empty array.
 *     @type bool|string $has_archive          Whether there should be post type archives, or if a string, the
 *                                             archive slug to use. Will generate the proper rewrite rules if
 *                                             $rewrite is enabled. Default false.
 *     @type bool|array  $rewrite              {
 *         Triggers the handling of rewrites for this post type. To prevent rewrite, set to false.
 *         Defaults to true, using $post_type as slug. To specify rewrite rules, an array can be
 *         passed with any of these keys:
 *
 *         @type string $slug       Customize the permastruct slug. Defaults to $post_type key.
 *         @type bool   $with_front Whether the permastruct should be prepended with WP_Rewrite::$front.
 *                                  Default true.
 *         @type bool   $feeds      Whether the feed permastruct should be built for this post type.
 *                                  Default is value of $has_archive.
 *         @type bool   $pages      Whether the permastruct should provide for pagination. Default true.
 *         @type const  $ep_mask    Endpoint mask to assign. If not specified and permalink_epmask is set,
 *                                  inherits from $permalink_epmask. If not specified and permalink_epmask
 *                                  is not set, defaults to EP_PERMALINK.
 *     }
 *     @type string|bool $query_var            Sets the query_var key for this post type. Defaults to $post_type
 *                                             key. If false, a post type cannot be loaded at
 *                                             ?{query_var}={post_slug}. If specified as a string, the query
 *                                             ?{query_var_string}={post_slug} will be valid.
 *     @type bool        $can_export           Whether to allow this post type to be exported. Default true.
 *     @type bool        $delete_with_user     Whether to delete posts of this type when deleting a user. If true,
 *                                             posts of this type belonging to the user will be moved to trash
 *                                             when then user is deleted. If false, posts of this type belonging
 *                                             to the user will *not* be trashed or deleted. If not set (the default),
 *                                             posts are trashed if post_type_supports('author'). Otherwise posts
 *                                             are not trashed or deleted. Default null.
 *     @type bool        $_builtin             FOR INTERNAL USE ONLY! True if this post type is a native or
 *                                             "built-in" post_type. Default false.
 *     @type string      $_edit_link           FOR INTERNAL USE ONLY! URL segment to use for edit link of
 *                                             this post type. Default 'post.php?post=%d'.
 * }
 * @return WP_Post_Type|WP_Error The registered post type object, or an error object.
 *
 */
function bw_register_post_type( $post_type, $post_type_args ) {
  bw_trace2( null, null, true, BW_TRACE_VERBOSE );
  $post_type_args['public'] = bw_array_get( $post_type_args, 'public', true );
  $post_type_args['query_var'] = bw_array_get( $post_type_args, 'query_var', $post_type );
  $post_type_args['label'] = bw_array_get_dcb( $post_type_args, 'label', $post_type, "bw_ucfirst"  );
  $post_type_args['singular_label'] = bw_array_get_dcb( $post_type_args, 'singular_label', $post_type_args['label'] , "bw_singularize" );
  $post_type_args['labels'] = bw_array_get_dcb( $post_type_args, 'labels', array( 'singular_name' => $post_type_args['singular_label'], 'name' => $post_type_args['label'] ) , "bw_default_labels" );
  $post_type_args['show_in_nav_menus'] = bw_array_get( $post_type_args, 'show_in_nav_menus', false);
  unset( $post_type_args['cap'] ) ;
  // bw_trace2( $post_type_args, "post_type_args");
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
  $ucplural = bw_array_get( $labels, 'name', null );
	if ( !$ucplural ) {
		$ucplural = __( 'Posts', "oik" );

	} else {
		$ucplural = bw_ucfirst( $ucplural );
	}
  $lcplural = strtolower( $ucplural );
  $labels['name'] = bw_array_get( $labels, 'name', $ucplural );
  $labels['menu_name'] = bw_array_get( $labels, 'menu_name', $ucplural );
  $ucsingular = bw_array_get( $labels, 'singular_name', null );
  if ( !$ucsingular ) {
    $ucsingular = bw_singularize( $ucplural );
  }
  $labels['singular_name'] = $ucsingular; 
  $labels['all_items'] = bw_array_get( $labels, 'all_items', sprintf( __( 'All %1$s', "oik" ), $ucplural ) );
  $labels['add_new'] = bw_array_get( $labels, 'add_new', sprintf( __( 'New %1$s', "oik" ), $ucsingular ) );
  $labels['add_new_item'] = bw_array_get( $labels, 'add_new_item', sprintf( __( 'Create New %1$s', "oik" ), $ucsingular ) );
  $labels['edit'] = bw_array_get( $labels, 'edit', __( 'Edit', "oik" ) );
  $labels['edit_item'] = bw_array_get( $labels, 'edit_item', sprintf( __( 'Edit %1$s', "oik" ), $ucsingular ) );
  $labels['new_item'] = bw_array_get( $labels, 'new_item', sprintf( __( 'New %1$s', "oik" ), $ucsingular ) );
  $labels['view'] = bw_array_get( $labels, 'view', sprintf( __( 'View %1$s', "oik"), $ucsingular ) );
  $labels['view_item'] = bw_array_get( $labels, 'view_item', sprintf( __( 'View %1$s', "oik" ), $ucsingular ) );
  $labels['search_items'] = bw_array_get( $labels, 'search_items', sprintf( __( 'View %1$s', "oik" ), $ucplural ) );
  $labels['not_found'] = bw_array_get( $labels, 'not_found', sprintf( __( 'No %1$s found', "oik" ), $lcplural ) );
  $labels['not_found_in_trash'] = bw_array_get( $labels, 'not_found_in_trash', sprintf( __( 'No %1$s found in Trash', "oik") , $lcplural ) ); 
  // These labels were added in WordPress 4.3.0
  $labels['featured_image'] = bw_array_get( $labels, 'featured_image', __( 'Featured Image', "oik" ) );
	$labels['set_featured_image'] = bw_array_get( $labels, 'set_featured_image', __( 'Set featured image', "oik" ) );
	$labels['remove_featured_image'] = bw_array_get( $labels, 'remove_featured_image', __( 'Remove featured image', "oik" ) );
	$labels['use_featured_image'] = bw_array_get( $labels, 'use_featured_image', __( 'Use as featured image', "oik" ) );
  // These labels were added in WordPress 5.8
	$labels['item_link'] = bw_array_get( $labels, 'item_link', sprintf( __( '%1$s Link', "oik"), $ucsingular ) );
	$labels['item_link_description'] = bw_array_get( $labels, 'item_link_description', sprintf( __( 'Link to %1$s', "oik"), $ucsingular ) );
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
  $labels['update_item'] = bw_array_get( $labels, "update_item" , sprintf( __( 'Update %1$s',  "oik" ), $ucplural ) ); 
  $labels['new_item_name'] = bw_array_get( $labels, "new_item_name" , sprintf( __( 'New %1$s', "oik" ), $ucsingular ) ); 
  $labels['parent_item'] = bw_array_get( $labels, "parent_item" , sprintf( __( 'Parent %1$s', "oik" ), $ucsingular ) ); 
  $labels['parent_item_colon'] = bw_array_get( $labels, "parent_item_colon" , sprintf( __( 'Parent %1$s:', "oik" ), $ucsingular ) ); 
  $labels['popular_items'] = bw_array_get( $labels, "popular_items" , sprintf( __( 'Popular %1$s', "oik" ), $ucplural ) ); 
  $labels['separate_items_with_commas'] = bw_array_get( $labels, "separate_items_with_commas", sprintf( __( 'Separate %1$s with commas', "oik" ), $ucplural ) ); 
  $labels['add_or_remove_items'] = bw_array_get( $labels, "add_or_remove_items", sprintf( __( 'Add or remove %1$s', "oik" ), $ucplural ) ); 
  $labels['choose_from_most_used'] = bw_array_get( $labels, "choose_from_most_used", sprintf( __( 'Choose from most used %1$s', "oik" ), $ucplural ) );
  // $labels['not_found'] = bw_array_get( $labels, "not_found", sprintf( __( 'No %1$s found', "oik") , $ucplural ) );
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

	$args = array();
  if ( !is_array( $arg ) ) {
    $labels = array( "name" => $arg );
  } else { 
		$args = $arg;
    $labels = bw_array_get( $args, "labels", null );
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
 * This function registers a custom $taxonomy for an $object_type with $args as specified.
 * If the $args parameter is a simple string then it's used to auto generate the "label" and "labels" values for the custom taxonomy.
 * You can set $args yourself or let the system create sensible default values.
 *
 * @param string $taxonomy - custom taxonomy name
 * @param string $object_type - the post type for which this taxonomy applies
 * @param mixed $args - array of arguments passed to register_taxonomy() 
 */
function bw_register_taxonomy( $taxonomy, $object_type=NULL, $args=NULL ) {
	//bw_trace2( $args );
	if ( !bw_query_taxonomy( $taxonomy ) ) {
		register_taxonomy( $taxonomy, $object_type, $args );
	} else {
		register_taxonomy_for_object_type( $taxonomy, $object_type );
	}
	if ( $args ) {
		$field_title=bw_array_get( $args['labels'], 'name', $taxonomy );
	} else {
		$field_title = $taxonomy;
	}
	bw_register_field( $taxonomy, "taxonomy", $field_title, $args );
}

/**
 * Query the registered taxonomies to see if this is registered
 *
 * @param string $taxonomy - the taxonomy name
 * @return bool - true if registered, false if not. 
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
	
	$args['show_in_rest'] = true;
    $args['show_in_nav_menus'] = bw_array_get( $args, 'show_in_nav_menus', false);
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
    if ( !isset( $args['rewrite']['hierarchical'] ) ) {
	  $args['rewrite']['hierarchical']=true;
  }
	$args['show_in_rest'] = true;
    $args['show_in_nav_menus'] = bw_array_get( $args, 'show_in_nav_menus', false);
  bw_register_taxonomy( $taxonomy, $object_type, $args ); 
}

/** 
 * Register a field named $field_name for object type $object_type
 *
 * @TODO - determine if this works for "taxonomy" field types as well
 * 
 * @param string $field_name - meta_key of the field - precede with an underscore so it's not in custom fields
 * @param string $object_type - object type
 * @param bool $rest - true for fields to be available to the REST api
 */
function bw_register_field_for_object_type( $field_name, $object_type, $rest=false ) {
  global $bw_mapping;
  $bw_mapping['field'][$object_type][$field_name] = $field_name;
  $bw_mapping['type'][$field_name][$object_type] = $object_type;
  if ( $rest ) {
	  bw_maybe_register_post_meta( $field_name, $object_type );
  }
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

function bw_auth_callback() {
    return current_user_can( 'edit_posts');
}

function bw_maybe_register_post_meta( $field_name, $object_type ) {
    $post_type = $object_type;
    $meta_key = $field_name;

    $description = bw_get_field_description( $field_name );
    if ( $description ) {
        $args = ['show_in_rest' => true,
            'single' => true,
            'type' => 'string',
            'auth_callback' => 'bw_auth_callback',
            'description' => $description
        ];
        $registered = bw_register_post_meta($post_type, $meta_key, $args);
    }
}

/**
 * Returns the field's description.
 *
 * Note: The field may not be registered even though the mapping has been registered.
 *
 *
 * @param $field_name
 * @return mixed
 */
function bw_get_field_description( $field_name ) {
    global $bw_fields;
    $description = null;
    bw_trace2( $bw_fields, "bw_fields", true, BW_TRACE_VERBOSE );
    //bw_backtrace();
    $field = bw_array_get( $bw_fields, $field_name, null );
    if ( $field ) {
        $description = $field['#title'];
    }
    return $description;
}

 function bw_register_post_meta( $post_type, $meta_key, $args ) {
    $registered = register_post_meta($post_type, $meta_key, $args);
    return $registered;
}