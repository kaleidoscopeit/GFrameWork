<?php
class data_jtablecell
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
    $class     = (isset($this->class) ? $this->class : '');
    $style     = (isset($this->style) ? $this->style : '');

    $style = "display:none;" . $style;

    $css_style  = $_->ROOT->style_registry_add($style)
                . $class;

    if($css_style!="") $css_style = 'class="w0311 '.$css_style.'" ';

    /* builds code */
    $_->buffer[]= '<div wid="0311" '
                . $_->ROOT->format_html_attributes($this)
                . $css_style . '> ';

    gfwk_flush_children($this);

    $_->buffer[] = '</div>';
  }

}
?>
