<?php
class pack_vlayout
{
  
  function __construct(&$_, $attrs)
  {
    /* imports properties */
    register_attributes($this, $attrs, array(
      'style','class', 'naked'));
    
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
    $css_style =  'class="w0120 '.
                  $_->ROOT->boxing($this->boxing).
                  $_->ROOT->style_registry_add($this->style).
                  $this->class.'" ';

    /* children size definining */
    foreach ((array) @$this->childs as $key => $child)
      if (get_class($child)=='pack_vlaycell') {
        if(method_exists($child, '__preflush')) $child->__preflush($_);
        if(!isset($child->nopaint)) {
          if(isset($child->height)) $fixed_height += 
            str_replace('px','',$child->height);
        
          else $float_childs[] = $key;
        }        
      }


    if(count($float_childs) > 0) {
      $float_div = 100/count($float_childs);
      
      if($fixed_height != 0)
        $within = $fixed_height/count($float_childs);
        
      else
        $within = false;
      
      foreach ($float_childs as $key){
        $this->childs[$key]->height = $float_div.'%';
        $this->childs[$key]->within = $within;
        $fixed_height += $this->childs[$key]->minheight;
      }      
    }

    /* builds code */
    if(!$this->naked)
      $_->buffer[] = '<div wid="0120" '
                   . 'style="min-height:'.$fixed_height.'px" '
                   . $css_style
                   . $_->ROOT->format_html_events($this)
                   . $_->ROOT->format_html_attributes($this)
                   . '>';

    /* flushes children */    
    foreach ((array) @$this->childs as  $child)
      if (get_class($child)=='pack_vlaycell') $child->__flush($_);
      
    if(!$this->naked)
      $_->buffer[] = '</div>';
  }  
  
}
?>