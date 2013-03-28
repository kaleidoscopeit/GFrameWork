<?php
class pack_iview
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
  
    $onload = $this->onload;
  
    /* Enable a reference to te parent View in the contained iframe View
       NOTE : parentView became available only after the onload event in 
       the contained document */       
    if($this->enableparent){
      $onload = 'this.contentDocument.parentView=window;'.$onload;
     }
    
    if($onload)
      $onload = 'onload="'.$onload.'" ';

    /* builds syles */  
    $style        = $_->ROOT->boxing($this->boxing).$this->style;
    $css_style    = 'class="w0150 '.$_->ROOT->style_registry_add($style).
                    $this->class.'" ';


    /* builds code */
    $_->buffer[] = '<div wid="0150" id="' . $this->id . '" ' . $css_style
                 . ($this->view ? 'view="'.$this->view.'" ' : '')
                 . ($this->onload ? 'onload="'.$this->onload.'" ' : '')
                 . ($this->normal_class?'tcn="'.$this->normal_class.'" ' : '')
                 . ($this->in_class ? 'tci="'.$this->in_class.'" ' : '')
                 . ($this->out_class ? 'tco="'.$this->out_class.'" ' : '')
                 . ($this->trans_class ? 'tct="'.$this->trans_class.'" ' : '')
                 . $_->ROOT->format_html_events($this).'>';
    //$_->buffer[] = '<iframe></iframe>';
    //$_->buffer[] = '<iframe></iframe>';
    //$_->buffer[] = '<iframe></iframe>';
    $_->buffer[] = '</div>'; 
  } 
}
?>