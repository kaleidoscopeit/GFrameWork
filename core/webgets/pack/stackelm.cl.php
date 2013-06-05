<?php
class pack_stackelm {
  
  function __construct (&$_, $attrs)
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
    $_->buffer[] = '<div wbg style="'
                 . $this->style . ($this->preset ? '' : 'display:none;')
                 . '" wid="0131" '
                 . ($this->onshow ? 'onshow="'.$this->onshow.'" ' : '')
                 . '>';

    /* flushes children */
    if (!$this->childs)
      $_->buffer[] = "Stack element number " . $this->index;
    else
      foreach ((array) @$this->childs as  $child) $child->__flush($_);

    $_->buffer[] = '</div>';
  }  
}
?>