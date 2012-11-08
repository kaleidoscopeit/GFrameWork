<?php
class form_text
{  
  function __construct(&$_, $attrs)
  {
    /* imports properties */
    foreach ($attrs as $key=>$value) $this->$key=$value;
    
    /* flow control server event */
    eval($this->ondefine);
   }
  
  function __flush(&$_ )
  {
    /* flow control server event */
    eval($this->onflush);

    /* no paint switch */    
    if ($this->nopaint) return;

    /* builds syles */
    $wclass           = 'class="w0230 '.$_->ROOT->boxing($this->boxing).'" ';
    $css_style        = 'class="'.$_->ROOT->style_registry_add
                        ('resize: none;'.$this->style).
                        $this->class.'" ';

    /* builds code */
    $_->buffer .= '<div wid="0230" '.$wclass.'>'.
                  '<textarea name="'.$this->id.'" id="'.$this->id.'" '.
                  $css_style.$_->ROOT->format_html_events($this).
                  ($this->disabled ? 'disabled="true" ' : '').                  
                  ($this->tip ? 'title="'.$this->tip.'" ' : '').
                  '>'.$this->value.'</textarea>'.
                  '</div>';
  }
}
?>