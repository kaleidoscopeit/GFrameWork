<?php
class form_button {
  
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
    $w_class          = 'class="w0250 '.$_->ROOT->boxing($this->boxing).'" ';
    $css_style        = $_->ROOT->style_registry_add($this->style).
                        $this->class;
                              
    if($css_style!="")  $css_style = 'class="'.$css_style.'" ';
    

    /* builds code */	
    $_->buffer[] = '<div ' . $w_class . '>';	    
    $_->buffer[] = '<button name="' . $this->id . '" id="' . $this->id
                 . '" wid="0250" ' . $_->ROOT->format_html_events($this)
                 . $css_style
                 . ($this->tip ? ' title="'.$this->tip.'" ' : '')  
                 . ($this->type ? ' type="'.$this->type.'" ' : ' type="button" ')
                 . ($this->disabled ? ' disabled="disabled" ' : '')
                 . '>';

    /* flushes children */
    if ($this->childs) {
      $_->buffer[] = '<div>';
      foreach ($this->childs as  $child) $child->__flush($_);
      $_->buffer[] = '</div>';        
    } else {
      $_->buffer[] = $this->value;
    }
    
    $_->buffer[] = '</button>';
    $_->buffer[] = '</div>';
  }  
}
?>