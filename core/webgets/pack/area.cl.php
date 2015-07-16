<?php
class pack_area
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
    $class = (isset($this->class) ? $this->class : '');    

    $this->attributes['class'] = $_->ROOT->boxing($boxing)
                               . $_->ROOT->style_registry_add($style)
                               . $class;

    /* builds code */
    $_->buffer[] = '<div wid="0100" '
                 . $_->ROOT->format_html_attributes($this)
                 . '> ';

    gfwk_flush_children($this);

    $_->buffer[] = '</div>';
  }

}
?>
