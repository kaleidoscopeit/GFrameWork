<?php
class base_label
{
  public $req_attribs = array(
    'style',
    'class',
    'field',
    'field_format',
    'caption',
    'default',
    'valign',
    'halign',
    'send_field'
  );

  function __define(&$_)
  {
  }

  function __flush(&$_)
  {
    /* set caption depending by the presence of 'field' property */
    if(isset($this->field)){
      $field        = explode(',', $this->field);
      $field_format = (isset($this->field_format) ? $this->field_format : '{0}');

      foreach($field as $key => $param) {
        $param       = explode(':', $param);

        /* if no record on server resultset or forcing sending field to client
         * send fields definition to client */
        if(!isset(_w($param[0])->current_record) || isset($this->send_field))
          $cfields[] = $field[$key];

        $field[$key] = array_get_nested
                       (_w($param[0])->current_record, $param[1]);

      }

      $caption = preg_replace_callback(
        '/\{(\d+)\}/',
        function($match) use ($field) {
          return $field[$match[1]];
        },
        $field_format
      );
    }

    else if(isset($this->caption)) {
      $caption = $this->caption;
    }

    else {
      $caption = '';
    }

    if($caption == "" && isset($this->default)) $caption = $this->default;

    /* enable client field definition */
    if(isset($cfields)) $cfields = 'field="' . implode(',', $cfields) .
                                   '" field_format="' . $field_format . '" ';

    else $cfields = "";

    /* builds syles */
    $boxing    = (isset($this->boxing) ? $this->boxing : '');
    $class     = (isset($this->class) ? $this->class : '');

    $style     = @$this->style.
                 (isset($this->halign) ? 'text-align:'.$this->halign : '');

    $css_style = $_->ROOT->boxing($boxing)
               . $_->ROOT->style_registry_add($style)
               . $class;

    $css_style = 'class="w0010 '.$css_style.'" ';

    $_->buffer[] = '<div wid="0010" '
                 . $css_style
                 . $_->ROOT->format_html_attributes($this)
                 . $cfields
                 . '>';

    $_->buffer[] = '<span class="w0011" >';

    $_->buffer[] = '<span '
                . (isset($this->valign) ? 'style="vertical-align:'
                . $this->valign . '"' : '')
                . '>';
    $_->buffer[] = $caption;
    $_->buffer[] = '</span>';
    $_->buffer[] = '</span>';
    $_->buffer[] = '</div>';
  }
}
?>
