<?php
class form_button
{
  public $req_attribs = array(
    'style',
    'class',
    'value'
  );
  
    
  function __define(&$_)
  {
    if(!isset($this->attributes['type'])) $this->attributes['type'] = 'button';

  }
  
  function __flush(&$_)
  {
    if(isset($this->attributes['id']))
      $this->attributes['name'] = $this->attributes['id'];
    
    /* builds syles */    
    $w_class   = 'class="w0250 '.$_->ROOT->boxing($this->boxing).'" ';

    /* builds syles */
    $this->attributes['class'] = $_->ROOT->style_registry_add($this->style)
                               . $this->class;
                               
    if($this->attributes['class'] == '') unset($this->attributes['class']);
                               
    /* builds code */	
    $_->buffer[] = '<div ' . $w_class . '>';	    
    $_->buffer[] = '<button wid="0250" '
                 . $_->ROOT->format_html_attributes($this)
                 . '>';

    /* flushes children */
    if (isset($this->childs)) {
      $_->buffer[] = '<div>';
      gfwk_flush_children($this);
      $_->buffer[] = '</div>';        
    } else {
      $_->buffer[] = $this->value;
    }
    
    $_->buffer[] = '</button>';
    $_->buffer[] = '</div>';
  }  
}
?>