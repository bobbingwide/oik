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
class OIK_Autoload {

	/**
	 * Array of information about classes to load
	 * Format of each item in the array
	 * "classname" => array( "class"=> "plugin"=> "file"=> ) 
	 * 
	 * @TODO Should also support theme? 
	 */
	public static $loads;
	
	/**
	 * Array of available classes
	 */
	public $classes;
	
	/**
	 * @var OIK_autoload - the true instance
	 */
	private static $instance;
	
	/**
	 * Return a single instance of this class
	 *
	 * @return object 
	 */
	public static function instance() {
		if ( !isset( self::$instance ) && !( self::$instance instanceof self ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	/**
	 * Constructor for the OIK_autoload class
	 */
	function __construct() {
		self::$loads = array();
		$loads_more = apply_filters( "oik_query_autoload_classes", self::$loads );
		self::$loads = $loads_more;
		$this->classes = null;
		//self::loads( $loads_more );
		spl_autoload_register( array( $this, 'autoload' ) );
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
		$class_file = $this->locate_class( $class );
		if ( $class_file ) {
			$file = $this->file( $class_file );
			oik_require( $file, $class_file->plugin );
		}
	}
	
	/**
	 * Determine the file name from the class and path
	 * 
	 * 
	 */
	function file( $class_file ) {
		//bw_trace2();
		$file = $class_file->file;
		if ( !$file ) {
			$file = str_replace( "_", "-", $class_file->class );
			$file = strtolower( $file );
			$file = "class-$file.php";
			if ( $class_file->path ) {
				$file = $class_file->path . '/' . $file;
			}
		}
		return( $file );
	}
	
	/**
	 * Locate the required class
	 * 
	 * self::$loads contains the raw information about classes we may want to load
	 * self::$classes is the post processed version
	 */
	function locate_class( $class ) {
		if ( !isset( $this->classes ) ) {
			$this->set_classes();
		}
		$class_file = bw_array_get( $this->classes, $class, null );
		$class_file = (object) $class_file;
		bw_trace2( $class_file, "class_file" );
		return( $class_file );
	}
	
	/**
	 * Register a set of classes that can be autoloaded
	 * 
	 * Here we receive an array of classes that may or may not be complete.
	 * We should allow multiple definitions and extract the class name from the definition
	 * if it's not given in the key. 
	 * @TODO Can this be deferred until the actual autoload() is requested? 
	 * 
	 * 
	 * 
	 *
	 * @TODO Each class should specify its version and dependencies
	 * 
		$class_file = bw_array_get( self::$loads, $class, null );
	 */
	function set_classes() {
		bw_trace2();
		foreach ( self::$loads as $class => $load ) {
			self::set_class( $class, $load );
		}
	}
	
	/**
	 * Register a class that can be autoloaded
	 *
	 * If the $class is numeric we need to extract the name from the array
	 */
	function set_class( $class, $load ) {
		bw_trace2( $load, $class );
		if ( is_numeric( $class ) ) {
			$class = $load[ "class" ];
		}
		$this->classes[ $class ] = $load;
	}

	/**
	 * Apply "oik_autoload" filter/action
	 * 
	 * Not a good idea since we don't know which hooks to filter on
	 * so perhaps it should just be an action hook
	 */
	function nortoload( $loads_more ) {
		self::loads( $loads_more );
		return( $loads_more );
	}


}
