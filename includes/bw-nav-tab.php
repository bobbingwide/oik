<?php // (C) Copyright Bobbing Wide 2014-2017

/**
 * Create a nav-tab link
 * 
 * @param string $nav_tab - the nav_tab identifier 
 * @param string $nav_label - the translated label
 * @param string $page - the value for the page parameter
 * @param string $tab - the currently selected tab
 */
function bw_nav_tab_link( $nav_tab, $nav_label, $page, $tab ) {
  $class = "nav-tab nav-tab-$tab"; 
  if ( $nav_tab == $tab ) {
    $class .= " nav-tab-active";
  }
  $link = admin_url("admin.php?page=$page&amp;tab=$nav_tab"); 
  BW_::alink( $class, $link, $nav_label );
}

/**
 * Display the nav_tabs
 *
 * Many WordPress admin pages display a series of tabs
 * and underneath that we see may also see a subsection.
 * Most routines hard code the logic.
 * This is the bobbingwide/oik approach.
 *
 * In any admin page that that can display a series of tabs
 * you code bw_nav_tabs() passing the defaults for the first page that you're creating
 * That seems fair enough.
 * The routine invokes "bw_nav_tabs_$page", passing the name of the currently selected $tab
 * 
 * The implementing plugin can use the tab name to decide whether or not to load any code.
 * Which will get invoked by the action invoked subsequently.
 * 
 * See oik-clone for an example.
 * Note: If there is no $page then the filter invoked is "bw_nav_tabs_"
 *
 * If you want to display subsections then you do this in action hook for the selected tab.
 * @TODO Code to be developed - for different Authentication methods of the WP-API solution
 *
 * @param string $default_tab - the default tab for a tabbed admin page
 * @param string $default_label - the default label for a tabbed admin page
 * @return string $tab - the currently selected tab  
 */
function bw_nav_tabs( $default_tab=null, $default_label=null ) { 
  $tab = bw_array_get( $_REQUEST, "tab", $default_tab );
  //bw_trace2( $tab, "tab" );
  $_REQUEST['tab'] = $tab;
  $page = bw_array_get( $_REQUEST, "page", null );
  stag( "h2", "nav-tab-wrapper");
  $nav_tabs = array( $default_tab => $default_label );
  $nav_tabs = apply_filters( "bw_nav_tabs_$page", $nav_tabs, $tab );
  foreach ( $nav_tabs as $nav_tab => $nav_label ) {
    bw_nav_tab_link( $nav_tab, $nav_label, $page, $tab );
  }  
  etag( "h2" );
  return( $tab );
}

