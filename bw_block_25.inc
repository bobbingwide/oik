<?php // (C) Copyright Bobbing Wide 2010-2013
/* bw_block_25.inc
   oik blocks functions for Artisteer level 2.5 themes
*/

/**
 * Syntax [bw_block class="classes" title="title text" framed="y/n,t/f,1/0"]
 * Future use parameters are
 *  prefix="art-"
 *  level="h3" - heading level for the block title
 */
function bw_block_25( $atts ) { 
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
  sartblock_25( $title, $framed );
  return( bw_ret());

}

/**
 * Syntax for [bw_eblock] 
 * There are no parameters. We use this shortcode to end a block
 * Using this technique means we can nest blocks within blocks.
   
*/ 
function bw_eblock_25( $atts ) {
  eartblock_25();
  return( bw_ret());
}

/**
 * Future use function
 */
function bw_header_25( $atts ) {
  extract( shortcode_atts( array(
      'prefix' => 'art-',
      'level' => 'h3',
      'title' => NULL, 
      'link' => NULL
      ), $atts ) );
  artblockheader( $title );
  return( bw_ret());
}


/**
 * Create a block header if the title text is specified 
 */
function artblockheader_25( $title=NULL, $link=NULL, $icon=NULL ) {
  if ( $title ) {  
    sdiv( "art-blockheader");
    //sdivslr( "art-header-tag-icon" );
    sediv( 'l' );
    sediv( 'r' );
    h3( $title, "t" );
    //sdiv( "t" );
    //t( $title );
    ediv();
  }
} 

/** 
 * Future use - 
 */
function artblockheaderNoIcon_25( $title ) {  
  sdiv( "art-blockheader");
  sdivslr( "t" );
  //h3( $title );
  t( $title );
  ediv();
  ediv();
}      

/**
 * draw the frame around the block header
 */
function artblockframe_25() {
  sediv( "art-block-tl" );
  sediv( "art-block-tr" );
  sediv( "art-block-bl" );
  sediv( "art-block-br" );
  sediv( "art-block-tc" );
  sediv( "art-block-bc" );
  sediv( "art-block-cl" );
  sediv( "art-block-cr" );
  sediv( "art-block-cc" );
} 

/**
 * draw the frame around the block content
 */
function artblockcontentframe_25() {
  sediv( "art-blockcontent-tl" );
  sediv( "art-blockcontent-tr" );
  sediv( "art-blockcontent-bl" );
  sediv( "art-blockcontent-br" );
  sediv( "art-blockcontent-tc" );
  sediv( "art-blockcontent-bc" );
  sediv( "art-blockcontent-cl" );
  sediv( "art-blockcontent-cr" );
  sediv( "art-blockcontent-cc" );
} 
 

/**
 * start an Artisteer style block
 */
function sartblock_25( $title=NULL, $framed=TRUE ) {
   sdiv( "art-block" );
     if ($framed )
     {
        artblockframe_25();
     }   
     artblockheader_25( $title );
     sdiv( "art-blockcontent" );
     if ( $framed ) {
       artblockcontentframe_25();
     }  
     sdiv( "art-blockcontent-body ");
}


/**
 * end an Artisteer style block
 */
function eartblock_25( $contentFunc = NULL ) {

   if ( !is_null( $contentFunc ))      
     $contentFunc();
   ediv(); // art-blockcontent-body
   ediv(); // art-blockcontent
   ediv(); // art-block
    global $div_class;
   ediv( $div_class );
   
}  

