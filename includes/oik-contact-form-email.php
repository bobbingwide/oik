<?php // (C) Copyright Bobbing Wide 2013, 2023

/**
 * Perform symbolic substitution of the fields in the body
 *
 * @param string $body - the body of the message
 * @param array $fields - array of key value pairs
 * 
 * This code allows for a variety of field delimeters:
 *   %name%
 *   {name}
 *   what else 
 *
 * At the end of the processing there may be some %var%'s remaining
 * That's not our problem; another routine could be used to clear up the mess
 * 
 */
function bw_replace_fields( $body=null, $fields=array() ) {
  if ( $body ) {
    foreach ( $fields as $name => $value ) {
		if ( null !== $value) {
			$body=str_replace( "%{$name}%", $value, $body );
		}
    }
  }  
  return( $body );
}

/**
 * Return the subject of the email
 *
 * @param string $subject - email subject
 * @param array $fields - reference to the fields array - we update the 'subject' field
 * @return string - the email subject
 */
function bw_get_email_subject( $subject=null, &$fields=array() ) {
  if ( null == $subject ) {
    $subject = __( "Contact form submission" );
  }
  add_filter( "bw_email_subject", "bw_replace_fields", 1, 2 );
  $subject = apply_filters( "bw_email_subject", $subject, $fields );
  $fields['subject'] = $subject;
  return( $subject );
}

/**
 * Return a default email message
 * @param string $tag - the HTML tag to separate lines ( e.g. "p", "span" or "br" )
 * @return string - the default email message
 */
function bw_get_email_default( $tag="p" ) {
  $tags = array( "br" => null );
  $etag = bw_array_get( $tags, $tag, "</$tag>" );    
  if ( $etag == null ) { 
    $tag .= " /";
  } 
  $message = ""; 
  $message .= "<$tag>Email: %from% $etag ";
  $message .= "<$tag>Name: %contact% $etag";
  $message .= "<$tag>Message: %message% $etag";
  return( $message );
} 

/**
 * Build the email message
 *
 * The default contact form message is:
 * 
 * Subject: Contact form submission
 * 
 * <pre>
 * Email: %email%
 * Name: %name%
 * Message: %message%
 * </pre>
*/
function bw_get_email_message( $message=null, $fields=array() ) {
  if ( $message == null ) {
    $message = bw_get_email_default();
  }  
  add_filter( "bw_email_message", "bw_replace_fields", 1, 2 );
  $message = apply_filters( "bw_email_message", $message, $fields );
  return( $message );
}  

/**
 * Set the email headers for the wp_mail() call
 * 
 * @param mixed $headers - normally an array of headers
 * @param mixed $fields - array of fields that may get updated
 * 
 * @return mixed filtered headers
 */
function bw_get_email_headers( $headers=null, &$fields=array() ) {
  $fields['bcc'] = $fields['from']; 
  if ( null == $headers ) {
    $headers[] = "From: %contact% <%from%>";
    $headers[] = "Bcc: %bcc%"; 
    $headers[] = "content-type: text/html";
  }  
  add_filter( "bw_email_headers", "bw_replace_fields", 1, 2 );
  $headers = apply_filters( "bw_email_headers", $headers, $fields );
  bw_trace2();
  return( $headers );
}  

/**
 * Build and send an email with fields substituted by default
 *
 * @param string $email - email address of the recipient - overridden by the value in $fields['email']      **?** not oiku_email_to then 2013/09/03
 * @param string $subject - the one line email subject, excluding Subject:
 * @param string $message - the body of the email
 * @param array/string $headers - the message header: From:, Bcc:, Content-type: etc
 * @param array $fields - name value pairs for symbolic substitution
 * @return bool the result of the wp_mail() call
 * 
 * @link http://codex.wordpress.org/Function_Reference/wp_mail 
 * @uses wp_mail
 * 
 */
function bw_send_email( $email, $subject=null, $message=null, $headers=null, $fields=array() ) {
  $to = bw_array_get( $fields, 'oiku_email_to', $email );
  $headers = bw_get_email_headers( $headers, $fields );
  $subject = bw_get_email_subject( $subject, $fields );
  $message = bw_get_email_message( $message, $fields );
  $attachments = [];
  $result = wp_mail( $to, $subject, $message, $headers, $attachments );
  bw_trace2( $result, "result of wp_mail" );
  if ( !$result ) {
      /* translators: %1 email address %2 email subject */
    BW_::p( sprintf( __( 'Failed to send email to: %1$s subject: %2$s', "oik" ), $to, $subject ) );
  }
  return( $result );
}  
  

