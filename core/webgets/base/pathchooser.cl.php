<?php
class base_pathchooser {
  
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
    $style       = $_->ROOT->boxing($this->boxing).$this->style;
    $css_style   = $_->ROOT->style_registry_add($style).$this->class;
    if($css_style) $css_style = 'class="'.$css_style.'" ';

    /* building code */
    $_->buffer .= '<div wid="0060" id="'.$this->id.'" '.$css_style.
                  ($this->path ? 'path="'.$this->path.'" ' : '').
                  $_->ROOT->format_html_events($this).'>'.
                  '<input type="text" name="'.$this->id.'" />'.
                  '<button type="button" ></button>'.
                  '<div></div>'.
                  '<button type="button" style="float:right;"></button>'.      
                  '</div>';
  }
 
}
?>