<?php
/**
 * oik-shortcodes library function polyfill.
 *
 * @copyright  (C) Copyright Bobbing Wide 2011-2020
 * @package oik
 *
 * Plugins such as oik-css from v1.0.0 and uk-tides v2.0.0 believe this is the file to load if oik is installed but not activated.
 * They have to do this if oik is v3.3.7 or earlier.
 * In versions before v.4.0 this file contains the functions that have been migrated to the oik-shortcodes shared library.
 * We can't have a plugin load load the shared library functions then have an earlier version of oik load oik-add-shortcodes.php
 * as this creates problems with duplicate functions.
 */
oik_require_lib( 'oik-shortcodes' );

