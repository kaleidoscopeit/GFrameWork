<?php
/* The develop of this class is suspended (2010-07-07) */ 
class base_icon
{

  public $req_attribs = array(
    'style',
    'class'
  );
  
  function __define(&$_)
  {

  }
  
  function __flush(&$_)
  {
    /* builds code */
    $_->buffer[] = '<div wid="0040" style="' . $this->style . ";"
                 . $_->ROOT->boxing
                   ($this->boxing, $this->size.'px', $this->size.'px') . '" '
                 . $_->ROOT->format_html_attributes($this)
                 . '/>';
  }  
}
?>