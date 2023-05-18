<?php
class pack_vlaycell
{
  public $req_attribs = array(
    'style',
    'class',
    'height',
    'min_height' // not full implemented
  );

  function __define(&$_)
  {
  }

  function __flush(&$_)
  {
    /* builds syles */
    $class = (isset($this->class) ? $this->class : '');
    $style = (isset($this->height) ? 'height:' . $this->height.';' : '');
    $style .= (isset($this->min_height) ? 'min-height:' . $this->min_height . ';' : '');

    /* style applied to cells with no size set */
    if(isset($this->within))
      $style .= 'padding-bottom:' . $this->within
             .  'px;margin-bottom:-' . $this->within . 'px;';

    else if(isset($this->style)) $style .= $this->style;

    $css_style = $_->ROOT->style_registry_add($style) . ' ';

    /* builds code */
    $_->buffer[] = '<div wid="0121" '
                 . 'class="' . $css_style
                 . (isset($this->within) ? 'w0122' : $class) . '" '
                 . $_->ROOT->format_html_attributes($this)
                 . '>';

    if(isset($this->within))
      $_->buffer[] = '<div class="w0122 ' . $class . '" '
                   . (isset($this->style) ? 'style="' . $this->style . '" ' : '')
                   . '>';

    gfwk_flush_children($this);

    if(isset($this->within))
      $_->buffer[] = '</div>';

    $_->buffer[] ='</div>';

  }

}
?>
