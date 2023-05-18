<?php
class data_tablecell
{
  public $req_attribs = array(
    'style',
    'class',
    'show_if'
  );

  function __define(&$_)
  {
    /* Set default values */
    $t              = array();
    $t['show_if'][] = 'true';

    foreach ($t as $key => $value)
      foreach ($value as $local)
        if ($local !== null && !isset($this->$key)) $this->$key=$local;
  }

  function __preflush(&$_)
  {
    $this->nopaint = NULL;

    /* gets environmental data from current table status. */
    $this->index = $this->parent->start_pointer+$this->parent->page_pointer;
    $this->current_record = $this->parent->result_set[$this->index];
    $this->parent->current_record  = $this->current_record;

    if(eval('return(' . $this->show_if . ');') != true) $this->nopaint = true;
    else unset($this->nopaint);
  }

  function __flush(&$_)
  {
    /* builds syles */
    $class  = (isset($this->class) ? $this->class : '');

    $style  = ($this->parent->columns > 1 ? 'width:'
            .   (100/$this->parent->columns) . '% !important;' : '')
            . 'height:' . $this->parent->rowheight . ';'
            . (isset($this->style) ? $this->style : '');

    $css_style  = 'class="w0301 '
                . $_->ROOT->style_registry_add($style)
                . $class
                . '" ';

    /* builds code */
    $_->buffer[] = '<div parent="' . $this->parent->id
                 . '" wid="0301" index="' . $this->index . '" '
                 . $css_style
                 . $_->ROOT->format_html_attributes($this)
                 . '>';

    gfwk_flush_children($this);

    $_->buffer[] = '</div>';
  }
}
?>
