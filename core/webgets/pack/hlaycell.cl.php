<?php
class pack_hlaycell
{
  public $req_attribs = array(
    'style',
    'class',
    'width'
  );

  function __define(&$_)
  {
  }

  function __flush(&$_)
  {
    /* builds syles */
    $class = (isset($this->class) ? $this->class : '');
    $style = (isset($this->width) ? 'width:' . $this->width.';' : '');

    if(isset($this->within))
      $style .= 'padding-right:' . $this->within
             .  'px;margin-right:-' . $this->within . 'px;';

    else if(isset($this->style)) $style .= $this->style;

    $css_style = $_->ROOT->style_registry_add($style) . ' ';

    /* builds code */
    $_->buffer[] = '<div wid="0111" '
                 . 'class="' . $css_style
                 . (isset($this->within) ? 'w0112' : $class) . '" '
                 . $_->ROOT->format_html_attributes($this)
                 . '>';

    if(isset($this->within))
      $_->buffer[] = '<div class="w0112 ' . $class . '" '
                   . (isset($this->style) ? 'style="' . $this->style . '" ' : '')
                   . '>';

    gfwk_flush_children($this);

    if(isset($this->within))
      $_->buffer[] = '</div>';

    $_->buffer[] ='</div>';

  }

}
?>
