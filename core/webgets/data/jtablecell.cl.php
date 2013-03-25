<?php
class data_jtablecell
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

    /* builds syles */
   $css_style = $_->ROOT->style_registry_add("display:none;".$this->style).
                $this->class;
                 
    if($css_style!="") $css_style = 'class="w0311 '.$css_style.'" ';

    /* builds code */
    $_->buffer[]= '<div id="' . $this->id . '" wid="0311" '
                . $_->ROOT->format_html_events($this)
                . 'show_if="' . $this->show_if . '" '
                . $css_style . '> ';

    /* flushes children */
    foreach ((array) @$this->childs as  $child) $child->__flush($_);
        
    $_->buffer[] = '</div>';
  }  

}
?>