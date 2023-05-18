<?php
class form_number
{
  public $req_attribs = array(
    'style',
    'class',
    'field',
    'field_format',
    'value',
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
    /* set value depending by the presence of 'field' property */
    if(isset($this->field)){
      $field        = explode(',', $this->field);
      $field_format = (isset($this->field_format) ? $this->field_format : '{0}');

      foreach($field as $key => $param) {
        $param       = explode(':', $param);

        if(!$_->webgets[$param[0]]->current_record) $cfields[] = $field[$key];

        $field[$key] = array_get_nested
                       ($_->webgets[$param[0]]->current_record, $param[1]);
      }

      $value = preg_replace_callback(
        '/\{(\d+)\}/',
        function($match) use ($field) {
          return ($field[$match[1]] != null ? $field[$match[1]] : $match[0]);
        },
        $field_format
      );
    }

    else if(isset($this->value)) $value = $this->value;

    /* enable client field definition */
    if(isset($cfields)) $cfields = 'field="' . implode(',', $cfields)
                                 . '" field_format="' . $field_format . '" ';

    else $cfields = "";

    /* builds syles */
    $style   = (isset($this->style) ? $this->style : '');
    $class   = (isset($this->class) ? $this->class : '');
    $wstyle  = (isset($this->wstyle) ? $this->wstyle : '');
    $wclass  = (isset($this->wclass) ? $this->wclass : '');
    $boxing  = (isset($this->boxing) ? $this->boxing : '');

    $w_class   = 'class="w02D0 '
               . $_->ROOT->boxing($boxing)
               . $_->ROOT->style_registry_add($wstyle)
               . $wclass
               . '" ';

    $css_style = $_->ROOT->style_registry_add($style)
               . $class;

    if($css_style!="") $css_style = 'class="' . $css_style . '" ';

    /* builds code */
    $_->buffer[] = '<div ' . $w_class . '>';
    $_->buffer[] = '<input type="number" wid="02D0" '
                 . $cfields
                 . (isset($value) ? ' value="' . $value . '" ' : '')
                 . $_->ROOT->format_html_attributes($this)
                 . $css_style . '>';

    $_->buffer[] = '</div>';
  }
}
?>
