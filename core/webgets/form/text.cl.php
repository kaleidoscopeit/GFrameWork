<?php
class form_text
{
  public $req_attribs = array(
    'style',
    'class',
    'field',
    'field_format',
    'value'
  );
  
  function __define(&$_)
  {
    if(isset($this->attributes['id']))
      $this->attributes['name'] = $this->attributes['id'];
   }
  
  function __flush(&$_ )
  {
    /* builds syles */    
    $w_class   = 'class="w0230 '
               . $_->ROOT->boxing($this->boxing)
               . '" ';
               
    $css_style = $_->ROOT->style_registry_add('resize: none;' . $this->style)
               . $this->class;

    if($css_style!="") $css_style = 'class="' . $css_style . '" ';

    /* builds code */
    $_->buffer[] = '<div ' . $w_class . '>';
    $_->buffer[] = '<textarea wid="0230" '
                 . $_->ROOT->format_html_attributes($this)
                 . $css_style . '>'
                 . $this->value
                 . '</textarea>';
    $_->buffer[] = '</div>';
  }
}
?>