<?php 

/* bw_block.inc
   oik blocks functions for themes not developed with Artisteer 
   Note: These function have only been tried out with Twenty-Eleven and the default BuddyPress theme
   They are here simply to support development when we have to revert to a non-Artisteer theme but still want to use 
   the [bw_block] and [bw_eblock] shortcode.
   
*/

 

/**
 * Syntax [bw_block class="classes" title="title text" framed="y/n,t/f,1/0"]
 * Future use parameters are
 *  prefix="art-"
 *  level="h3" - heading level for the block title
 */
function bw_block_( $atts ) { 
  bw_trace( $atts, __FUNCTION__, __LINE__, __FILE__, "atts" );
  extract( shortcode_atts( array(
      'prefix' => 'art-',
      'level' => 'h3',
      'title' => NULL, 
      'framed' => true,
      'class' => NULL,
      ), $atts ) );
  /* We can't pass the prefix or heading level yet */   
  /* the block is enclosed in a div which can be used to control the width and depth 
     We save the div_class just in case it's needed in the bw_eblock processing
     Actually, this is a bit daft. Since it is possible to nest blocks within blocks
     the saved value will be that of the most recently nested blocks. So if we wanted to be able to do something with 
     it we would need to manage a stack.
     
  */ 
  $framed = bw_validate_torf( $framed );  
  global $div_class;
  $div_class = $class;
  sdiv( $class ); 
  sblock_( $title, $framed );
  return( bw_ret());

}

/**
 * Syntax for [bw_eblock] 
 * There are no parameters. We use this shortcode to end a block
 * Using this technique means we can nest blocks within blocks.
   
*/ 
function bw_eblock_( $atts ) {
  eblock_();
  return( bw_ret());
}

/**
 * Future use function
 */
function bw_header_( $atts ) {
  extract( shortcode_atts( array(
      'prefix' => 'art-',
      'level' => 'h3',
      'title' => NULL, 
      'link' => NULL
      ), $atts ) );
  blockheader_( $title );
  return( bw_ret());
}


/**
 * Create a block header if the title text is specified 
 */
function blockheader_( $title=NULL, $link=NULL, $icon=NULL ) {
  if ( $title ) {  
    sdiv( "blockheader");
    h3( $title );
    ediv();
  }
} 


/**
 * draw the frame around the block header
 */
function blockframe_() {
} 

/**
 * draw the frame around the block content
 */
function blockcontentframe_() {
} 
 

/**
 * start a block
 */
function sblock_( $title=NULL, $framed=TRUE ) {
   sdiv( "block" );
     if ($framed )
     {
        blockframe_();
     }   
     blockheader_( $title );
     sdiv( "blockcontent" );
     if ( $framed ) {
       blockcontentframe_();
     }  
     sdiv( "blockcontent-body ");
}


/**
 * end an Artisteer style block
 */
function eblock_( $contentFunc = NULL ) {

   if ( !is_null( $contentFunc ))      
     $contentFunc();
   ediv(); // blockcontent-body
   ediv(); // blockcontent
   ediv(); // block
    global $div_class;
   ediv( $div_class );
   
}  
