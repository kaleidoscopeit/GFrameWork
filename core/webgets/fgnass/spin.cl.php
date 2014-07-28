<?php
class fgnass_spin
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
    $this->attributes['class'] = "w5000 "
                               . $_->ROOT->boxing($this->boxing)
                               . $_->ROOT->style_registry_add($this->style)
                               . $this->class;
             
    /* builds code */
    $_->buffer[] = '<div wid="5000" '
                 . $_->ROOT->format_html_attributes($this)
                 . '>';

    $_->buffer[] = '</div>';
  }  

}
?>