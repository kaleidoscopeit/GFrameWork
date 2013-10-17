<?php
class pack_vlayout
{
  public $req_attribs = array(
    'style',
    'class',
    'naked'
  );
  
  function __define(&$_)
  {
  }
  
  function __flush(&$_)
  {
    /* children size definining */
    if(!isset($fixed_height)) $fixed_height = 0;
    
    foreach ((array) @$this->childs as $key => $child)
      if (get_class($child)=='pack_vlaycell') {
        if(method_exists($child, '__preflush')) $child->__preflush($_);
        if(!isset($child->nopaint)) {
          if(isset($child->height)) $fixed_height += 
            str_replace('px','',$child->height);
        
          else $float_childs[] = $key;
        }        
      }

    if(isset($float_childs))
      if(count($float_childs) > 0) {
        $float_div = 100/count($float_childs);
        
        if($fixed_height != 0)
          $within = $fixed_height/count($float_childs);
          
        else
          $within = false;
        
        foreach ($float_childs as $key){
          $this->childs[$key]->height = $float_div.'%';
          $this->childs[$key]->within = $within;
          if(isset($this->childs[$key]->minheight))
            $fixed_height += $this->childs[$key]->minheight;
        }      
      }

    /* builds syles */
    $this->attributes['class'] =
        $_->ROOT->boxing($this->boxing)
      . $_->ROOT->style_registry_add(
        'min-height:' . $fixed_height . 'px;'
        . $this->style)
      . $this->class;
                               
    /* builds code */
    if(!isset($this->naked))
      $_->buffer[] = '<div wid="0120" '
                   . $_->ROOT->format_html_attributes($this)
                   . '>';

    /* flushes children */    
    foreach ((array) @$this->childs as  $child)
      if (get_class($child)=='pack_vlaycell') $child->__flush($_);
      
    if(!isset($this->naked))
      $_->buffer[] = '</div>';
  }  
}
?>