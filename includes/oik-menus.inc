<?php // (C) Copyright Bobbing Wide 2012

/** 
 * Return an array of top level nav menus
 * 
   $menus array from wp_get_nav_menus() is 
   (
       [0] => stdClass Object
           (
               [term_id] => 3
               [name] => Primary
               [slug] => primary
               [term_group] => 0
               [term_taxonomy_id] => 3
               [taxonomy] => nav_menu
               [description] => 
               [parent] => 0
               [count] => 8
           )


 */
function bw_get_nav_menus() {
  oik_require( "bw_metadata.inc" );
  $menus = wp_get_nav_menus( $args = array() );
  $terms = bw_term_array( $menus );
  $terms[0] = "none";
  $auto_add = get_option( 'nav_menu_options' );
  $auto_add = bw_array_get( $auto_add, "auto_add", 0 );
  $auto_add = bw_array_get( $auto_add, 0, 0 );
  if ( $auto_add ) {
    bw_tablerow( array("&nbsp;", "The new page will be added to menu: " . $terms[$auto_add] ) );
  } else { 
    bw_select( "bw_nav_menu", "Add to menu", $auto_add, array( '#options' => $terms) );
  }
  return( $menus );
}

/**
 * Create a menu link for the "page" in the selected menu (at the top level)
 *
 * @param string $title of the new page
 * @param int $term_id - taxonomy term_id of the menu
 * @param int $page_id - ID of the new page
 * @param int $menu_order - defaults to 0
 * @return int $menu_item_db_id - ID of the new menu item
 *
 * @uses wp_update_nav_menu_item() 
*/
function bw_insert_menu_item( $title, $term_id, $page_id, $menu_order=0 ) {
  $menu_item_data = array( 'menu-item-type' => 'post_type'
                         , 'menu-item-object-id' => $page_id
                         , 'menu-item-object' => 'page'
                         , 'menu-item-title' => $title
                         , 'menu-item-position' => $menu_order 
                         , 'menu-item-status' => 'publish'
                         );
  $menu_item_db_id = wp_update_nav_menu_item( $term_id, 0, $menu_item_data );
  return( bw_trace2( $menu_item_db_id ));
}  

