<?php
class pack_hlaycell
{
  public $req_attribs = array(
    'style',
    'class',
    'width'
  );
    
  function __define(&$_)
  {
  }

  function __preflush(&$_){
    /* flow control server event */
    eval($this->onflush);
  }
  
  function __flush(&$_)
  {
    /* no paint switch */    
    if ($this->nopaint) return;

    /* builds syles */
    $style = 'width:' . $this->width.';';
    if(isset($this->within)) 
         $style .= 'padding-right:' . $this->within
                . 'px;margin-right:-' . $this->within . 'px;';
    else $style .= $this->style;
    
    $css_style   = $_->ROOT->style_registry_add($style) . ' ';

    /* builds code */
    $_->buffer[] = '<div wid="0111" '
                 . 'class="w0111 ' . $css_style
                 . (isset($this->within) ? '' : $this->class) . '" '
                 . $_->ROOT->format_html_attributes($this)
                 . '>';

    if(isset($this->within)) 
      $_->buffer[] = '<div class="w0112 ' . $this->class . '" '
                   . (isset($this->style) ? 'style="' . $this->style . '" ' : '')
                   . '>';

    gfwk_flush_children($this);

    if(isset($this->within))
      $_->buffer[] = '</div>';
    
    $_->buffer[] ='</div>';

  }  
  
}
?>