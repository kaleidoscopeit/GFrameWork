<?php
class pack_modal {
  
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
    $_->buffer[] = '<div class="modalwa" wid="0180" id="' . $this->id . '">';
    $_->buffer[] = '<div class="modalwb gwha" '
                 . 'onclick="this.parentNode.hide()"></div>';
    $_->buffer[] = '<div style="overflow:auto;' . $this->style . ";"
                 . $_->webgets['root']->boxing( $this->boxing ) . ';" '
                 . $_->webgets['root']->format_html_events($this, Array('mouse'))
                 . 'class = "gbsz ' . $this->class . '" '
                 . ($this->root ? 'root="'.$this->root.'" ' : '')
                 . '>';

    /* flushes children */
    foreach ((array) @$this->childs as  $child) $child->__flush($_);

    $_->buffer[] = '</div>';
    $_->buffer[] = '</div>';
  }  
}
?>