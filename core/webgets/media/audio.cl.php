<?php
class media_audio
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

    $_->buffer .= '<audio id="'.$this->id.'" '.
                  ($this->src ? 'src="'.$this->src.'" ' : '').
                  '></audio>';
  }  
}
?>