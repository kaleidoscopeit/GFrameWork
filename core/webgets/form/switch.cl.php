<?php
class form_switch
{  
  function __construct(&$_, $attrs)
  {
    /* imports properties */
    foreach ($attrs as $key=>$value) $this->$key=$value;
    
    /* flow control server event */
    eval($this->ondefine);
  }
  
  function __flush (&$_)
  {
    /* flow control server event */
    eval($this->onflush);

    /* no paint switch */    
    if ($this->nopaint) return;

    /* builds syles */  
    $css_style = 'class="w0290 '.$_->ROOT->boxing($this->boxing).
                 $_->ROOT->style_registry_add($this->style).$this->class.'" ';
                 
    /* builds code */
    $_->buffer[] = '<div id="' . $this->id . '" wid="0290" '
                 . ($this->disabled ? 'disabled="disabled" ' : '')
                 . $css_style . '>';
    $_->buffer[] = '<input name="'.$this->id.'" type="text" value=""></input>';
    $_->buffer[] = '<span>';
    $_->buffer[] = '<span>ON</span>';
    $_->buffer[] = '<span>OFF</span>';
    $_->buffer[] = '</span>';
    $_->buffer[] = '<div>';
    $_->buffer[] = '<button type="button" disabled></button>';
    $_->buffer[] = '</div>';
    $_->buffer[] = '<div ' . $_->ROOT->format_html_events($this)
                 . ($this->tip ? 'title="' . $this->tip . '" ' : '')
                 . '></div>';
    $_->buffer[] = '</div>';
  }  
}
?>


