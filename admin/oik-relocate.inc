<?php // (C) Copyright Bobbing Wide 2012
 
/**

    Copyright 2012 Bobbing Wide (email : herb@bobbingwide.com )

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


//add_action( "oik_admin_menu", "bw_relocate_admin_menu", 20 );
//add_action( "admin_action_activate", "bw_relocate_activate" );
//add_action( "pre_current_active_plugins", "bw_relocate_pre_current_active_plugins", 10, 1 );



//function bw_relocate_plugin_list() {
//  //bw_add_relocation( 'play/relocateme.php', 'relocateme/relocateme.php' );
//  //bw_add_relocation( 'oldplay/rel1.php', 'play-rel2/rel1.php' );
//  //bw_add_relocation( 'oldplay/rel2.php', 'play-rel2/rel2.php' );
//} 

/**
 * Create the target directory if it does not already exist as a directory 
 * 
 * @param $tofile - the target file name
 * @return bool - indicator of success
 * Note: We set the directory permissions to octal 755
 */
function bw_mkdir( $tofile ) {
  $dir = dirname( $tofile );
  $success = file_exists( $dir );
  if ( $success ) { 
    $success = is_dir( $dir );
  } else {  
    $success = mkdir( $dir, 0755, true );
  }
  //bw_trace2( $success, "success?" );
  return( $success );
}
 
/**
 * Move the file from $fromfile to $tofile
 *
 * @param string $fromfile - fully qualified file to move
 * @param string $tofile - fully qualified target location - MUST be different from $fromfile ( not checked )
 * @return bool - true if target directory was OK AND the file was copied and the tofile deleted
 * 
 * Note: Need to check if we could use rename()
 */
function bw_movefile( $fromfile, $tofile ) {
  $success = bw_mkdir( $tofile );  
  if ( $success ) { 
    $success = copy( $fromfile, $tofile );
    if ( $success ) 
      $success = unlink( $fromfile );
  }
  bw_trace2( $success, "success?" );
  return( $success );
} 

/**
 * Wrapper to unlink
 */
function bw_deletefile( $file ) {
  $success = unlink( $file );
  bw_trace2( $success, "success?" );
  return $success; 
}  
   
/**
 * Function bw_relocate
 * 
 * @param string $thisfile - __FILE__ 
 * @param string $from - relative path within the plugins directory of the file to relocate
 * @param string $to - relative path within the plugins directory of the new target file
 *
 * Processing depends on the presence of the $from and $to files
 * The migration may require multiple steps - depending on what's active
 * 
 * $to exists   $from exists  Processing
 * ------------ ------------  --------------
 * Y            Y             delete the original file
 * Y            N             processing is complete
 * N            Y             move the file from $from to $to
 * N            N             ERROR
 *
 *  
 */
function bw_relocate( $thisfile, $from, $to  ) {
  $plugindir = plugin_dir_path( $thisfile );
  //echo "WP_PLUGIN_DIR: " . WP_PLUGIN_DIR . PHP_EOL;
  //echo "thisfile: " . $thisfile . PHP_EOL;
  //echo "plugindir: " . $plugindir . PHP_EOL;
  
  $plugindir = WP_PLUGIN_DIR . '/';
  $fromfile = $plugindir . $from;
  $tofile = $plugindir . $to;
  
  $fromexists = file_exists( $fromfile );
  $toexists = file_exists( $tofile );
  
  if ( $toexists ) {
    if ( $fromexists ) {
      $success = bw_deletefile( $fromfile ); 
    } else {
      $success = true;  /* this is the situation we're hoping for */
    }
  } else {
    if ( $fromexists ) {
      $success = bw_movefile( $fromfile, $tofile );
    } else {
      bw_trace2( $from, "Source file does not exist for relocation" ); 
      $success = false; 
    }   
  }
  bw_trace2( $success, "success" );
  return( $success );     
}

/** 
 * Perform relocations 
 * @param array $relocations - array of from => to
 */
function bw_perform_relocations( $relocations ) {
  foreach ( $relocations as $from => $to ) {
    bw_relocate( __FILE__, $from, $to );
  }  
}    

/**
 * Replace all entries in "active_sitewide_plugins" and "active_plugins"
 * with the new $to locations
 * Note: We don't need to check if the plugin is network only, we just check both arrays
 * We do this whether it's multisite or not... we don't update the active_sitewide_plugins
 * if we didn't get it in the first place
 *
 * active_sitewide_plugins is an array of plugin file names with time()
 * active_plugins is a simple array of plugin file names ( e.g. 0 => "oik/oik.php" )
 
 */
function bw_replace_active_plugins( $relocations ) {

  $active_sitewide_plugins = get_site_option( 'active_sitewide_plugins', array(), false );
  $aswpc = count( $active_sitewide_plugins );
  
  $active_plugins = get_option( 'active_plugins', array() );
  bw_trace2( $active_plugins, "before" );

  foreach ( $relocations as $from => $to ) {
    if ( in_array( $from, $active_sitewide_plugins ) ) {
      unset( $active_sitewide_plugins[ $from ] );
      $active_sitewide_plugins[ $to ] = time();
    }
    
    $key = array_search( $from, $active_plugins );
    if ( $key !== FALSE )  {
      $active_plugins[ $key ] = $to;
    }
  }
  if ( $aswpc ) {
    update_site_option( 'active_sitewide_plugins', $active_sitewide_plugins );
  }  
  update_option( 'active_plugins', $active_plugins ); 
   
  bw_trace2( $active_plugins, "after" );

}


/** 
 * Remove from the update_plugins transient any plugin that has been relocated
 *
 */
function bw_unset_update_plugins( $relocations ) {
  if ( $current = get_site_transient('update_plugins') ) {
    bw_trace2( $current, "update_plugins before" );
    foreach ( $relocations as $from => $to ) {
      unset( $current->response[ $from ] );
      unset( $current->checked[ $from ] );   
    }  
    set_site_transient('update_plugins', $current);
    
    bw_trace2( $current, "update_plugins after", false );
  }
} 


/** 
 * Perform relocation of selected child plugins 
 */
function bw_relocate_plugins() {
  //bw_backtrace();
  global $bw_relocations;
  if ( isset( $bw_relocations) && count( $bw_relocations ) ) {
    bw_perform_relocations( $bw_relocations );
    bw_replace_active_plugins( $bw_relocations );
    bw_unset_update_plugins( $bw_relocations );
  }    
}

/**
 * Alter the wp_list_table to reflect the plugin file changes
 * @link http://wordpress.org/support/topic/how-to-a-hide-plugin-from-username?replies=10
 
 * array_splice was no good since it lost the key value
       
      if ( $replacement ) 
        $key = $replacement;  
        //unset( $wp_list_table->items[$key] );
        //bw_trace2( $val, "val", false );
        //$wp_list_table->items[ $replacement ] = $val; 
        // array_splice( $wp_list_table->items, $offset, 1, array( $replacement => $val)  ); 

 
 */
function bw_update_list_table() {

  global $wp_list_table, $bw_relocations;
  bw_trace2( $wp_list_table, "wp_list_table", false );
  bw_trace2( $bw_relocations, "bw_relocations", false );
  if ( isset( $bw_relocations ) && isset( $wp_list_table )) {
    $myplugins = $wp_list_table->items;
    $newplugins = array();
    $offset = 0;
    foreach ($myplugins as $key => $val) {
      bw_trace2( $key, "key", false );
      $replacement = bw_array_get( $bw_relocations, $key, $key );           
      bw_trace2( $replacement, "replacement", false );

      $newplugins[$replacement] = $val;
      
      $offset++;
    }
    $wp_list_table->items = $newplugins;
  }
  
  
  bw_trace2( $wp_list_table, "wp_list_table after", false );
}

/**
 * Perform plugin relocation just before the plugins are listed on the admin page
 * for action: pre_current_active_plugins
 * 
 */
function bw_lazy_relocate_pre_current_active_plugins( $plugins ) {
  if ( defined( "BW_DISABLE_RELOCATION" ) && ( true == BW_DISABLE_RELOCATION) ) {
    global $bw_relocations;
    bw_trace2( BW_DISABLE_RELOCATION, "BW_DISABLE_RELOCATION is true" );
  } else {
    bw_relocate_plugins();
    bw_update_list_table();
  }   
}
