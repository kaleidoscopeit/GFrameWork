<?php
// The develop of this class is suspended (2010-07-07) 
class base_icon
{
  function __construct(&$_, $attrs)
  {
    /* imports properties */
    foreach ($attrs as $key=>$value) $this->$key=$value;
    /* imports properties */
    register_attributes($this, $attrs, array(
      'style','class'));
    
    /* flow control server event */
    eval($this->ondefine);
   }
  
  function __flush(&$_)
  {
    /* flow control server event */
    eval($this->onflush);

    /* no paint switch */    
    if ($this->nopaint) return;  

    /* builds code */
    $_->buffer[] = '<div wid="0040" style="' . $this->style . ";"
                 . $_->ROOT->boxing
                   ($this->boxing, $this->size.'px', $this->size.'px') . '" '
                 . $_->ROOT->format_html_attributes($this) 
                 . 'theme="' . $this->theme . '"'
                 . '/>';
  }  
}
?>