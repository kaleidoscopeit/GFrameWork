<?php
class form_switch
{
  public $req_attribs = array(
    'style',
    'class',
    'disabled'
  );
  
  function __define(&$_)
  {
  }
  
  function __flush (&$_)
  {
    if(isset($this->attributes['id']))
      $name = 'name="' . $this->attributes['id'] .'" ';
      echo "a";
    /* builds syles */  
    $css_style = 'class="w0290 '
               . $_->ROOT->boxing($this->boxing)
               . $_->ROOT->style_registry_add($this->style)
               . $this->class
               . '" ';
                 
    /* builds code */
    $_->buffer[] = '<div id="' . $this->id . '" wid="0290" '
                 . ($this->disabled ? 'disabled="disabled" ' : '')
                 . $css_style . '>';
    $_->buffer[] = '<input type="text" value="" ' . $name
                 . '></input>';
    $_->buffer[] = '<span>';
    $_->buffer[] = '<span>ON</span>';
    $_->buffer[] = '<span>OFF</span>';
    $_->buffer[] = '</span>';
    $_->buffer[] = '<div>';
    $_->buffer[] = '<button type="button" disabled></button>';
    $_->buffer[] = '</div>';
    $_->buffer[] = '<div ' . $_->ROOT->format_html_attributes($this)
                 . '></div>';
    $_->buffer[] = '</div>';
  }  
}
?>


