<?php // (C) Copyright Bobbing Wide 2012, 2013, 2019
/**
 * 
 * Lazy code for implementing the [] quicktag
 * The JavaScript code is pre-requisite to the TinyMCE [] button
 *
 * @link https://wordpress.org/support/topic/edit_page_form-versus-edit_form_advanced?replies=4
 * - edit_page_form is for PAGES
 * - edit_form_advanced is for POSTS
*/
add_action( "edit_form_advanced", "bw_load_admin_scripts" );
add_action( "edit_page_form", "bw_load_admin_scripts" );


