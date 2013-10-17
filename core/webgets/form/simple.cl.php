<?php
class form_simple
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
    $_->buffer[] = '<form wid="0200" '
                 . 'method="post" enctype="multipart/form-data" '
                 . $_->ROOT->format_html_attributes($this)
                 . ' >';
    
    gfwk_flush_children($this);

    $_->buffer[] = '</form>';
  }
}
?>