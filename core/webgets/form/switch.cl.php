<?php
class form_switch
{
  public $req_attribs = array(
    'style',
    'class',
    'disabled',
    'labels',
    'field',
    'value'
  );

  function __define(&$_)
  {
    /* Set default values */
    $t              = array();
    $t['labels'][]  = 'ON,OFF';

    foreach ($t as $key => $value)
      foreach ($value as $local)
        if ($local != null && !$this->$key) $this->$key=$local;


  }

  function __flush (&$_)
  {
    if(isset($this->attributes['id']))
      $name = 'name="' . $this->attributes['id'] .'" ';

    /* set value depending by the presence of 'field' property */
    if($this->field){
      $field        = explode(',', $this->field);
      $param        = explode(':', $field[0]);
      $field_format = ($this->field_format ? $this->field_format : '{0}');
      if(!$_->webgets[$param[0]]->current_record) $cfields[] = $field[0];
      else $field[0] = &array_get_nested
        ($_->webgets[$param[0]]->current_record, $param[1]);

      $value = preg_replace_callback(
      '/\{(\d+)\}/',
      function($match) use ($field) {
        return ($field[$match[1]] != null ? $field[$match[1]] : $match[0]);
      },
      $field_format);
    }

    else $value = $this->value;

    /* enable client field definition */
    if(isset($cfields)) $cfields = 'field="' . implode(',', $cfields)
    . '" field_format="' . $field_format . '" ';

    else $cfields = "";

    /* builds labels */
    $labels = explode(',',$this->labels);

    /* builds syles */
    $style  = (isset($this->style) ? $this->style : '');
    $boxing = (isset($this->boxing) ? $this->boxing : '');

    $css_style = 'class="w0290 '
               . $_->ROOT->boxing($boxing)
               . $_->ROOT->style_registry_add($style)
               . $this->class
               . '" ';

    /* builds code */
    $_->buffer[] = '<div id="' . $this->id . '" wid="0290" '
                 . ($this->disabled ? 'disabled="disabled" ' : '')
                 . $css_style
                 . $cfields
                 . $_->ROOT->format_html_attributes($this)
                 .  '>';
    $_->buffer[] = '<input type="text" value="' . $value . '" '
                 . $name
                 . '></input>';
    $_->buffer[] = '<span>';
    $_->buffer[] = '<span>' . $labels[0] . '</span>';
    $_->buffer[] = '<span>' . $labels[1] . '</span>';
    $_->buffer[] = '</span>';
    $_->buffer[] = '<div>';
    $_->buffer[] = '<button type="button" disabled></button>';
    $_->buffer[] = '</div>';
    $_->buffer[] = '<div></div>';
    $_->buffer[] = '</div>';
  }
}
?>
