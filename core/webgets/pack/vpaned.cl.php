<?php
class pack_vpaned {
  
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
    $_->buffer .= '<div style="'.$this->style.";".
                  $_->ROOT->boxing($this->boxing ).'" '.
                  'type="pack:vpaned" id="'.$this->id.'" '.
                  ($this->handle ? 'handle="'.$this->handle.'" ' : '').
                  ($this->vsize ? 'vsize="'.$this->vsize.'" ' : '').
                  '>';

    /* flushes children */
    foreach ((array) @$this->childs as  $child) $child->__flush($_);

    $_->buffer .= '</div>';
  }
  
}
?>