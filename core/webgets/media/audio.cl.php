<?php
class media_audio
{
  public $req_attribs = array(
  );
  
  function __define(&$_)
  {
  }
  
  function __flush(&$_ )
  {
    $_->buffer[] = '<audio  '
                 . $_->ROOT->format_html_attributes($this)
                 . '></audio>';
  }  
}
?>