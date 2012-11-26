<?php
class pack_iframe {
  
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
    
    $onload = $this->onload;
    
    /* Enable a reference to te parent View in the contained iframe View
       NOTE : parentView became available only after the onload event in 
       the contained document */       
    if($this->enableparent)
      $onload = 'this.contentWindow.parentView=window;'.$onload;
    
    if($onload)
      $onload = 'onload="'.$onload.'" ';

    /* builds syles */
    $style         =  $_->ROOT->boxing($this->boxing).$this->style;
    $css_style     =  'class="w0140 '.$_->ROOT->style_registry_add($style).
                      $this->class.'" ';

    /* builds code */
    $_->buffer .= '<div wid="0140" '.$css_style.'>'.
                  '<iframe id="'.$this->id.'" src="'.$this->src.'" '.
                  $onload.$_->ROOT->format_html_events($this).'></iframe>'.
                  '</div>'; 
  }
  
}
?>