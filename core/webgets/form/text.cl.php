<?php
class form_text
{
  public $req_attribs = array(
    'style',
    'class',
    'field',
    'field_format',
    'value'
  );

  function __define(&$_)
  {
    if(isset($this->attributes['id']))
      $this->attributes['name'] = $this->attributes['id'];
   }

  function __flush(&$_ )
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

    else if(isset($this->value)) {
      $value = $this->value;
    }

    else {
      $value = '';
    }

    /* builds syles */
    $style  = (isset($this->style) ? $this->style : '');
    $boxing = (isset($this->boxing) ? $this->boxing : '');

    $w_class  = 'class="w0230 '
              . $_->ROOT->boxing($boxing)
              . '" ';

    $css_style = $_->ROOT->style_registry_add('resize: none;' . $style)
               . $this->class;

    if($css_style!="") $css_style = 'class="' . $css_style . '" ';

    /* builds code */
    $_->buffer[] = '<div ' . $w_class . '>';
    $_->buffer[] = '<textarea wid="0230" '
                 . $_->ROOT->format_html_attributes($this)
                 . $css_style . '>'
                 . $value
                 . '</textarea>';
    $_->buffer[] = '</div>';
  }
}
?>
