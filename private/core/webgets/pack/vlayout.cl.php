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
      $this->compute_fixed_height($fixed_height, $float_childs);
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
        if(isset($this->childs[$key]->min_height))
          $fixed_height += str_ireplace('px', '', $this->childs[$key]->min_height);
      }
    }

    /* builds syles */
    $boxing = (isset($this->boxing) ? $this->boxing : '');
    $style  = (isset($this->style) ? $this->style : '');

    $this->attributes['class'] =
        $_->ROOT->boxing($boxing)
      . $_->ROOT->style_registry_add('min-height:' . $fixed_height . 'px;' . $style)
      . (isset($this->class) ? $this->class : '');

    /* builds code */
    if(!isset($this->naked))
      $_->buffer[] = '<div wid="0120" '
                   . $_->ROOT->format_html_attributes($this)
                   . '>';

    gfwk_flush_children($this, 'pack_vlaycell');

    foreach ($float_childs as $key){
      unset($this->childs[$key]->height);
      unset($this->childs[$key]->within);
    }

    if(!isset($this->naked))
      $_->buffer[] = '</div>';
  }

  function compute_fixed_height(&$fixed_height = 0, &$float_childs = array()) {
    foreach ($this->childs as  $key => $child) {
      if (get_class($child)=='pack_vlaycell')
        gfwk_flush($child, true);
    }

    foreach ($this->childs as $key => $child) {
      if(!isset($child->nopaint)) {
        if(isset($child->height)) {
          $fixed_height += str_replace('px', '', $child->height);
        }
        else $float_childs[] = $key;
      }
    }

    return $fixed_height;
  }
}
?>
