<?php //

/**
 * Deprecated functions from bobblink.inc 
 *
 */

function sa()
{
  bw_echo( "<a" );
}

function ag()
{
  bw_echo( ">" );
}

function ea()
{
  bw_echo( '</a>' );
}

function ahref( $href )
{
  bw_echo( ' href="'.$href.'"');
}



/* Here the class is associated to a span and not to the anchor 
   the link is followed by a short description.
   This was first used in the 404 error page
*/  
if ( !function_exists( "lialink" ) ) {
function lialink( $class=NULL, $url, $desc, $short )
{
  stag( "li", NULL, NULL );
  span( $class );
  alink( NULL, $url, $short );
  epan();
  if ( $desc <> NULL )
  {
    e( "&nbsp;-&nbsp;" );
    e( $desc );
  }  
  etag( "li" );
}
}


function aid( $id=NULL ) { 
  return( kv( "id", $id ));
}  

function aclass( $class=NULL ) {
  return( kv( "class", $class ) );
}

function aalt( $alt = NULL ) { 
  return( kv( "alt", $alt ) );
}


function atext( $linktori ) {
   e( $linktori );
} 



  
/* alink used to consist of this code 

  sa();
  aclass( $class );
  aid( $id );
  ahref( $url );
  if ( is_null( $alt ) )
     $alt = $linktori;
  // Is alt= allowed with XHTML Strict 1.0?    
  // aalt( $alt );
  atitle( $alt );
  e( $extra );
  ag();
  atext( $linktori );
  ea();
*/  

