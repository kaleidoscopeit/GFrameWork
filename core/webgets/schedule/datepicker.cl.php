<?php
class schedule_datepicker
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
    $this->attributes['class'] = $_->ROOT->boxing($this->boxing)
                               . $_->ROOT->style_registry_add($this->style)
                               . $this->class;
                 
    /* builds code */
    $_->buffer[] = '<div wid="0400" '
                 . $_->ROOT->format_html_attributes($this)
                 . '> ';

    gfwk_flush_children($this);

    $_->buffer[] = '</div>';
  }  

}
?>