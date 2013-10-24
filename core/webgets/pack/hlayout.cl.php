<?php
class pack_hlayout
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
    /* builds syles */
    @$css_style = $_->ROOT->boxing($this->boxing)
               . $_->ROOT->style_registry_add($this->style)
               . $this->class;
                 
    if($css_style!="") $css_style = 'class="'.$css_style.'" ';

    /* children size definining */
    if(!isset($fixed_width)) $fixed_width = 0;
    
    foreach ((array) @$this->childs as $key => $child)
      if (get_class($child)=='pack_hlaycell') {
        if(method_exists($child, '__preflush')) $child->__preflush($_);
        if(!isset($child->nopaint)) {
          if(isset($child->width)) $fixed_width += 
            str_replace('px','',$child->width);
        
          else $float_childs[] = $key;
        }        
      }
    
    if(isset($float_childs))
      if(count($float_childs) > 0) {
        $float_div = 100/count($float_childs);
  
        if($fixed_width != 0)
          $within = $fixed_width/count($float_childs);
          
        else
          $within = false;
        
        foreach ($float_childs as $key){
          $this->childs[$key]->width  = $float_div.'%';
          $this->childs[$key]->within = $within;
          if(isset($this->childs[$key]->minwidth))
            $fixed_height += $this->childs[$key]->minwidth;
        }      
      }
    
    /* builds code */
    if(!isset($this->naked))
      $_->buffer[] = '<div wid="0110" '
                   . 'style="min-width:' . $fixed_width . 'px" '
                   . $css_style
                   . $_->ROOT->format_html_events($this)
                   . $_->ROOT->format_html_attributes($this)
                   . '>';
                                    
    gfwk_flush_children($this, 'pack_hlaycell');
      
    if(!$this->naked)
      $_->buffer[] = '</div>';
  }  
}
?>