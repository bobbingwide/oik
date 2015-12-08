<?php // (C) Copyright Bobbing Wide 2014,2015
if ( !defined( "BW_LIST_TABLE_INCLUDED" ) ) {
define( "BW_LIST_TABLE_INCLUDED", "0.0.1" );
define( 'BW_LIST_TABLE_FILE', __FILE__ );

/**
 * oik-list-table is destined to becoming a shared library called bw_list_table
 * Here we're going to use the BW_LIST_TABLE_INCLUDED constant 
 * to attempt to prevent problems...
 * but may use oik_require_lib( "bw_list_table") when this file gets deprecated
 * so perhaps it's not a good idea after all.
 * That's OK, we can use CLASS_BW_LIST_TABLE for the BW_List_Table class
 * and CLASS_BW_OPTIONS_LIST_TABLE for the BW_Options_List_Table class
 */

/**
 * Fetch an instance of the BW_List_Table class
 * 
 * Implements the bw version of _get_list_table using BW_List_Table as the base class
 * 
 * @link http://codex.wordpress.org/Class_Reference/WP_List_Table
 * 
 * File                                    Implements
 * ---------------------------------       ----------------------------------------------
 * admin/class-bw-list-table.php           BW_List_Table - which is a clone of WP_List_Table ( WordPress 4.0 - 2014/10/19 )
 * admin/class-bw-something-list-table.php BW_Something_List_Table extends BW_List_Table
 *
 * 
 * @param string $class - the class name e.g. XY_Something_List_Table
 * @param array $args - which may also contain 'plugin' slug and 'path' to the class file.
 * @return object - an instance of the list class
 *
 */
function bw_get_list_table( $class, $args=array() ) {
  if ( !class_exists( "BW_List_Table" ) ) {
    oik_require( "admin/class-bw-list-table.php" );
  }
  $loaded = bw_list_table_loader( $class, $args );
  if ( $loaded && class_exists( $class ) ) {  
    $args = _bw_get_list_table_args_screen( $args );
    $instance = new $class( $args );
  }  
  return( $instance );

}

/**
 * Find where the class is implemented and load it
 *
 * @TODO Decide whether or not use to autoload for classes
 * If so, determine the loading rules that will apply for all classes
 * since we don't want hundreds of rules.
 * Can this function actually be the spl loader function?
 *
 * @param string $class - the class name to load
 * @param array $args - 
 * @return bool - whether or not the class is loaded
 */
function bw_list_table_loader( $class, $args ) {
  if ( class_exists( $class ) ) {
    $loaded = true;
  } else {
    $file = str_replace( "_", "-", $class );
    $file = strtolower( $file );
    $plugin = bw_array_get( $args, "plugin", null );
    $path = bw_array_get( $args, "path", "admin" );
    $file = "${path}/class-${file}.php";
    $required = array( "class" => $class
                     , "file" => $file
                     , "plugin" => $plugin 
                     , "args" => $args
                     );
    $required = apply_filters( "bw_list_table", $required );
     
    // Do we need to check if the file exists?
    
    oik_require( $required['file'], $required['plugin'] );
    $loaded = class_exists( $class );
  }
  return( $loaded );
} 

/**
 * Fiddle about with $args['screen'] 
 * 
 * Code copied from _get_list_table()
 */
function _bw_get_list_table_args_screen( $args ) {
  if ( isset( $args['screen'] ) )
    $args['screen'] = convert_to_screen( $args['screen'] );
  elseif ( isset( $GLOBALS['hook_suffix'] ) )
    $args['screen'] = get_current_screen();
  else
    $args['screen'] = null;
  return( $args );
}

} /* endif */ 



