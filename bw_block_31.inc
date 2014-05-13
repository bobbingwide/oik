<?php 
/* bw_block_31.inc - functions for creating blocks with Artisteer version 3.1 level
   Current limitations:
   - code does not support framed=n. eartblock function would have to be able to recognise whether or not framed was used in the previous artblock
     we would have to maintain a stack of the framed parameters to support nested blocks
     ... but first we need to determine whether or not we can handle each of the block styles.
   - level parameter does not default to the Artisteer options for block heading levels.  
   
   Notes: The div structure for Artisteer 3.1 blocks is much simpler than it was.
     
*/


/**
 * Syntax [bw_block class="classes" title="title text" framed="y/n,t/f,1/0"]
 * Future use parameters are
 *  prefix="art-"
 *  level="h3" - heading level for the block title
 */
function bw_block_31( $atts ) { 
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
  sartblock_31( $title, $framed );
  return( bw_ret());

}

/**
 * Syntax for [bw_eblock] 
 * There are no parameters. We use this shortcode to end a block
 * Using this technique means we can nest blocks within blocks.
   
*/ 
function bw_eblock_31( $atts ) {
  eartblock_31();
  return( bw_ret());
}


/**
 * Create a block header if the title text is specified 
 * Notes: link and icon parameters not yet supported.  
 */
function artblockheader_31( $title=NULL, $link=NULL, $icon=NULL ) {
  if ( $title ) {  
    sdiv( "art-bar art-blockheader");
    h3( $title, "t" );
    ediv();
  }
} 


/**
 * draw the frame around the block header
 */
function artblockframe_31() {

  sdiv( "art-box-body art-block-body" );
} 

/**
 * draw the frame around the block content
 */
function artblockcontentframe_31() {
  sdiv( "art-box art-blockcontent" );
} 
 

/**
 * start an Artisteer style block
 


  <div class="art-box art-block widget widget_links" id="linkcat-2">
    <div class="art-box-body art-block-body">
    
      <div class="art-bar art-blockheader">
        <h3 class="t">Blogroll</h3>
      </div>
      
      <div class="art-box art-blockcontent">
        <div class="art-box-body art-blockcontent-body"> 
 */
function sartblock_31( $title=NULL, $framed=TRUE ) {
   sdiv( "art-box art-block widget" );
     //if ($framed )
     {
        artblockframe_31();
     }   
     artblockheader_31( $title );
     //if ( $framed )
      {
       artblockcontentframe_31();
     }  
     sdiv( "art-box-body art-blockcontent-body ");
}


/**
 * end an Artisteer style block
 * Note: Not sure if the cleared blocks are actually needed
 */
function eartblock_31( $contentFunc = NULL, $framed=TRUE ) {

   //if ( !is_null( $contentFunc ))      
   //  $contentFunc();
   //if ( $framed )
   sediv( "cleared" );
   
   ediv(); // art-box-body art-blockcontent-body
   ediv(); // art-box art-blockcontent
   sediv( "cleared" );
   ediv(); // art-box-body art-block-body
   ediv(); // art-box art-block
    global $div_class;
   sediv("cleared");
   ediv( $div_class );
   
}  


/*  Examples extracted from an actual page.

  <div class="art-box art-block widget widget_links" id="linkcat-2">
    <div class="art-box-body art-block-body">
    
      <div class="art-bar art-blockheader">
        <h3 class="t">Blogroll</h3>
      </div>
      
      <div class="art-box art-blockcontent">
        <div class="art-box-body art-blockcontent-body"> 
          // content goes here 
          
          <div class="cleared"></div>
        </div>  
      </div>
      <div class="cleared"></div>
    </div>
  </div>
  


  <div class="art-box art-block widget widget_text" id="text-17">
    <div class="art-box-body art-block-body">
      <div class="art-bar art-blockheader">
        <h3 class="t">a block</h3>
      </div>
    
      <div class="art-box art-blockcontent">
        <div class="art-box-body art-blockcontent-body">
          // <div class="textwidget">this is the content of a block in the lhs</div> 
         
          <div class="cleared"></div>
        </div>
      </div>
    <div class="cleared"></div>
    </div>
  </div>
  
<div class="art-box art-block widget widget_text" id="text-32">
  <div class="art-box-body art-block-body">
    <div class="art-bar art-blockheader">
      <h3 class="t">a block</h3>
    </div>
    <div class="art-box art-blockcontent">
      <div class="art-box-body art-blockcontent-body">			
        <div class="textwidget">this is the content of a block</div> 
        <div class="cleared"></div>
      </div>
    </div>		
    <div class="cleared"></div>
  </div>
</div>
<div class="cleared"></div>
</div>
</div>
        
<div>
<div class="art-box art-block">
  <div class="art-box-body art-block-body">
    <div class="art-bar art-blockheader">
      <h3 class="t">titled</h3>
    </div>
    <div class="art-box art-blockcontent">
      <div class="art-box-body art-blockcontent-body "> 
        a titled block 
        <div class="cleared"></div>
      </div>
    </div>
    <div class="cleared"></div>
</div>
</div>
</div> 



<div class="cleared"> </div>
<br /> 

<div>
<div class="art-box art-block"><div class="art-box-body art-block-body"><div class="art-box art-blockcontent"><div class="art-box-body art-blockcontent-body "> untitled block<div class="cleared"></div></div></div><div class="cleared"></div></div></div></div> 

*/
