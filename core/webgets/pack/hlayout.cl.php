<?php
class pack_hlayout
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
    $css_style       = $_->ROOT->boxing($this->boxing).
                       $_->ROOT->style_registry_add($this->style).
                       $this->class;
                 
    if($css_style!="") $css_style = 'class="'.$css_style.'" ';

    /* children size definining */
    foreach ((array) @$this->childs as $key => $child)
      if (get_class($child)=='pack_hlaycell') {
        if(method_exists($child, '__preflush')) $child->__preflush($_);
        if(!isset($child->nopaint)) {
          if(isset($child->width)) $fixed_width += 
            str_replace('px','',$child->width);
        
          else $float_childs[] = $key;
        }        
      }
      
    if(count($float_childs) > 0) {
      $float_div = 100/count($float_childs);

      if($fixed_width != 0)
        $within = $fixed_width/count($float_childs);
        
      else
        $within = false;
      
      foreach ($float_childs as $key){
        $this->childs[$key]->width  = $float_div.'%';
        $this->childs[$key]->within = $within;
      }      
    }
    
    /* builds code */
    if(!$this->naked)
      $_->buffer[] = '<div id="' . $this->id . '" wid="0110" '
                   . 'style="min-width:' . $fixed_width . 'px" '
                   . $css_style
                   . $_->ROOT->format_html_events($this) . '>';
                                    
    /* flushes children */
    foreach ((array) @$this->childs as  $child)
      if (get_class($child)=='pack_hlaycell') $child->__flush($_);
      
    if(!$this->naked)
      $_->buffer[] = '</div>';
  }  
}
?>