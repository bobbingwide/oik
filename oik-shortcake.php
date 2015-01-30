<?php // (C) Copyright Bobbing Wide 2015

/**
 * Lazy code for implementing integration with the shortcake plugin
 *
 * We defer evaluating the syntax of all the shortcodes until just before
 * shortcake makes use of the information.
 *
 * Note: We could argue that Shortcake is inefficient when there are hundreds of shortcodes
 * since it seems to want to know the syntax for every single shortcode even though it may never be used.
 * AND, because may of oik's shortcode take an inordinate number of parameters the output could get very large indeed.
 * 
 *
 */


/**
 * Implement "admin_enqueue_scripts" actions for oik's integration with shortcake
 *
 * If we're editing content and shortcode is active then we should
 * register our shortcodes with shortcake
 * 
 * @TODO There are some shortcodes we wouldn't want to register to shortcake
 * 
 */
function oik_shortcake_admin_enqueue_scripts( $hook ) {
  //bw_trace2();
  if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
     if ( function_exists( "shortcode_ui_register_for_shortcode" ) ) {
       oik_register_shortcodes_to_shortcake();  
     }
  }
}

/**
 * Query if the shortcode is already registered
 * 
 * Other plugins may have already registered the shortcode so there's no need to do it again
 *
 * @param string $shortcode - the shortcode tag
 * @return registered - the registered shortcode's definition
 */
function oik_registered_to_shortcake( $shortcode ) {
  $registered = shortcode_ui_get_register_shortcode( $shortcode );
  return( $registered );
}
 

/**
 * Register our shortcodes to shortcake
 *
 * The oik base plugin provides a service which allows each shortcode
 * to specify help, shortcode syntax etc.
 * 
 * shortcake provides a similar service, although this is not currently lazy.
 *
 * For each of the registered shortcodes, if it's not already registered with shortcake,
 * then map the syntax to shortcake and register it. 
 * 
 */
function oik_register_shortcodes_to_shortcake() {
  do_action( "oik_add_shortcodes" );
  oik_require( 'shortcodes/oik-codes.php' );
  $sc_list = bw_shortcode_list();
  bw_trace2( $sc_list, "shortcode list", false );
  foreach ( $sc_list as $shortcode => $help ) {
    $registered = oik_registered_to_shortcake( $shortcode );
    if ( !$registered ) {
      $sc_syntax = _bw_lazy_sc_syntax( $shortcode );
      oik_register_shortcode_to_shortcake( $shortcode, $help, $sc_syntax );
    }  
  }
}

/**
 * Return the image to use for the shortcode
 * 
 * The image can either be a dashicon - fully prefixed
 * or an actual image file. 
 * Assume the logic works the same way as the admin menu
 * 
 * 
 * can we also use genericons - no NOT yet
 * nor "texticons" 
 *
 * This is a Quick and Dirty prototype. 
 * In a future version there will be an API to enable each shortcode to specify its own icon... on demand
 * It should not be necessary to register the information until it's needed.
 *
 */
function oik_select_shortcake_image( $shortcode ) {
  $shortcode_images = array( "artisteer" => "dashicons-art"
                           , "wp" => "wordpress" 
                           , "bw_pages" => "dashicons-admin-page"
                           , "bw_posts" => "dashicons-admin-post"
                           , "bw_wtf" => "dashicons-sos"
                           , "bw_dash" => "genericons-minus"
                           , "gallery" => "dashicons-format-gallery"
                           , "bw_images" => "dashicons-format-image"
                           , "bw_attachments" => "dashicons-admin-media"
                           , "bw_alt_slogan" => "dashicons-megaphone"
                           , "add_to_cart" => "dashicons-cart"
                           , "add_to_cart_url" => "dashicons-cart"
                           , "audio" => "dashicons-format_audio"
                           , "bw_address" => "dashicons-business"
                           ); 
  $image = bw_array_get( $shortcode_images, $shortcode, "" );
  
  return( $image );

}

/**
 * Map default and values to type and options
 
   'attrs'         => array(
                array(
                    'label'   => 'Alignement',
                    'attr'    => 'float',
                    'type'    => 'select',
                    'value'   => 'right', // default value 
                    'options' => array( // List of options  value => label
                        'left'  => 'Left',
                        'right' => 'Right',
                        'none'  => 'None'
                    ),
                ),
 *
 These are the types that shortcake currently support
 
 'text' => array(),
		'textarea' => array(   'template' => 'shortcode-ui-field-textarea',
		),
		'url' => array(    'template' => 'shortcode-ui-field-url',
		),
		'select' => array(    'template' => 'shortcode-ui-field-select',
		),
		'checkbox' => array(  'template' => 'shortcode-ui-field-checkbox',
		),
		'radio' => array(    'template' => 'shortcode-ui-field-radio',
		),
		'email' => array(   'template' => 'shortcode-ui-field-email',
		),
		'number' => array(   'template' => 'shortcode-ui-field-number',
		),
		'date' => array(    'template' => 'shortcode-ui-field-date',
		),
    
 oik's values are fairly free form.
 So initially everything would be a text field
     
    
    
 */
function oik_map_skv_to_attr( $attr, $shortcode, $help, $parameter, $data ) {
  $default = $data['default'];
  if ( is_numeric( $default ) ) {
    $attr['type'] = "number";
  } 
  $values = $data['values'];
  $values = str_replace( "<i>", "", $values );
  $values = str_replace( "</i>", "", $values );
  $options = explode( "|", $values );
  $first_alternative = $options[0];
  $lc_alternative = strtolower( $first_alternative );
  $mapping = array( "asc" => "select" 
                  , "desc" => "select" 
                  , "email" => "email" 
                  , "id" => "number"
                  , "url" => "url"
                  , "n" => "checkbox"
                  , "y" => "checkbox"
                  , "date" => "date" 
                  , "numeric" => "number" 
                  );
  $attr['type'] = bw_array_get( $mapping, $lc_alternative, $attr['type'] );
  if ( $attr['type'] == "select" ) {
    array_unshift( $options, $default );
    $attr['options'] = bw_assoc( $options ); 
  }
  
  
  bw_trace2( $attr );
  
  
  
  
  
  return( $attr );
}

/**
 * Register a shortcode to shortcake
 *
 *
 * We need to map our shortcode's syntax into shortcake's
 * 
 * `
 *  [numberposts] => Array
 *              (
 *                  [default] => 5
 *                  [values] => numeric
 *                  [notes] => number to return
 *              )
 * `              
                                       
                                       
    shortcode_ui_register_for_shortcode( "wp" 
                                       , array( 'label' => "WordPress"
                                              , 'listItemImage' => 'dashicons-wordpress'
                                              , 'attrs' => array( array( 'label' => 'Version', 'attr' => 'v', 'type' => 'text' )    
                                                                , array( 'label' => 'PHP version', 'attr' => 'p', 'type' => 'text' )
                                                                )
                                              )
                                       );
 */
function oik_register_shortcode_to_shortcake( $shortcode, $help, $syntax ) {
  $attrs = array();
  if ( is_array( $syntax ) && count( $syntax ) ) {
    foreach ( $syntax as $parameter => $data ) {
      $notes = $data['notes'];
      $default = $data['default'];
      $attr = array( 'label' => "$parameter - $notes"
                   , 'attr' => $parameter
                   , 'type' => 'text'
                   , 'value' => $default
                   ); 
      $attr = oik_map_skv_to_attr( $attr, $shortcode, $help, $parameter, $data );              
      $attrs[] = $attr;
    }
  }
  $parm2 = array();
  $parm2['label'] = $shortcode; // $help;
  $parm2['listItemImage'] = oik_select_shortcake_image( $shortcode );
  $parm2['listItemText'] = $shortcode;
  $parm2['attrs'] = $attrs;
  shortcode_ui_register_for_shortcode( $shortcode, $parm2 );
} 



function oik_shortcake_loaded() {

  //bw_trace2();
  add_action( "admin_enqueue_scripts", "oik_shortcake_admin_enqueue_scripts", 9 );
}

oik_shortcake_loaded();
