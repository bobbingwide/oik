<?php // (C) Copyright Bobbing Wide 2009, 2010
// bwlink.inc

function bw_old() 
{
      $bw_class_prefix = '';
      
      
      $bw  = '<em class="' . $bw_class_prefix.'B1">B</em>';
      $bw .= '<em class="' . $bw_class_prefix.'o">o</em>'; 
      $bw .= '<em class="' . $bw_class_prefix.'b2">b</em>';
      $bw .= '<em class="' . $bw_class_prefix.'b3">b</em>';
      $bw .= '<em class="' . $bw_class_prefix.'i1">i</em>';
      $bw .= '<em class="' . $bw_class_prefix.'n">n</em>';
      $bw .= '<em class="' . $bw_class_prefix.'g">g</em>';
      $bw .= '<em class="' . $bw_class_prefix.'space">&nbsp;</em>';
      $bw .= '<em class="' . $bw_class_prefix.'W">W</em>';
      $bw .= '<em class="' . $bw_class_prefix.'i2">i</em>';
      $bw .= '<em class="' . $bw_class_prefix.'d">d</em>';
      $bw .= '<em class="' . $bw_class_prefix.'e">e</em>';
      
      $bw_class_prefix = 'none';       
      return $bw;
}      

function bwlink()
{
   $link = '<a class="bwlink" ';
   // Cannot use alt in Strict XHTML 1.0
   // $link.= 'alt="Website design and website development by Bobbing Wide" ';
   $link.= 'title="Website design and website development by Bobbing Wide" ';
   $link.= 'href="http://www.bobbingwide.com">&copy; Copyright ' ;
   $link.= bw();
   $link.= ' 2010</a>';
   echo $link;
}

 
function bwlinks( $suffix, $oneliner )
{
  global $bw;
  $line = '<li><a ';
  $line.= ' alt="Go to BobbingWide' . $suffix . '.com ' . $oneliner . '" ';
  $line.= ' title="Go to BobbingWide' . $suffix . '.com ' . $oneliner . '" ';
  $line.= ' href="http://www.bobbingwide';
  $line.=$suffix;
  $line.='.com/">www.';
  $line.= $bw.$suffix.'.com';
  $line.='</a>&nbsp;...&nbsp;';  
  $line.= $oneliner;

  $line.='</li>';
  echo $line;
}


function bwlink_list()
{      

  $bw = bw();
  bwlinks( "", "to buy web site design and development services" );
  //bwlinks( "webdesign", "for information on web design" );  
  //bwlinks( "webdevelopment", "for information on web development" );
  //bwlinks( "pcsupport", "for PC technical support services" );
  bwlinks( "itconsultant", "for consultancy or freelance work" );
}


