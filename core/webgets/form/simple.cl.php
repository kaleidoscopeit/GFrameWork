<?php
class form_simple
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
    
    $_->buffer[] = '<form method="post" id="'.$this->id.'" wid="0200" '
                 . 'name="'.$this->id.'" enctype="multipart/form-data" '
                 . ($this->action ? 'action="'.$this->action.'" ' : '')
                 . ($this->onsubmit ? 'onsubmit="'.$this->onsubmit.'" ' : '')
                 . '>';
    
    foreach ((array) @$this->childs as  $child) $child->__flush($_);

    $_->buffer[] = '</form>';
  }
}
?>