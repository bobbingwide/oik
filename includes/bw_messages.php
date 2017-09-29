<?php // (C) Copyright Bobbing Wide 2013-2017

/**
 * Issue a message for a particular field
 * 
 * Similar to add_settings_error() but not restricted to use by admin functions
 * 
 * @param string $field - field name  e.g. _dtib_location
 * @param string $code - message code e.g. "invalid_location"
 * @param string $text - the message text e.g. "Invalid Location, please correct"
 * @param string $type The type of message it is, controls HTML class. Use 'error' or 'updated'.
 */
if ( !function_exists( "bw_issue_message" ) ) { 
function bw_issue_message( $field, $code, $text, $type='error' ) { 
  bw_trace2();
  global $bw_messages;
  if ( !isset( $bw_messages ) ) {
    $bw_messages = array();
  }
  $bw_messages[] = array( "field" => $field
                        , "code" => $code
                        , "text" => $text
                        , "type" => $type
                        );
  return( false );                       
}
}
  
/**
 * Return number of messages to display
 * 
 * @return integer - number of messages, if any
 */
 
if ( !function_exists( "bw_query_messages" ) ) { 
function bw_query_messages() {
  global $bw_messages;
  $messages = isset( $bw_messages );
  if ( $messages ) {
    $messages = count( $bw_messages );
  }
  return( $messages );
}
}

/** 
 * Display a message 
 * 
 * The message display is similar to the message display for settings errors 
 * 
 * @param array $bw_message - a message field
 * @return bool - true 
 */
 
if ( !function_exists( "bw_display_message" ) ) { 
function bw_display_message( $bw_message ) {
  $classes = $bw_message['field'];
  $classes .= " ";
  $classes .= $bw_message['type']; 
  sdiv( $classes, $bw_message['code'] );
  BW_::p( $bw_message['text'] );
  ediv();
  return( true );
}
}  

/** 
 * Display the messages
 * 
 * Display the set of messages in @global $bw_messages
 *
 * @TODO - display messages associated with a particular 'field' or area of the form.
 */
if ( !function_exists( "bw_display_messages" ) ) { 
function bw_display_messages() {
  global $bw_messages;
  $displayed = false;
  sdiv( "bw_messages" );
  foreach ( $bw_messages as $key => $bw_message ) {
    $displayed = bw_display_message( $bw_message );
  }
  ediv();
  return( $displayed );
}
} 
