<?php
class form_hidden
{
  public $req_attribs = array(
    'field',
    'field_format'
  );
  
  function __define(&$_)
  {
    if(isset($this->attributes['id']))
      $this->attributes['name'] = $this->attributes['id'];
  }
  
  function __flush(&$_)
  {  
    $_->buffer[] = '<input type="hidden" wid="0220" '
                 . $_->ROOT->format_html_attributes($this)
                 . ' />';
  }
}
?>