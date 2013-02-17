<?php
class form_hidden
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

    $_->buffer .= '<input name="'.$this->id.'" id="'.$this->id.'" '.
                  'wid="0220" type="hidden" '.
                  ($this->value ? 'value="'.$this->value.'" ' : '').'>';
  }
}
?>