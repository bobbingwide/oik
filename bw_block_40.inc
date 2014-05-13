<?php // (C) Copyright Bobbing Wide 2012

/* bw_block_40.inc - functions for creating blocks with Artisteer version 4.0 level
   Current limitations:
   - level parameter does not default to the Artisteer options for block heading levels.  
   
   Notes: The div structure for Artisteer 4.0 blocks is simpler than it was with Artisteer 3.1,
   which is simpler than it was with previous versions.
   - Artisteer 4.0 uses the "clearfix" class to replace div class=cleared
     
*/


/**
 * Syntax [bw_block class="classes" title="title text" framed="y/n,t/f,1/0"]
 * Future use parameters are
 *  prefix="art-"
 *  level="h3" - heading level for the block title
 */
function bw_block_40( $atts=null ) { 
  //bw_trace( $atts, __FUNCTION__, __LINE__, __FILE__, "atts" );
  $class = bw_array_get( $atts, "class", null );
  $title = bw_array_get( $atts, "title", null );
  $prefix = bw_array_get( $atts, "prefix", "art-" );
  $level = bw_array_get( $atts, "level", "div" );
  /* We can't pass the prefix or heading level yet */   
  /* the block is enclosed in a div which can be used to control the width and depth 
     We save the div_class just in case it's needed in the bw_eblock() processing
     Actually, this is a bit daft. Since it is possible to nest blocks within blocks
     the saved value will be that of the most recently nested blocks. So if we wanted to be able to do something with 
     it we would need to manage a stack.
     
  */ 
  global $div_class;
  $div_class = $class;
  sdiv( $class ); 
  sartblock_40( $title );
  return( bw_ret());
}

/**
 * Syntax for [bw_eblock] 
 * There are no parameters. We use this shortcode to end a block
 * Using this technique means we can nest blocks within blocks.
 *  
*/ 
function bw_eblock_40( $atts=null ) {
  eartblock_40();
  return( bw_ret());
}

/**
 * Create a block header if the title text is specified
 *  
 * Notes: link and icon parameters not yet supported.  
 

  <div class="art-blockheader">
    <div class="t">Artisteer 4 &#8211; block
    </div>
  </div>
 */
function artblockheader_40( $title=NULL, $link=NULL, $icon=NULL ) {
  if ( $title ) {  
    sdiv( "art-blockheader");
    sdiv( "t" );
    e( $title );
    ediv();
    ediv();
  }
} 

/**
 * Start an Artisteer 4.0 style block
 *
 * @param string $title - the block title
 *
 */
function sartblock_40( $title=NULL ) {
  sdiv( "art-block widget clearfix" );
    artblockheader_40( $title );
    sdiv( "art-blockcontent" );
}

/**
 * end an Artisteer style block
 * Note: Not sure if the cleared blocks are actually needed
 */
function eartblock_40( $contentFunc = NULL, $framed=TRUE ) {
   ediv( "art-blockcontent" ); 
   ediv( "art-block" ); // widget clearfix 
   global $div_class;
   ediv( $div_class );
}  


/*  Examples extracted from an actual page. 

Meta block - with most of the list items removed

<div  id="meta-2" class="art-block widget widget_meta  clearfix">
  <div class="art-blockheader">
    <div class="t">Meta</div>
  </div>
  <div class="art-blockcontent">
  
    <ul>
    <li>Dummy meta</li>
    </ul>
    
  </div>
</div>


This is a widget with "block" style

<div  id="text-55" class="art-block widget widget_text  clearfix">
  <div class="art-blockheader">
    <div class="t">Artisteer 4 &#8211; block
    </div>
  </div>
  <div class="art-blockcontent">
  
    <div class="textwidget">Content - block
    </div>
    
  </div>
</div>


This is a widget with "post" style
	
<article id="text-56"  class="art-post art-article  widget widget_text">
  <div class="art-postheader">Artisteer 4 &#8211; post
  </div>
  <div class="art-postcontent clearfix">
  
    <div class="textwidget">Content - post
    </div>
    
  </div>
</article>


This is a widget with "simple text" style


<div class="art-widget widget widget_text" id="text-57" >
  <div class="art-widget-title">Artisteer 4 &#8211; simple text
  </div>
  
  <div class="art-widget-content">
  
    <div class="textwidget">Content - simple text
    </div>
    
  </div>
</div>


*/
