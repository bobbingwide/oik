<?php // (C) Copyright Bobbing Wide 2015, 2016

/**
 * Load an array of node references - for hierarchical post types
 *
 * @param string/array $args - array of args containing the #type of the node to load
 * Note: #type may also be an array of post_types
 * @returns array $options - array of nodes keyed by ID with value of title
 *
 * Note: You can simply pass this as a string if you so wish 
 * 
 * **?** This is probably an inefficient use of get_posts, especially for very large lists
 * **?** Could we not just pass $args to bw_get_posts to allow the returned list to be fine tuned
 * 
 * 
 */
function bw_load_noderef2( $args ) {
  oik_require( "includes/bw_posts.inc" );
  $post_types = array();
  $post_type = bw_array_get( $args, '#type', $args );
  if ( is_array( $post_type ) ) { 
    $post_types = $post_type;
  } else {
    $post_types[] = $post_type;
  }   
    
  $options = array();
  foreach ( $post_types as $post_type ) { 
    if ( is_post_type_hierarchical( $post_type ) ) {
      $options += bw_load_noderef2_hier( $post_type, $args );
    } else {
     $options += bw_load_noderef2_flat( $post_type, $args );
    }
  }
  return( $options );
}

/**
 * Load flat posts
 *
 * @param string $post_type
 * @param array $args additional parameters to bw_get_posts
 * @return array of the options
 */
function bw_load_noderef2_flat( $post_type, $args ) { 
  if ( $post_type !== "attachment" ) {
    $args['post_parent'] = 0; 
  } else {
		unset( $args['post_parent'] );
		$post_type = array( $post_type, 1 );	
  }
  $args['post_type'] = $post_type;
  $posts = bw_get_posts( $args );
  $options = bw_post_array( $posts );
  return( $options );
}

/**
 * Load hierarchical posts
 *
 * @param string $post_type - the post type to load
 * @param array $args - other parameters to get_pages() 
 */
function bw_load_noderef2_hier( $post_type, $args ) { 
  $args['post_type'] = $post_type;
  $posts = get_pages( $args );
  $options = bw_post_array_hier( $posts );
  return( $options );
}  

/**
 * Simplify post array to the hierarchical order
 *
 * Does this keep the posts in the order they were retrieved by get_pages?
 *
 * @param array $posts - array of post objects
 * @return array $options - array of post options
 */  
function bw_post_array_hier( $posts ) {
  $args = array();
  $args['depth'] = 0;
  $args['selected'] = 0;
  $walker = new bw_post_Walker;
  $args['walker'] = $walker;
  $output = walk_page_dropdown_tree( $posts, 0, $args );
  //echo $output;
  $options = $walker->post_array;
  bw_trace2( $options ); 
  return( $options );
}

/**
 * bw_post_Walker class
 *
 * We use the Walker class to create the tree of posts
 * in a form we can use for displaying "noderef" fields
 * which may be of multiple post types.
 *
 * This class is not intended to be used as a normal walker class
 * Rather than creating HTML it saves its output
 *
 */
class bw_post_Walker extends Walker {

	public $db_fields = array ('parent' => 'post_parent', 'id' => 'ID');
  
  public $post_array = array();
  
  //public function start_lvl( &$output, $depth=0, $args=array() ) {
    //bw_trace2();
  //}
  
	public function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) {
    $title = str_repeat( "&nbsp;&nbsp;&nbsp;", $depth );
    $title .= $object->post_title;
    $this->post_array[ $object->ID ] = $title;
    /*
    static $count;
    $count++;
    echo $count;
    echo ",";
    echo $object->ID;
    echo ",";
    echo $object->post_parent;
    echo ",";
    echo $depth;
    echo PHP_EOL;
    */
  }

}

