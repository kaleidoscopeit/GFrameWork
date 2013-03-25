<?php
class form_password {
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

    $wclass = 'class="w0240 '.$_->ROOT->boxing($this->boxing).'" ';
    $css_style = $_->ROOT->style_registry_add($this->style).$this->class;
    if($css_style!="") $css_style = 'class="'.$css_style.'" ';
    
    $_->buffer[] = '<div wid="0240" wbg ' . $wclass . '>';
    $_->buffer[] = '<input name="' . $this->id . '" id="' . $this->id . '" '
                 . 'type="password" ' . $css_style
                 . ($this->disabled ? ' disabled ' : '')
                 . ($this->readonly ? ' readonly ' : '')
                 . ($this->tip ? ' title="'.$this->tip.'" ' : '')
                 . ($this->value ? ' value="'.$this->value.'" ' : '')
                 . ($this->tabindex ? ' tabindex="'.$this->tabindex.'" ' : '')
                 . $_->ROOT->format_html_events($this) . '>';
    $_->buffer[] = '</div>';
  }
}
?>