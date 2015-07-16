<?php
class data_jtable
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
    $style  = (isset($this->style) ? $this->style : '');
    $boxing = (isset($this->boxing) ? $this->boxing : '');
    $class     = (isset($this->class) ? $this->class : '');

    $css_style  = $_->ROOT->boxing($boxing)
                . $_->ROOT->style_registry_add($style)
                . $class;

    if($css_style!="") $css_style = 'class="w0310 ' . $css_style . '" ';

    /* builds code */
    $_->buffer[] = '<div wid="0310" '
                 . $_->ROOT->format_html_attributes($this) . ' '
                 . $css_style.'>';

    $_->buffer[] = '</div>';

    $_->buffer[] = '<div>';

    gfwk_flush_children($this, 'data_jtablecell');

    $_->buffer[] = '</div>';


  }

}
?>
