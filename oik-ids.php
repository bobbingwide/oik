<?php
/*
    Copyright 2014 Bobbing Wide (email : herb@bobbingwide.com )

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
 * Implement 'manage_posts_columns' filter for oik-ids
 *
 * Also 'manage_pages_column', 'manage_media_column'
 * 
 * So what priority do we need for the ID column to appear consistently at the end, after WordPress SEO's fields say?
 * 
 * 
 * @param array $cols - array of defined columns
 * @return array - updated array of defined columns
 *  
 */
function oik_ids_column( $cols ) {
	$cols['ID'] = __( 'ID' ) ;
	return $cols;
}

/**
 * Implement manage_edit-$ptype_sortable_columns for oik-ids
 *
 * For fields in the post the key and value are the same. The value is the name of the orderby field.
 * Set the field to an array if you want the order to be DESC rather than ASC
 * 
 * @param array $cols - sortable columns
 * @return array - updated sortable columns
 */
function oik_ids_sortable_column( $cols ) {
  //bw_backtrace();
  $cols['ID'] = 'ID';
  return( $cols );
}

/**
 * Implement 'manage_posts_custom_column' action for oik-ids
 *
 * Echo the ID when required
 *
 * Notes:
 * 'manage_posts_custom_column' is fired for non-hierarchical posts types such as posts.
 * 'manage_pages_custom_column' is fired for hierarchical post types such as pages.
 * 
 * @param string $column_name - column to display
 * @param string $id - post ID
 */ 
function oik_ids_value( $column_name, $id ) {
  //bw_backtrace(); 
	if ( $column_name == 'ID' ) {
		echo $id;
  }
}

/**
 * Implement 'manage_users_custom_column' action for oik-ids
 *
 * Echo the ID when required
 * 
 * @param string $column_name - column to display
 * @param string $id - user ID
 */ 
function oik_ids_return_value( $value, $column_name, $id ) {
	if ( $column_name == 'ID' ) {
		$value = $id;
  }
	return $value;
}


/**
 * Output CSS for width of new column
 */ 
function oik_ids_css() {
  echo '<style type="text/css">#ID { width: 50px; }</style>';
}

/**
 * Function to invoke when oik-ids is loaded
 * 
 * Define action and filter hooks for various content types
 * 
 * Commented out:
 * - Comments
 * 
 * We also make the ID field sortable. 
 * @TODO Can we also reduce the filters we add based on the $ptype?
 * Can we make media sortable by adding logic when handling manager_media_columns ?
 * Ditto for post
 
 * {@link http://codex.wordpress.org/Plugin_API/Filter_Reference/manage_edit-post_type_columns}
 * 
 * In the codex we get told that the manage_edit-post_type_columns filter has been supplanted by manage_${post_type}_posts_columns
 * 
 * So what about the filter for custom post types?
 * {@link http://codex.wordpress.org/Plugin_API/Action_Reference/manage_$post_type_posts_custom_column}
 * 
 * For custom post types we see the following actions/filters
 *                       
 *   [manage_edit-oik_location_columns] => 1
 *   [manage_taxonomies_for_oik_location_columns] => 2
 *   [manage_posts_columns] => 2
 *   [manage_oik_location_posts_columns] => 2
 * 
 * Whether or not it's more efficient to respond to the action for the particular 'screen' has not been determined
 * BUT we're doing it anyway.
 * `
 * URI                 action                  Invoked for
 * ------------        ----------------------- ---------------------------
 * "edit.php"          "load-edit.php"         posts, pages and CPTs 
 * "edit-tags.php"     "load-edit-tags.php"    taxonomies
 * "upload.php"        "load-upload.php"       media attachments
 * "link-manager.php"  "load-link-manager.php" links
 * "users.php"         "load-users.php"        users
 * `
 * 
 * The most efficient method would be to dynamically define the function to match the action
 * e.g. oik_ids_load_edit - for "load_edit" 
 * IF the function exists AND we know the screen->id.
 * which we can get from the first file loaded?  
 * 
 * $_SERVER[ 'REQUEST_URI' ] = /wordpress/wp-admin/users.php
 * $_SERVER[ 'SCRIPT_FILENAME ' ]
 * $_SERVER[ 'PHP_SELF' ]
 * $_SERVER[ 'SCRIPT_NAME' ] 
 
 * admin.php uses $pagenow - which is set to the URI without the prefix
  
 */ 
function oik_ids_loaded() {
  //bw_backtrace();
  //bw_trace2( $GLOBALS, "globals" );
	//add_action( 'admin_head', 'oik_ids_css');
  
  global $pagenow;
  if ( $pagenow ) {
    $pagenow_function = "oik_ids_load_";
    $pagenow_function .= str_replace( array( ".", "-" ), "_", $pagenow );
    if ( function_exists( $pagenow_function ) ) {
      add_action( "load-" . $pagenow, $pagenow_function );
    }
  }
}

/**
 * Implement "load-edit.php" action for oik-ids
 * 
 * Defines the filters and hooks for sortable ID column on post, page and CPT admin screens
 *
 * There really is no need to add a filter for each post type if we can find out what post type we're currently displaying.
 * For edit.php we can query the 'post_type field', except when it's the 'post' type when the value is not set.
 *
 * Note: according to (some) documentation 
 * 'manage_posts_columns' is for non-hierarchical post types
 * 'manage_pages_columns' is for hierarchical.
 * 
 * No need to find out... just define actions for both.
 */
function oik_ids_load_edit_php() {
	add_filter( 'manage_posts_columns', 'oik_ids_column' );
	add_action( 'manage_posts_custom_column', 'oik_ids_value', 1000, 2 );
	add_filter( 'manage_pages_columns', 'oik_ids_column' );
	add_action( 'manage_pages_custom_column', 'oik_ids_value', 1000, 2 );
  $ptype = bw_array_get( $_REQUEST, "post_type", null ); 
  if ( $ptype ) {
    add_filter("manage_edit-${ptype}_sortable_columns", "oik_ids_sortable_column" );
  } else {
    add_filter("manage_edit-post_sortable_columns", "oik_ids_sortable_column" ); 
  }
}

/**
 * Implement "load-edit-tags.php" action for oik-ids
 * 
 * Defines the filters and hooks for adding the sortable ID column to taxonomy edit screens. 
 *
 * This includes the link-categories taxonomy and custom taxonomies.
 */ 
function oik_ids_load_edit_tags_php() {
	// For Taxonomy Management
  $taxonomy = bw_array_get( $_REQUEST, "taxonomy", null ); 
  if ( $taxonomy ) {
		add_action("manage_edit-${taxonomy}_columns", 'oik_ids_column');			
		add_filter("manage_${taxonomy}_custom_column", 'oik_ids_return_value', 1000, 3);
    add_filter( 'manage_edit-${taxonomy}_sortable_columns', "oik_ids_sortable_column" );
	}
}

/**
 * Implement "load-upload.php" action for oik-ids
 * 
 * Defines the filters and hooks for adding the sortable ID column to Media screens. 
 */ 
function oik_ids_load_upload_php() {
	add_filter( 'manage_media_columns', 'oik_ids_column' );
	add_action( 'manage_media_custom_column', 'oik_ids_value', 1000, 2 );
  add_filter( 'manage_upload_sortable_columns', 'oik_ids_sortable_column' );
}

/**
 * Implement "load-link-manager.php" action for oik-ids
 * 
 * Defines the filters and hooks for adding the sortable ID column to the Link manager.
 * Not that anyone still uses this :-?
 */ 
function oik_ids_load_link_manager_php() {
	add_filter( 'manage_link-manager_columns', 'oik_ids_column' );
	add_action( 'manage_link_custom_column', 'oik_ids_value', 1000, 2 );
  add_filter( 'manage_link-manager_sortable_columns', "oik_ids_sortable_column" );
}

 
/**
 * Implement "load-users.php" action for oik-ids
 *
 * Defines the filters and hooks for adding the sortable ID column to the User screen
 */
function oik_ids_load_users_php() {
	add_action( 'manage_users_columns', 'oik_ids_column' );
	add_filter( 'manage_users_custom_column', 'oik_ids_return_value', 1000, 3 );
	add_filter( 'manage_users_sortable_columns', "oik_ids_sortable_column" );
  
}

/**
 * I don't think there's any point in doing this for comments! 
 *
 // For Comment Management
 //add_action( 'manage_edit-comments_columns', 'oik_ids_column' );
 //add_action( 'manage_comments_custom_column', 'oik_ids_value', 1000, 2 );
 */

oik_ids_loaded();

