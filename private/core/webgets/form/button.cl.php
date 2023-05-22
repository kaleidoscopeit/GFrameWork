<?php
class form_button
{
  public $req_attribs = array(
    'style',
    'class',
    'value',
    'caption'
  );


  function __define(&$_)
  {
    if(!isset($this->attributes['type'])) $this->attributes['type'] = 'button';

  }

  function __flush(&$_)
  {
    if(isset($this->attributes['id']))
      $this->attributes['name'] = $this->attributes['id'];

    /* builds syles */
    $style   = (isset($this->style) ? $this->style : '');
    $boxing  = (isset($this->boxing) ? $this->boxing : '');
    $class   = (isset($this->class) ? $this->class : '');

    $w_class = 'class="w0250 '
             . $_->ROOT->boxing($boxing) . '" ';

    $b_class = $_->ROOT->style_registry_add($style)
             . $class;

    if($b_class != '') $b_class = 'class="' . $b_class . '" ';

    /* builds code */
    $_->buffer[] = '<div ' . $w_class . '>';
    $_->buffer[] = '<button wid="0250" '
                 . (isset($this->value) ? 'value="' . $this->value . '" ' : "")
                 . $b_class
                 . $_->ROOT->format_html_attributes($this)
                 . '>';

    /* flushes children */
    if (isset($this->childs)) {
      $_->buffer[] = '<div>';
      gfwk_flush_children($this);
      $_->buffer[] = '</div>';
    } else {
      $_->buffer[] = (isset($this->caption) ? $this->caption : $this->value);
    }

    $_->buffer[] = '</button>';
    $_->buffer[] = '</div>';
  }
}
?>
