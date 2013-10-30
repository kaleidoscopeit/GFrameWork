<?php
class base_subview
{  
  public $req_attribs = array(
    'style',
    'class'
  );
  
  function __define(&$_)
  {
  }

  function __flush(&$_)
  {
    /* builds syles */
    $css_style = $_->ROOT->boxing($this->boxing)
               . $_->ROOT->style_registry_add($this->style)
               . $this->class;

    if($css_style!="") $css_style = 'class="'.$css_style.'" ';

    /* builds code */
    $_->buffer[] = '<div wid="0070" '
                 . $_->ROOT->format_html_attributes($this)
                 . $css_style . '> ';

    /* flushes children */
    gfwk_flush_children($this);

    $_->buffer[] = '</div>';
  }  

}
?>