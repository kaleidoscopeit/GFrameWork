<?php
class pack_vpaned
{
  public $req_attribs = array(
    'style',
    'class',
    'handle',
    'vsize'
  );

  function __define(&$_)
  {
  }

  function __flush(&$_)
  {
    /* builds syles */
    $style  = (isset($this->style) ? $this->style : '');
    $boxing = (isset($this->boxing) ? $this->boxing : '');

    $this->attributes['class'] = $_->ROOT->boxing($boxing)
                               . $_->ROOT->style_registry_add($style)
                               . $this->class;

    /* builds code */
    $_->buffer[] = '<div wid="0171" '
                 . $_->ROOT->format_html_attributes($this)
                 . '>';

    gfwk_flush_children($this);

    $_->buffer[] = '</div>';
  }

}
?>
