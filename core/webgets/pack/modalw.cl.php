<?php
class pack_modalw {
  
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
    $_->buffer .= '<div class="modalwa" type="pack:modalw" wbg id="'.$this->id.'">'.
                  '<div class="modalwb gwha" onclick="this.parentNode.hide()">'.
                  '</div><div style="overflow:auto;'.$this->style.";".
                  $_->webgets['root']->boxing( $this->boxing ).';" '.
                  $_->webgets['root']->format_html_events( $this, Array ( 'mouse' ) ).
                  'class = "gbsz '.$this->class.'" '.
                  ($this->root ? 'root="'.$this->root.'" ' : '').
                  '>';

    /* flushes children */
    foreach ((array) @$this->childs as  $child) $child->__flush($_);

    $_->buffer .= '</div></div>';
  }  
}
?>