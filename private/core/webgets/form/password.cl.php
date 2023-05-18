<?php
class form_password
{
  public $req_attribs = array(
    'style',
    'class'
  );

  function __define(&$_)
  {
    if(isset($this->attributes['id']))
      $this->attributes['name'] = $this->attributes['id'];
  }

  function __flush(&$_)
  {

    /* builds syles */
    $style  = (isset($this->style) ? $this->style : '');
    $wstyle = (isset($this->wstyle) ? $this->wstyle : '');
    $boxing = (isset($this->boxing) ? $this->boxing : '');
    $class  = (isset($this->class) ? $this->class : '');
    $wclass = (isset($this->wclass) ? $this->wclass : '');

    $w_class  = 'class="w0240 '
              . $_->ROOT->boxing($boxing)
              . $_->ROOT->style_registry_add($wstyle)
              . $wclass
              . '" ';

    $css_style  = $_->ROOT->style_registry_add($style)
                . $class;

    if($css_style!="") $css_style = 'class="' . $css_style . '" ';

    /* builds code */
    $_->buffer[] = '<div ' . $w_class . '>';
    $_->buffer[] = '<input type="password" wid="0240" '
                 . $_->ROOT->format_html_attributes($this)
                 . $css_style . '>';

    $_->buffer[] = '</div>';
  }
}
?>
