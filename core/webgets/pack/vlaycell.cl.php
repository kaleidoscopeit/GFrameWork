<?php
class pack_vlaycell
{
  public $req_attribs = array(
    'style',
    'class',
    'height'
  );
    
  function __define(&$_)
  {
  }

  function __preflush(&$_){
    /* flow control server event */
    if(isset($this->onflush))eval($this->onflush);
  }
    
  function __flush(&$_)
  {
    /* no paint switch */    
    if (isset($this->nopaint)) return;

    /* builds syles */
    $style = 'height:' . $this->height . ';';
    if(isset($this->within))
      $style .= 'padding-bottom:' . $this->within
              . 'px;margin-bottom:-' . $this->within . 'px;';
              
    else if(isset($this->style)) $style .= $this->style;    
    $css_style                = $_->ROOT->style_registry_add($style).' ';

    /* builds code */
    $_->buffer[] = '<div wid="0121" '
                 . 'class="w0121 ' . $css_style
                 . (isset($this->within) ? '' : 
                    (isset($this->class) ? $this->class : '')) . '" '
                 . $_->ROOT->format_html_attributes($this)
                 . '>';

    if(isset($this->within)) 
      $_->buffer[] = '<div class="w0122 ' . $this->class . '" '
                   . (isset($this->style) ? 'style="'.$this->style.'" ' : '')
                   . '>';

    gfwk_flush_children($this);    

    if(isset($this->within))
      $_->buffer[] = '</div>';
    
    $_->buffer[] ='</div>';

  }
  
}
?>