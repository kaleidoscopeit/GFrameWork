<?php
// The develop of this class is suspended (2010-07-07) 
class base_icon
{
  function __construct(&$_, $attrs)
  {
    /* imports properties */
    foreach ($attrs as $key=>$value) $this->$key=$value;

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
    $_->buffer .=  '<div type="base:icon" id="'.$this->id.'" '.
                   'style="'.$this->style.";".
                   $_->ROOT->boxing
                   ($this->boxing, $this->size.'px', $this->size.'px').'" '.
                   'theme="'.$this->theme.'"'.
                   '/>';
  }  
}
?>