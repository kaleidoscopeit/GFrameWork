<?php
class pack_vpane
{
  public $req_attribs = array(
    'minsize',
    'maxsize',
    'locked'
  );
  
  function __define(&$_)
  {
  }
  
  function __flush(&$_)
  {
    /* builds code */
    $_->buffer[] = '<div wid="0170" '
                 . $_->ROOT->format_html_attributes($this)
                 . '>';

    gfwk_flush_children($this);

    $_->buffer[] = '</div>';
  }
  
}
?>