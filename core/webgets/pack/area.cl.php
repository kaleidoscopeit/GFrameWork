<?php
class pack_area
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
    $css_style = $_->ROOT->boxing($this->boxing).
                 $_->ROOT->style_registry_add($this->style).
                 $this->class;
                 
    if($css_style!="") $css_style = 'class="'.$css_style.'" ';

    /* builds code */
    $_->buffer .=  '<div id="'.$this->id.'" '.$css_style.
                   $_->ROOT->format_html_events($this, array('mouse')).
                   '> ';

    /* flushes children */
    foreach ((array) @$this->childs as  $child) $child->__flush($_);

    $_->buffer .= '</div>';
  }  

}
?>