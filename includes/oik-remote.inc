<?php // (C) Copyright Bobbing Wide 2012, 2013

/**
 * Wrapper to wp_remote_get
 * @param string $url with parameters already added
 * @return decoded result - a json object OR null 
 */ 
function bw_remote_get( $url ) {
  $request = wp_remote_get( $url );
  if ( is_wp_error ($request ) ) {
    bw_trace2( $request, "request is_wp_error" );
    $result = null;
  } else {
    $json = wp_remote_retrieve_body( $request );
    bw_trace2( $json );
    if ( empty( $json ) ) {
      $result = null;
    } else {
      $result = json_decode( $json );
    }  
  }
  bw_trace2( $result, "result" );
  return( $result );
}

/**
 * Wrapper to wp_remote_get2
 * @param string $url with parameters already added
 * @return 
 */ 
function bw_remote_get2( $url ) {
  $request = wp_remote_get( $url );
  if ( is_wp_error ($request ) ) {
    bw_trace2( $request, "request is_wp_error" );
    $result = null;
  } else {
    $result = bw_retrieve_result( $request );
  }
  bw_trace2( $result, "result" );
  return( $result );
}

/**
 * Return the result if the response code is 200 ( OK ) 
 * @param $result from wp_remote_post()
 * @return 
 */
function bw_retrieve_result( $request ) {
  $response_code = wp_remote_retrieve_response_code( $request );
  if ( $response_code == 200 ) {
    $response = wp_remote_retrieve_body( $request );
    bw_trace2( $response, $response_code );
    if ( empty( $response ) ) {
      $result = null;
    } else {
      $result = maybe_unserialize( $response ); //json_decode( $json );
    }
    //bw_trace2( "can we see cookies?", "can we?" );
  } else {
    bw_trace2( $response_code, "unexpected response_code" );
    $response_message = wp_remote_retrieve_response_message( $request );
    bw_trace2( $response_message, "response_message" );
    $result = null;
  }
  return( $result );      
}

/**
 * Wrapper to wp_remote_post
 * @param string $url the request URL
 * @param array $args - array of args including body array 
 * @return unserialized result or null
 */ 
function bw_remote_post( $url, $args ) {
  $request = wp_remote_post( $url, $args );
  if ( is_wp_error ($request ) ) {
    bw_trace2( $request, "request is_wp_error" );
    $result = $request; // Was NULL
  } else {
    $result = bw_retrieve_result( $request );
  }
  bw_trace2( $result, "result" );
  return( $result );
}

/**
 * Update site_transient_update_plugins for each of our plugins
 * 
 * On the second call to this function we perform our own checks to see if the plugins have been updated.
 * 
 * The response array is updated for each plugin with a new version.
 * 
 * 
 * @param array $transient - structure as in example below
 * @return array $transient - with updates to the response array
 *
 * $plugin_slug is the path and plugin name e.g. oik/oik.php 
 *
 *  [last_checked] => 1342601824
 *  [checked] => Array
 *      (
 *          [backwpup/backwpup.php] => 2.1.11
 *          [oik-pro/oik-altapi.php] => 1.0
 *      )
 *
 *  [response] => Array
 *      (
 *          [backwpup/backwpup.php] => stdClass Object
 *              (
 *                  [id] => 8736
 *                  [slug] => backwpup
 *                  [new_version] => 2.1.12
 *                  [url] => http://wordpress.org/extend/plugins/backwpup/
 *                  [package] => http://downloads.wordpress.org/plugin/backwpup.2.1.12.zip
 *              )
 *      )
 *
 */
function oik_lazy_altapi_check( $transient ) {
  bw_backtrace();
  static $checks = 0;
  static $responses = array();
  static $responses_built = false;
  $checks++;
  bw_trace2( $checks, "checks" );
  if ( !$responses_built ) {
    $checked = bw_array_get( $transient, "checked", null );
    if ( $checked ) {
      foreach ( $checked as $plugin => $version ) {
        $response = oik_check_for_update( $plugin, $version );
        if ( $response && !is_wp_error( $response ) ) {
          $responses[$plugin] = $response;
        }  
      } 
      $responses_built = true;
    }
  }
  if ( $responses_built ) {
    foreach ( $responses as $plugin => $response ) {      
      $transient->response[$plugin] = $response;
    }
    bw_trace2( $responses, "transient updated" );
  }  
  return( $transient );  
}


/**
 * 
 
  


option__site_transient_update_themes stdClass Object
(
    [last_checked] => 1367174636
    [checked] => Array
        (
            ...
            [designfolio] => 1.22
            ...
            [oik120206] => 1.0
            [oik120206c] => 
            [oik2012] => 0.2
            [oik20120] => 0.1
            ...
            [thematic] => 1.0.3.2
            ...
        )

    [response] => Array
        (
            [designfolio] => Array
                (
                    [new_version] => 1.23
                    [url] => http://wordpress.org/extend/themes/designfolio
                    [package] => http://wordpress.org/extend/themes/download/designfolio.1.23.zip
                )

            [thematic] => Array
                (
                    [new_version] => 1.0.4
                    [url] => http://wordpress.org/extend/themes/thematic
                    [package] => http://wordpress.org/extend/themes/download/thematic.1.0.4.zip
                )

        )

*/

function oik_lazy_alttheme_check( $transient ) {
  static $checks = 0;
  static $responses = array();
  static $responses_built = false;
  $checks++;
  bw_trace2( $checks, "checks" );
  if ( !$responses_built ) {
    $checked = bw_array_get( $transient, "checked", null );
    if ( $checked ) {
      foreach ( $checked as $theme => $version ) {
        $response = oik_check_for_theme_update( $theme, $version );
        if ( $response && !is_wp_error( $response ) ) {
          $responses[$theme] = $response;
        }  
      } 
      $responses_built = true;
    }
  }
  if ( $responses_built ) {
    foreach ( $responses as $theme => $response ) {      
      $transient->response[$theme] = $response;
    }
    bw_trace2( $responses, "transient updated" );
  } 
  return( $transient );  
}

/**
 * Check an oik-plugins server for an updated plugin 
 * @param string $plugin plugin name e.g. oik-edd/oik-edd.php
 * @param string $version currently installed version e.g 1.03
 * @return $response if there is a new version else null
 *
 * Note: We pass the API key even if it doesn't get used in the server
 *
 */
function oik_check_for_update( $plugin, $version ) {
  bw_trace2();
  $response = null;
  $plugin_settings = oik_query_plugins_server( bw_get_slug( $plugin ) );
  if ( $plugin_settings ) {
    $url = bw_array_get( $plugin_settings, 'server', null );
    $url .= '/plugins/update-check/';
    $apikey = bw_array_get( $plugin_settings, 'apikey', null );
    $body = array( "action" => "update-check" 
                 , "plugin_name" => $plugin 
                 , "version" => $version
                 , "apikey" => $apikey
                 );
    $args = array( "body" => $body, "timeout" => 10 );
    $result = bw_remote_post( $url, $args ); 
    if ( $result ) {
      bw_trace2( $result );
      if ( is_wp_error( $result ) ) {
          $response = $result;
      } else { 
        //bw_trace2( $result->new_version, "$version!", false ); 
        //$vc = version_compare( $result->new_version, $version, ">" );
        //bw_trace2( $result->new_version, "new version", false );
        //bw_trace2( $version, "old version", false );
        //bw_trace2( $vc, "vc result", false );
        if( isset( $result->new_version ) && version_compare( $result->new_version, $version, '>' ) ) {
          $response = $result;  
          bw_trace2();
        }  
      }  
    } 
  }
  return( $response );    
}


/**
 * Check an oik-themes server for an updated theme
 * @param string $theme theme name e.g. oik-edd/oik-edd.php
 * @param string $version currently installed version e.g 1.03
 * @return $response if there is a new version else null
 *
 * Note: We pass the API key even if it doesn't get used in the server
 *
 */
function oik_check_for_theme_update( $theme, $version ) {
  bw_trace2();
  $response = null;
  $theme_settings = oik_query_themes_server( $theme );
  if ( $theme_settings ) {
    $url = bw_array_get( $theme_settings, 'server', null );
    $url .= '/themes/update-check/';
    $apikey = bw_array_get( $theme_settings, 'apikey', null );
    $body = array( "action" => "update-check" 
                 , "theme_name" => $theme 
                 , "version" => $version
                 , "apikey" => $apikey
                 );
    $args = array( "body" => $body, "timeout" => 10 );
    $result = bw_remote_post( $url, $args ); 
    if ( $result ) {
      bw_trace2( $result, "result-ocftu" );
      if ( is_wp_error( $result ) ) {
        $response = $result;
      } else { 
        if( isset( $result->new_version ) && version_compare( $result->new_version, $version, '>' ) ) {
          // WordPress will issue a Fatal if this is an object.
          $response = (array) $result;  
          bw_trace2( $response, "response=returned-result");
        } else {
          // These can produce info messages if $result->new_version is not set  
          // bw_trace2( $result->new_version, "$version!", false ); 
          // $vc = version_compare( $result->new_version, $version, ">" );
          // bw_trace2( $result->new_version, "new version", false );
          bw_trace2( $version, "old version", false );
          // bw_trace2( $vc, "vc result", false );
        }
      }  
    } else {
      //gobang();
    } 
  }
  return( $response );    
}

/**
 * Determine if this is one of "our" plugins
 *
 * @param mixed $args - object or array expected to contain "slug"
 * @return string $server - URL to connect with 
 *
 * Note: Can we trust slug? Could it be "plugin/plugin-file.php" or just "plugin"
 * It may depend upon the $action
 *
 */
function oikp_our_plugin( $args ) {
  $args_slug = bw_array_get( $args, "slug", null );
  if ( $args_slug ) {
    $plugin_settings = oik_query_plugins_server( $args_slug );
    $server = bw_array_get( $plugin_settings, 'server', null );
  }  
  else 
    $server = null;
  return( $server );
} 

/**
 * Implement "plugin_information" for our own plugins
 

    stdClass Object
        (
            [slug] => backwpup
            [per_page] => 24
        )
        
"http://api.wordpress.org/plugins/info/1.0/$plugin_slug.xml";


 */
function oik_lazy_pluginsapi( $false, $action, $args ) {
  bw_trace2();
  $response = false; 
  if ( $action == "plugin_information" ) {
    $url = oikp_our_plugin( $args);      
    /* We have to exclude the version number (i.e. exclude 1.0 from plugins/info/1.0/ ) since the server end's permalink structure does not expect it */
    if ( $url ) { 
      $url .= "/plugins/info/";
      $post_args =  array( 'timeout' => 15
                         , 'body' => array('action' => $action, 'request' => serialize($args))
                         );
      $response = bw_remote_post( $url, $post_args ); 
    }  
  }
  bw_trace2( $response, "response", false );
  return( $response );
}

/**
 * Implement "themes_api" for our own themes 
 * 
 * @param bool $false - false 
 * @param string $action - one of a set of actions
 * - "query_themes" - see below
 * - "feature_list" 
 * - "theme_information" 
 * 
 * @param object $args - for "query_themes"
 * 

(
    [0] => 
    [1] => query_themes
    [2] => stdClass Object
        (
            [page] => 1
            [per_page] => 36
            [fields] => 
            [browse] => featured
        )

)
  where [browse] can be
  "featured" - list the Featured themes
  "new" - list the Newest themes
  "updated" - list the Recently Updated themes
  
  or [search] to search by keyword
  or [author] to search by author
  or [tag] which is an array of tags. e.g.
   
              [tag] => Array
                (
                    [0] => blue
                )

        )
        
  $args for "theme_information"
  array('slug' => $theme, 'fields' => array('sections' => false, 'tags' => false)
      

  
 */
function oik_lazy_themes_api( $false, $action, $args ) {
  bw_backtrace();
  bw_trace2();
  $response = false;
  return( $response );
}

/**
 * Implement "themes_api_result" filter to add our own themes 
 

  @return unserialised object $result
  (
    [0] => stdClass Object
        (
            [info] => Array
                (
                    [page] => 1
                    [pages] => 1
                    [results] => 6
                )

            [themes] => Array
                (
                    [0] => stdClass Object
                        (
                            [name] => DailyPost
                            [slug] => dailypost
                            [version] => 1.0.5
                            [author] => valeriutihai
                            [preview_url] => http://wp-themes.com/dailypost
                            [screenshot_url] => http://wp-themes.com/wp-content/themes/dailypost/screenshot.png
                            [rating] => 83.4
                            [num_ratings] => 6
                            [homepage] => http://wordpress.org/themes/dailypost
                            [description] => DailyPost is intresting theme ideal for your everyday notes and thoughts, which supports post formats and several customisation options. The theme is a special one because of it's responsive design, thus you will get the pleasure to read the post with your mobile device.
                        )
                     ...
                     one per [results]

 */
function oik_lazy_themes_api_result( $result, $action, $args ) {
  bw_backtrace();
  bw_trace2();
  /** 
  * We need to do this for EACH theme that is installed to find which servers to query.
  * http://api.wordpress.org/themes/info/1.0/', array( 'body' => array('action' => $action, 'request' => serialize($args)))
  *
  */
  $url = "http://www.oik-plugins.com";
  $url = "http://qw/wpit";
  $url .= "/themes/info/";
  $post_args =  array( 'timeout' => 15
                     , 'body' => array('action' => $action, 'request' => serialize($args))
                     );
  $add_result = bw_remote_post( $url, $post_args );
  if ( $add_result ) {
    $response = oik_merge_result( $result, $add_result );
  } else {
    $response = $result; 
  }
  bw_trace2( $response, "response", false );
  return( $response );
} 


/**
 * Differences between responses for different requests
 *
 * [browse] = "featured" doesn't return much information in the "info" section. 
 * Perhaps this is because there is less than one page! We could test this by setting "per_page" to a low number.
 * OR have a look at what happens with "search". See below
 *
            [info] => Array
                (
                    [page] => 1
                    [pages] => 0
                    [results] => 
                )
 * [browse] = "new"  - same as featured
 * [browse] = "updated" - same as featured
 * 
 * [search] = keyword
 * [author] = author
             [info] => Array
                (
                    [page] => 1
                    [pages] => 1
                    [results] => 27
                    [author] => 
                )

 * [tag] =    tags
 * 
 * The answer is tricky since ajax takes over at the front end.  
 * So we probably need to see what happens during this processing
 (
    [action] => fetch-list
    [paged] => 3
    [s] => 
    [tab] => search
    [type] => term
    [_ajax_fetch_list_nonce] => 5962dafe3a
    [list_args] => Array
        (
            [class] => WP_Theme_Install_List_Table
            [screen] => Array
                (
                    [id] => theme-install
                    [base] => theme-install
                )

        )

)
*/ 
               







