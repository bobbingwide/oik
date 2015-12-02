<?php // (C) Copyright Bobbing Wide 2015

/**
 * Implement autoloading for shared libraries
 *
 * The autoload function is not supposed to load the class willy nilly
 * it needs to check compatibility with the libraries that are already loaded
 * 
 * {@link http://woocommerce.wp-a2z.org/oik_api/wc_autoloaderautoload}
 * 
 *
 */
class OIK_autoload {

	/**
	 * Array of information about classes to load
	 * Format of each item in the array
	 * "classname" => array( "class"=> "plugin"=> "file"=> ) 
	 * 
	 * @TODO Should also support theme? 
	 */
	public $loads;

	/**
	 * Constructor for the OIK_autoload class
	 */
	function __construct() {
		$this->loads = array();
	}

	/** 
	 * Autoload a class if we know how to
	 * 
	 * The fact that we have gotten here means that the class is not already loaded so we need to load it.
	 * @TODO We should also know which pre-requisite classes to load. Does spl_autoload_register() handle this?
	 * 
	 * What if we can't?
	 */
	function autoload( $class ) {
		$class_file = bw_array_get( $this->loads, $class, null );
		if ( $class_file ) {
			oik_require( $class_file->file, $class_file->plugin );
		}
		
	}

	function loads( $loads_more ) {
		foreach ( $loads_more as $class => $load ) {
			$this->loads[ $class ] = $load;
		}
	}

	/**
	 * Apply "oik_autoload" filter/action
	 * 
	 * Not a good idea since we don't know which hooks to filter on
	 * so perhaps it should just be an action hook
	 */
	function nortoload( $loads_more ) {
		$this->loads( $loads_more );
		return( $loads_more );
	}


}
