<?php
class data_jtableheader
{
  public $req_attribs = array(
    'style',
    'class',
    'height'
  );

  function __define(&$_)
  {
  }

  function __flush(&$_)
  {
    /* builds syles */
    $style = (isset($this->style) ? $this->style : '')
           . (isset($this->height) ? 'height:' . $this->height : '');

    $css_style  = $_->ROOT->style_registry_add($style)
                . $this->class;

    if($css_style!="") $css_style = 'class="w0312 ' . $css_style . '" ';

    /* builds code */
    $_->buffer[]= '<div wid="0312" '
                . $_->ROOT->format_html_attributes($this)
                . $css_style . '> ';

    gfwk_flush_children($this);

    $_->buffer[] = '</div>';
  }

}
?>
