<?php
/*
 * attributes :
 *
 * onshow   : happens when this element will be shown
 */

class pack_stackelm
{
  public $req_attribs = array(
    'style',
    'class',
    'preset'
  );

  function __define(&$_)
  {
  }

  function __preflush(&$_)
  {
    $this->index = $this->parent->count;
    $this->parent->count++;
  }

  function __flush(&$_)
  {
    /* builds syles */
    $style  = (isset($this->style) ? $this->style : '');
    $boxing = (isset($this->boxing) ? $this->boxing : '');

    $this->attributes['class'] = $_->ROOT->style_registry_add($style)
                               . (isset($this->class) ? $this->class : '');

    /* builds code */
    $_->buffer[] = '<div wid="0131" '
                 . $_->ROOT->format_html_attributes($this)
                 . ($this->parent->preset != $this->index ?
                    'style="visibility:hidden;"' : '')
                 . '> ';

    /* flushes children */
    if (!isset($this->childs))
      $_->buffer[] = "Stack element number " . $this->index;
    else
      gfwk_flush_children($this);

    $_->buffer[] = '</div>';
  }
}
?>
