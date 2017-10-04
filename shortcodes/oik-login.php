<?php // (C) Copyright Bobbing Wide 2013-2017

/**
 * Implement [bw_login] shortcode
 *
 * @param array $atts - shortcode parameters
 * @param string $content - not expected
 * @param string $tag - 
 * @link http://pippinsplugins.com/wordpress-login-form-short-code/ copied and cobbled code 2013/01/15
 * @link http://codex.wordpress.org/Function_Reference/wp_login_form
 
 <code>
 
        'echo' => true,
        'redirect' => site_url( $_SERVER['REQUEST_URI'] ), 
        'form_id' => 'loginform',
        'label_username' => __( 'Username' ),
        'label_password' => __( 'Password' ),
        'label_remember' => __( 'Remember Me' ),
        'label_log_in' => __( 'Log In' ),
        'id_username' => 'user_login',
        'id_password' => 'user_pass',
        'id_remember' => 'rememberme',
        'id_submit' => 'wp-submit',
        'remember' => true,
        'value_username' => NULL,
        'value_remember' => false ); 
 </code>        
        
Note: remember is whether or not the values are remembered
There is also lost password... 
@link http://codex.wordpress.org/Function_Reference/wp_lostpassword_url

 */
function bw_login_shortcode( $atts=null, $content=null, $tag=null ) {
  
  if ( is_user_logged_in() ) {
    if ( $content ) {
      $form =  bw_do_shortcode( $content );
    } else {
      $form = null;
    }
  } else {
    $redirect = bw_array_get_dcb( $atts, "redirect", null );
    if ( $redirect == null ) { 
      $redirect = get_permalink(); 
    } 
    $remember = bw_array_get( $atts, "remember", true );
    $args = array( "redirect" => $redirect
                 , "remember" => $remember
                 , "echo" => false
                 );
    $form = wp_login_form( $args );
  }  
  return( $form );
}

/**
 * Help for [bw_login] shortcode
 */
function bw_login__help( $shortcode="bw_login" ) {
  return( __( "Display the login form or protected content", "oik" ) );
}

/**
 * Syntax for [bw_login] shortcode
 */
function bw_login__syntax( $shortcode="bw_login" ) {
  $syntax = array( "redirect" => BW_::bw_skv( "", __( "ID", "oik" ), __( "post ID to redirect to", "oik" ) )
                 , "remember" => BW_::bw_skv( "Y", "N", __( "display 'Remember me?' checkbox", "oik" ) )
                 );
  return( $syntax );                 
}

/**
 * Implement the [bw_loginout] shortcode
 */
function bw_loginout_shortcode( $atts=null, $content=null, $tag=null ) {
  $redirect = bw_array_get( $atts, "redirect", null );
  $form = wp_loginout( $redirect, false );
  return( $form );
}

/**
 * Help hook for [bw_loginout] 
 */
function bw_loginout__help( $shortcode="bw_loginout" ) {
  return( __( "Display the Login or Logout link", "oik" ) );
}

/**
 * Implement the [bw_register] shortcode
 */ 
function bw_register_shortcode( $atts=null, $content=null, $tag=null ) {
  $form = wp_register( '', '', false );
  return( $form );
} 

function bw_register__help( $shortcode="bw_register" ) {
  return( __( "Display a link to the Registration form, if Registration is enabled", "oik" ) );
}
 
  
