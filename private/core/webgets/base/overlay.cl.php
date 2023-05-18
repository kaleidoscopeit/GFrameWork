<?php
class base_overlay
{
  public $req_attribs = array(
    'view'
  );

  function __define(&$_)
  {
    /* dirty interaction with the XML parsing loop */
    $_->ENGINE->current_webget = &$this;
    $_->ENGINE->populate_root_object("views/" . $this->view);
    $_->ENGINE->current_webget = &$this->parent;
  }

  function __flush(&$_)
  {
    $_->ROOT->css_rules['includes']['views/' . $this->view . '/_this.css'] = 1;
    $_->ROOT->js_includes['views/' . $this->view . '/_this.js' ] = true;

    gfwk_flush_children($this);
  }

}
?>
