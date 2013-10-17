<?php
/*
 * attributes :
 *
 * onshow   : happens when this element will be shown 
 */
 
class pack_stackelm
{ 
  public $req_attribs = array(
    'style',
    'class',
    'preset'
  );
  
  function __define(&$_)
  {
  }

  function __preflush(&$_)
  {
    $this->index = $parent->count;
    if($parent->preset != $this->index) $this->style .= 'display:none;';
    $parent->count++;
  }
  
  function __flush(&$_)
  {
    /* builds syles */
    $this->attributes['class'] = $_->ROOT->style_registry_add($this->style)
                               . $this->class . '" ';
      
    /* builds code */    
    $_->buffer[] = '<div wid="0131" '
                 . $_->ROOT->format_html_attributes($this)
                 . '> ';

    /* flushes children */
    if (!isset($this->childs))
      $_->buffer[] = "Stack element number " . $this->index;
    else
      gfwk_flush_children($this);

    $_->buffer[] = '</div>';
  }  
}
?>