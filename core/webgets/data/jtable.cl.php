<?php
class data_jtable
{  
  function __construct(&$_, $attrs)
  {
    /* imports properties */
    register_attributes($this, $attrs, array(
      'style','class'));
    
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
                 
    if($css_style!="") $css_style = 'class="w0310 '.$css_style.'" ';

    /* builds code */
    $_->buffer .= '<div id="'.$this->id.'" wid="0310" '
                . $_->ROOT->format_html_attributes($this) . ' ' .
                  $css_style.'></div><div>';

    /* flushes children */
    foreach ((array) @$this->childs as $child)
      if (get_class($child) == 'data_jtablecell')$child->__flush($_);
        
    $_->buffer .= '</div>';
  }  

}
?>