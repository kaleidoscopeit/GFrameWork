<?php
class base_pathchooser
{
  public $req_attribs = array(
    'style',
    'class',
    'field',
    'field_format'
  );

  function __define(&$_)
  {
  }

  function __flush(&$_)
  {
    /* builds syles */
    $style  = (isset($this->style) ? $this->style : '');
    $boxing = (isset($this->boxing) ? $this->boxing : '');

    $css_style  = 'class="w0060 '
                . $_->ROOT->boxing($boxing)
                . $_->ROOT->style_registry_add($style)
                . $this->class
                . '" ';

    /* building code */
    $_->buffer[] = '<div wid="0060" '
                 . $_->ROOT->format_html_attributes($this)
                 . $css_style . '> ';
    $_->buffer[] = '<input type="text" name="'.$this->attributes['id'].'" />';
    $_->buffer[] = '<button type="button" >';
    $_->buffer[] = '</button>';
    $_->buffer[] = '<div>';
    $_->buffer[] = '</div>';
    $_->buffer[] = '<button type="button" style="float:right;">';
    $_->buffer[] = '</button>';
    $_->buffer[] = '</div>';
  }

}
?>
