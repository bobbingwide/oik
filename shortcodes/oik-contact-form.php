<?php // (C) Copyright Bobbing Wide 2013-2017

/** 
 * Return a unique contact form ID 
 *
 * @param bool $set - increment the ID if true
 * @return string - the contact form ID  - format oiku_contact-$bw_contact_form_id
 */
function bw_contact_form_id( $set=false ) {
	static $bw_contact_form_id = 0;
	if ( $set ) {
		$bw_contact_form_id++;
	}
	return( "oiku_contact-$bw_contact_form_id" );
}

/**
 * Implements the [bw_contact_form] shortcode
 * 
 * Creates/processes an inline contact form for the user
 * 
 * @param array $atts - shortcode parameters
 * @param string $content - not yet expected 
 * @param string $tag - shortcode name
 * @return string Generated HTML for the contact form
 */
function bw_contact_form( $atts=null, $content=null, $tag=null ) {
	$email_to = bw_get_option_arr( "email", null, $atts );
	if ( $email_to ) { 
		$atts['email'] = $email_to; 
		bw_display_contact_form( $atts );
	} else { 
		e( __( "Cannot produce contact form for unknown user.", "oik" ) );
	}  
	return( bw_ret() );
}

/**
 * Create the submit button for the contact form 
 *
 * @param array $atts - containing "contact" or "me" or defaults
 */  
function bw_contact_form_submit_button( $atts ) {
	$text = bw_array_get( $atts, "contact", null );
	if ( !$text ) {
		$me = bw_get_me( $atts );
		$text = sprintf( __( "Contact %s" ), $me ); 
	}
	e( isubmit( bw_contact_form_id(), $text, null ) );
}

/**
 * Show the "oik" contact form
 * 
 * This is a simple contact form which contains: Name, Email, Subject, Message and a submit button.
 * 
 * - Note: The * indicates Required field.
 * - If you want to make the fields responsive then try some CSS such as:
 *
 * `
 * textarea { max-width: 100%; }
 * `
 * 
 * @param array $atts - shortcode parameters 
 */
function _bw_show_contact_form_oik( $atts ) {
	$email_to = bw_get_option_arr( "email", null, $atts );
	oik_require( "bobbforms.inc" );
	$class = bw_array_get( $atts, "class", "bw_contact_form" );
	sdiv( $class );
	bw_form();
	stag( "table" ); 
	BW_::bw_textfield( "oiku_name", 30, __( "Name *", "oik" ), null, "textBox", "required" );
	BW_::bw_emailfield( "oiku_email", 30, __( "Email *", "oik" ), null, "textBox", "required" );
	BW_::bw_textfield( "oiku_subject", 30, __( "Subject", "oik" ), null, "textBox" );
	BW_::bw_textarea( "oiku_text", 30, __( "Message", "oik" ), null, 10 );
	// @TODO Optional "required" checkbox
	//bw_checkbox( "oiku_checkbox, 
	etag( "table" );
	e( wp_nonce_field( "_oik_contact_form", "_oik_contact_nonce", false, false ) );
	e( ihidden( "oiku_email_to", $email_to ) );
	oik_require_lib( "oik-honeypot" );
	do_action( "oik_add_honeypot" );
	bw_contact_form_submit_button( $atts );
	etag( "form" );
	ediv();
}

/**
 * Show/process a contact form using oik
 * 
 * @param array $atts 
 * @param string $user
 */
function bw_display_contact_form( $atts, $user=null ) {
	$contact_form_id = bw_contact_form_id( true );
	$contact = bw_array_get( $_REQUEST, $contact_form_id, null );
	if ( $contact ) {
		oik_require( "bobbforms.inc" );
		oik_require_lib( "oik-honeypot" );
		do_action( "oik_check_honeypot", "Human check failed." );
		$contact = bw_verify_nonce( "_oik_contact_form", "_oik_contact_nonce" );
		if ( $contact ) {
			$contact = _bw_process_contact_form_oik();
		}
	}
	if ( !$contact ) { 
		_bw_show_contact_form_oik( $atts, $user );
	}
}

/**
 * Return the sanitized message subject
 *  
 * @return string - sanitized value of the message subject ( oiku_subject )
 */ 
function bw_get_subject() {
	$subject = bw_array_get( $_REQUEST, "oiku_subject", null );
	// $subject = stripslashes( $subject );
	$subject = sanitize_text_field( $subject );
	$subject = stripslashes( $subject );
	return( $subject );
}

/**
 * Return the sanitized message text
 * 
 * Don't allow HTML, remove any unwanted slashes and remove % signs to prevent variable substitution from taking place unexpectedly.
 * 
 * @return string - sanitized value of the message text field ( oiku_text ) 
 */
function bw_get_message() {
	$message = bw_array_get( $_REQUEST, "oiku_text", null );
	$message = sanitize_text_field( $message );
	$message = stripslashes( $message );
	$message = str_replace( "%", "", $message );
	return( $message );
}

/**
 * Perform an Akismet check on the message, if it's activated
 * 
 * @param array - name value pairs of fields
 * @return bool - whether or not to send the email message
 */
function bw_akismet_check( $fields ) {
	if ( class_exists( "Akismet") || function_exists( 'akismet_http_post' ) ) {
		$query_string = bw_build_akismet_query_string( $fields );
		$send = bw_call_akismet( $query_string );
	} else {
		bw_trace2( "Akismet not loaded." ); 
		$send = true;
	}
	return( $send );
}

/**
 * Return true if the akismet call says the message is not spam
 * 
 * @param string $query_string - query string to pass to akismet
 * @return bool - true is the message is not spam 
 */
function bw_call_akismet( $query_string ) {
	global $akismet_api_host, $akismet_api_port;
	if ( class_exists( "Akismet" ) ) {
		$response = Akismet::http_post( $query_string, 'comment-check' );
	} else {
		$response = akismet_http_post( $query_string, $akismet_api_host, '/1.1/comment-check', $akismet_api_port  );
	}  
	bw_trace2( $response, "akismet response" );
	$result = false;
	$send = 'false' == trim( $response[1] ); // 'true' is spam, 'false' is not spam
	return( $send );
}

/**
 * Return the query_string to pass to Akismet given the fields in $fields and $_SERVER
 * 
 * @link http://akismet.com/development/api/#comment-check
 * blog (required) -The front page or home URL of the instance making the request. 
 *                  For a blog or wiki this would be the front page. Note: Must be a full URI, including http://.
 * user_ip (required) - IP address of the comment submitter.
 * user_agent (required) - User agent string of the web browser submitting the comment - typically the HTTP_USER_AGENT cgi variable. 
 *                          Not to be confused with the user agent of your Akismet library.
 * referrer (note spelling) - The content of the HTTP_REFERER header should be sent here.
 * permalink - The permanent location of the entry the comment was submitted to.
 * comment_type - May be blank, comment, trackback, pingback, or a made up value like "registration".
 * comment_author - Name submitted with the comment
 * Use "viagra-test-123" to always get a spam response
 * comment_author_email - Email address submitted with the comment
 * comment_author_url - URL submitted with comment
 * comment_content - The content that was submitted. 
 * Note: $fields['comment_content'] is the sanitized version of the user's input
 * 
 * @param array $fields array of fields 
 */
function bw_build_akismet_query_string( $fields ) {
	bw_trace2();
	//bw_backtrace();
	$form = $_SERVER;
	$form['blog'] = get_option( 'home' );
	$form['user_ip'] = preg_replace( '/[^0-9., ]/', '', $_SERVER['REMOTE_ADDR'] );
	$form['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
	$form['referrer'] = $_SERVER['HTTP_REFERER'];
	$form['permalink'] =  get_permalink();
	$form['comment_type'] = $fields['comment_type']; // 'oik-contact-form';
	$form['comment_author'] = bw_array_get( $fields, 'comment_author', null );
	$form['comment_author_email'] = bw_array_get( $fields, 'comment_author_email', null );
	$form['comment_author_url'] = bw_array_get( $fields, 'comment_author_url', null );
	$form['comment_content'] = bw_array_get( $fields, 'comment_content', null );  
	unset( $form['HTTP_COOKIE'] ); 
	$query_string = http_build_query( $form );
	return( $query_string );
}

/**
 * Display a "thank you" message
 * 
 * @param array $fields - in case we need them
 * @param bool $send - whether or not we were going to send the email / insert the post
 * @param bool $sent - whether or not the email was sent / post inserted
 */
function bw_thankyou_message( $fields, $send, $sent ) {
	if ( $send ) {
		if ( $sent ) {
			BW_::p( __( "Thank you for your submission.", "oik" ) );
		} else {
			BW_::p( __( "Thank you for your submission. Something went wrong. Please try again.", "oik" ) );
		}
	} else { 
		BW_::p( __( "We would like to thank you for your submission.", "oik" ) ); // spammer
	}
}

/**
 * Process a contact form submission
 *
 * Handle the contact form submission
 * 1. Check fields
 *   If message is blank then display an error message.
 * 2. Perform spam checking
 * 3. Send email, copying user if required
 * 4. Display "thank you" message
 * 
 */
function _bw_process_contact_form_oik() {
	$email_to = bw_array_get( $_REQUEST, "oiku_email_to", null );
	$message = bw_get_message();
	if ( $email_to && $message ) {
		oik_require( "includes/oik-contact-form-email.inc" );
		$fields = array();
		$subject = bw_get_subject();
		$fields['comment_content'] = $message;
		$fields['comment_author'] = bw_array_get( $_REQUEST, "oiku_name", null );
		$fields['comment_author_email'] = bw_array_get( $_REQUEST, "oiku_email", null );
		$fields['comment_author_url'] = null;
		$fields['comment_type'] = 'oik-contact-form';
		$send = bw_akismet_check( $fields );
		if ( $send ) {
			$message .= "<br />\r\n";
			$message .= retlink( null, get_permalink() );
			$fields['message'] = $message;
			$fields['contact'] =  $fields['comment_author'];
			$fields['from'] = $fields['comment_author_email']; 
			$sent = bw_send_email( $email_to, $subject, $message, null, $fields );
		} else {
			$sent = true; // Pretend we sent it.
		}
		bw_thankyou_message( $fields, $send, $sent );
	} else {
		$sent = false;
		if ( !function_exists( "bw_issue_message" ) ) { 
			oik_require( "includes/bw_messages.php" );
		}  
		$text = __( "Invalid. Please correct and retry.", "oik" );
		bw_issue_message( null, "bw_field_required", $text );
		$displayed = bw_display_messages();
		if ( !$displayed ) {
			p_( $text );
		}  
	}
	return( $sent );
}

/**
 * Implement help hook for bw_contact_form
 */
function bw_contact_form__help( $shortcode="bw_contact_form" ) {
	return( __( "Display a contact form for the specific user", "oik" ) );
}

/**
 * Syntax hook for [bw_contact_form] shortcode
 */
function bw_contact_form__syntax( $shortcode="bw_contact_form" ) {
	$syntax = array( "user" =>  BW_::bw_skv( bw_default_user(), "<i>" . __( "id", "oik" ) . "</i>|<i>" . __( "email", "oik" ) . "</i>|<i>" . __( "slug", "oik" ) . "</i>|<i>" . __( "login", "oik" ) . "</i>", __( "Value to identify the user", "oik" ) )  
								 , "contact" => BW_::bw_skv( null, "<i>" . __( "text", "oik" ) . "</i>", __( "Text for submit button", "oik" ) )
								 , "email" => BW_::bw_skv( null, "<i>" . __( "email", "oik" ) . "</i>", __( "Email address for submission", "oik" ) ) 
								 );
	$syntax += _sc_classes();
	return( $syntax );
}

/**
 * Implement example hook for [bw_contact_form] 
 *
 */
function bw_contact_form__example( $shortcode="bw_contact_form" ) {
	//oik_require( "shortcodes/oik-user.php", "oik-user" );
	$id = bw_default_user( true ); 
	$example = "user=$id"; 
	$text = __( "Display a contact form for user: $id " );
	bw_invoke_shortcode( $shortcode, $example, $text );
}

/**
 * Implement snippet hook for [bw_contact_form]
 */
function bw_contact_form__snippet( $shortcode="bw_contact_form" ) {
	$contact = bw_array_get( $_REQUEST, "oiku_contact", null );
	if ( $contact ) {
		p( "Note: If the form is submitted from Shortcode help then two emails would be sent." );
		p( "So the normal snippet code is not invoked in this case." );
	} else {  
		//oik_require( "shortcodes/oik-user.php", "oik-user" );
		$id = bw_default_user( true ); 
		$example = "user=$id"; 
		_sc__snippet( $shortcode, $example );
	}
}
