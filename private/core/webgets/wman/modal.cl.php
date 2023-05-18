<?php
/*
* attributes :
*
* root   : ????
*/

class wman_modal
{
  public $req_attribs = array(
    'style',
    'class',
    'id'
  );

  function __define(&$_)
  {
  }

  function __flush(&$_)
  {
    /* builds syles */
    $style  = (isset($this->style) ? $this->style : '');
    $boxing = (isset($this->boxing) ? $this->boxing : '');

    $this->attributes['class']  = 'gbsz '
                                . $_->ROOT->boxing($boxing)
                                . $_->ROOT->style_registry_add('overflow:auto;' . $style)
                                . $this->class . '" ';

    /* builds code */
    $_->buffer[] = '<div class="modalwa" wid="0180" id="' . $this->id . '">';
    $_->buffer[] = '<div class="modalwb gwha" '
                 . 'onclick="this.parentNode.hide()"></div>';
    $_->buffer[] = '<div ' . $_->ROOT->format_html_attributes($this) . '>';

    gfwk_flush_children($this);

    $_->buffer[] = '</div>';
    $_->buffer[] = '</div>';
  }
}
?>
