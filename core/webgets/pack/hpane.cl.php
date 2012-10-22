<?php
class pack_hpane {
  
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
    $_->buffer .= '<div type="pack:hpane" id="'.$this->id.'" style="'.
                  $this->style.';" '.
                  ($this->minsize ? 'misz="'.$this->minsize.'" ' : '').
                  ($this->maxsize ? 'masz="'.$this->maxsize.'" ' : '').
                  ($this->locked ?  'lkd="' .$this->locked. '" ' : '').
                  '>';

    /* flushes children */
    foreach ((array) @$this->childs as  $child) $child->__flush($_);

    $_->buffer .= '</div>';
  }
  
}
?>