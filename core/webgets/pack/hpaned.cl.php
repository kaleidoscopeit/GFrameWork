<?php
class pack_hpaned
{
  public $req_attribs = array(
    'style',
    'class',
    'handle',
    'hsize'
  );

  function __define(&$_)
  {
  }

  function __flush(&$_)
  {
    /* builds syles */
    $style  = (isset($this->style) ? $this->style : '');
    $boxing = (isset($this->boxing) ? $this->boxing : '');

    $css_style = 'class="'
               . $_->ROOT->boxing($boxing)
               . $_->ROOT->style_registry_add($style)
               . $this->class .'" ';

    /* builds code */
    $_->buffer[] = '<div ' . $css_style . '">';

    $_->buffer[] = '<div wid="0161" '
                 . $_->ROOT->format_html_attributes($this)
                 . '>';

    gfwk_flush_children($this);

    $_->buffer[] = '</div></div>';
  }
}
?>
