<?php
class base_overlay
{  
  public $req_attribs = array(
    'src'
  );
  
  function __define(&$_)
  {
    /* dirty interaction with the XML parsing loop */
    $_->current_webget = &$this;
    $source_url = $this->src . '/_this';
    $_->extend_root($source_url);
    $_->current_webget = &$this->parent;
  }

  function __flush(&$_)
  {
    gfwk_flush_children($this);
  }  

}
?>