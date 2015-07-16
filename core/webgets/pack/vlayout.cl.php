<?php
class pack_vlayout
{
  public $req_attribs = array(
    'style',
    'class',
    'naked',
    'class'
  );

  function __define(&$_)
  {
  }

  function __flush(&$_)
  {
    /* children size definining */
    if(!isset($fixed_height)) $fixed_height = 0;
    $float_childs = array();

    if(isset($this->childs)) {
      foreach ($this->childs as  $key => $child) {
        if (get_class($child)=='pack_hlaycell')
          gfwk_flush($child, true);
      }

      foreach ($this->childs as  $key => $child) {
        if(!isset($child->nopaint)) {
          if(isset($child->height)) $fixed_height +=
            str_replace('px', '', $child->height);

          else $float_childs[] = $key;
        }
      }
    }

    if(count($float_childs) > 0) {
      $float_div = 100/count($float_childs);

      if($fixed_height != 0)
        $within = $fixed_height/count($float_childs);

      else
        $within = false;

      foreach ($float_childs as $key){
        $this->childs[$key]->height = $float_div.'%';
        $this->childs[$key]->within = $within;
        if(isset($this->childs[$key]->minheight))
          $fixed_height += $this->childs[$key]->minheight;
      }
    }

    /* builds syles */
    $boxing    = (isset($this->boxing) ? $this->boxing : '');
    $style     = (isset($this->style) ? $this->style : '');
    $class     = (isset($this->class) ? $this->class : '');

    $this->attributes['class'] =
        $_->ROOT->boxing($boxing)
      . $_->ROOT->style_registry_add(
        'min-height:' . $fixed_height . 'px;' . $style)
      . $class;

    /* builds code */
    if(!isset($this->naked))
      $_->buffer[] = '<div wid="0120" '
                   . $_->ROOT->format_html_attributes($this)
                   . '>';

    gfwk_flush_children($this, 'pack_vlaycell');

    if(!isset($this->naked))
      $_->buffer[] = '</div>';
  }
}
?>
