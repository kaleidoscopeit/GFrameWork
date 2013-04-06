<?php
class pack_vlaycell {
  
  function __construct(&$_, $attrs)
  {
    /* imports properties */
    register_attributes($this, $attrs, array(
      'style','class','height'));
    
    /* flow control server event */
    eval($this->ondefine);
   }

  function __preflush(&$_){
    /* flow control server event */
    eval($this->onflush);
  }
    
  function __flush(&$_)
  {
    /* no paint switch */    
    if ($this->nopaint) return;

    /* builds syles */
    $style                    = 'height:' . $this->height . ';';
    if($this->within) $style .= 'padding-bottom:' . $this->within
                              . 'px;margin-bottom:-' . $this->within . 'px;';
    else              $style .= $this->style;    
    $css_style                = $_->ROOT->style_registry_add($style).' ';

    /* builds code */
    $_->buffer[] = '<div wid="0121" '
                 . 'class="w0121 ' . $css_style
                 . ($this->within ? '' : $this->class) . '" '
                 . $_->ROOT->format_html_events($this)
                 . $_->ROOT->format_html_attributes($this)
                 . '>';

    if($this->within) 
      $_->buffer[] = '<div class="w0122 ' . $this->class . '" '
                   . ($this->style ? 'style="'.$this->style.'" ' : '')
                   . '>';

    /* flushes children */
    foreach ((array) @$this->childs as  $child) $child->__flush($_);

    if($this->within)
      $_->buffer[] = '</div>';
    
    $_->buffer[] ='</div>';

  }
  
}
?>