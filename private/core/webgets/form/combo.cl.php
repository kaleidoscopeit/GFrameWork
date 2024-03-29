<?php
class form_combo
{
  public $req_attribs = array(
    'style',
    'class',
    'field',
    'field_format',
    'values',
    'labels',
    'default',
    'wstyle',   // da implementare su tutti i webget compositi
    'wclass'    // da implementare su tutti i webget compositi
  );

  function __define(&$_)
  {
    if(isset($this->attributes['id']))
      $this->attributes['name'] = $this->attributes['id'];
  }

  function __flush(&$_)
  {
    /* Import the static values from the view */
    if (isset($this->values)) {
      $this->items['values'] = explode('|', $this->values);

      if(isset($this->labels)) {
        $this->items['labels'] = explode('|', $this->labels);
      } else {
        $this->items['labels'] = $this->items['values'];
      }

      if (count($this->items['labels']) != count($this->items['values'])) {
        $this->items['labels'] = $this->items['values'];
      }

    }

    /* builds syles */
    $style   = (isset($this->style) ? $this->style : '');
    $wstyle  = (isset($this->wstyle) ? $this->wstyle : '');
    $boxing  = (isset($this->boxing) ? $this->boxing : '');

    $w_class  = 'class="w02A0 '
              . $_->ROOT->boxing($boxing)
              . $_->ROOT->style_registry_add($wstyle)
              . (isset($this->wclass) ? $this->wclass : '')
              . '" ';

    $css_style  = $_->ROOT->style_registry_add($style)
                . (isset($this->class) ? $this->class : '');

    if($css_style!="") $css_style = 'class="' . $css_style . '" ';

    $_->buffer[] = '<div  wid="02A0" ' . $w_class . '>';

    $_->buffer[] = '<select wid="02A1" '
                 . $_->ROOT->format_html_attributes($this)
                 . $css_style . '>';


    if (isset($this->items['values'])) {
      foreach ($this->items['values'] as $key => $value) {
        $_->buffer[] = '<option value="' . $value . '"'
                     . (@$this->default == $value ? ' selected' : '') . '>';
        $_->buffer[] = $this->items['labels'][$key];
        $_->buffer[] = '</option>';
      }
    }

    $_->buffer[] = '</select>';
    $_->buffer[] = '</div>';
  }

  function items_insert( $value, $label = -1, $index = null )
  {
    $index = $index === null ? count(@$this->items['values']) : $index;
    $index = intval($index);

    $this->items['values'] =
      array_merge (
        (@$this->items['values'] ?
          array_slice(@$this->items['values'], 0, $index) : array()),

         array("$value"),

        (@$this->items['values'] ?
          array_slice($this->items['values'], $index) : array())
      );

    $this->items['labels'] =
      array_merge (
        (isset($this->items['labels']) ?
          array_slice($this->items['labels'], 0, $index) : array()),

        array(($label != -1 ? $label : $value)),

        (isset($this->items['labels']) ?
          array_slice($this->items['labels'], $index) : array())
      );
  }

  /* delete item by value or by index */
  function items_remove($item)
  {
    if (count($this->items['values']) == 0) return false;

    if (is_int($item) && $this->items['values'][$item]) {
      unset($this->items['values'][$item]);
      unset($this->items['labels'][$item]);
      return true;
    }

    if (is_string($item)) {
      foreach($this->items['values'] as $key => $value) {
        if ($value === $item) {
          unset($this->items['values'][$key]);
          unset($this->items['labels'][$key]);
          return true;
        }
      }
    }

    return false;
  }
}
?>
