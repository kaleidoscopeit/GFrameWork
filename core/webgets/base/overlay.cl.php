<?php
class base_overlay
{  
  function __construct(&$_, $attrs)
  {
    /* imports properties */
    foreach ($attrs as $key=>$value) $this->$key=$value;

    /* dirty interaction with the XML parsing loop */
    $_->current_webget = &$this;
    $source_url = $this->src . '/_this';
    $_->extend_root($source_url);
    $_->current_webget = &$this->parent;
    
    /* flow control server event */
    eval($this->ondefine);
  }

  function __flush(&$_)
  {
    /* flow control server event */
    eval($this->onflush);

    /* no paint switch */    
    if ($this->nopaint) return;

    /* flushes children */
    foreach ((array) @$this->childs as  $child) $child->__flush($_);
  }  

}
?>