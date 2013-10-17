<?php
class pack_hpane
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
    $_->buffer[] = '<div wid="0160" '
                 . $_->ROOT->format_html_attributes($this)
                 . '>';

    gfwk_flush_children($this);

    $_->buffer[] = '</div>';
  }
  
}
?>