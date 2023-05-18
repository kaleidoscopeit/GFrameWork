<?php
class reports_toshibatec_group
{
  public $req_attribs = array(
    'left',
    'top',
  );

  function __define(&$_)
  {
    /* Set default values */
    $default               = array();
    $default['left'][]     = "0";
    $default['top'][]      = "0";

    foreach ($default as $key => $value)
      foreach ($value as $local)
        if ($local !== null && !isset($this->$key)) $this->$key=$local;
  }

  function __flush (&$_)
  {
    /* setup local coordinates */
    $this->offsetLeft = $this->left + $this->parent->offsetLeft;
    $this->offsetTop  = $this->top + $this->parent->offsetTop;

    /* paint contained webgets */
    gfwk_flush_children($this);
  }
}
?>
