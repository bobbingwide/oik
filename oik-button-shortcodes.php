<?php // Copyright Bobbing Wide 2010-2014
/**
 * Optional functionality to provide the TinyMCE button for inserting [bw_button] shortcodes
 *
 * This file is loaded during oik_options_init() which is called to process 'admin_init'
 * so we can't add processing for this action... just do it
 *
 * Note: We assume that the current user can edit content. 
 * If they can't then the filters won't get called.
*/
add_filter( 'mce_buttons', 'bw_filter_mce_button' );
add_filter( 'mce_external_plugins', 'bw_filter_mce_plugin' );
 
/**
 * Implement 'mce_buttons' filter 
 * 
 * Add the bwbutton_button to the array of Tiny MCE buttons
 * 
 * @param array $buttons - array of TinyMCE buttons
 * @return array - with the bwbutton_button added
 * 
 */       
function bw_filter_mce_button( $buttons ) {

  global $tinymce_version;
  if ( version_compare( $tinymce_version, '4018' ) >= 0 ) {
    array_push( $buttons, 'bwbutton' );
  } else {  
    array_push( $buttons, 'bwbutton_button' );
  }
  return $buttons;
}
 
/**
 * Implement the 'mce_external_plugins' filter
 * 
 * @param array $plugins - array of external plugins for TinyMCE
 * @return array $plugins with oik_button_plugin.js added for bwbutton
 * 
 *  
 * Add the jQuery code to be executed when the bwbutton_button is clicked
 * Note: The _button suffix is not used... not quite sure where the linkage is
 * @see http://codex.wordpress.org/TinyMCE_Custom_Buttons
*/
function bw_filter_mce_plugin( $plugins ) {
  global $tinymce_version;
  if ( version_compare( $tinymce_version, '4018' ) >= 0 ) {
    $plugins['bwbutton'] = oik_url( 'admin/oik_button_plugin_4.js' );
  } else {
    $plugins['bwbutton'] = oik_url( 'admin/oik_button_plugin.js' );
  }
  return $plugins;
}
        

