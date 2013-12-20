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
    'halign'
  );

  function __define(&$_)
  {
  }
  
  function __flush(&$_)
  {
    /* set caption depending by the presence of 'field' property */
    if(isset($this->field)){
      $field        = explode(',', $this->field);
      $field_format = ($this->field_format ? $this->field_format : '%s');

      foreach($field as $key => $param) {
        $param       = explode(':', $param);

        /* if no record on server resultset send fields definition to client */
        if(!$_->webgets[$param[0]]->current_record) $cfields[] = $field[$key];

        $field[$key] = &array_get_nested
                       ($_->webgets[$param[0]]->current_record, $param[1]);           
      }

      $caption = vsprintf($field_format, $field); 
    }
    
    else $caption = $this->caption;

    if($caption == "" && $this->default != "") $caption = $this->default;

    /* enable client field definition */
    if(isset($cfields)) $cfields = 'field="' . implode(',', $cfields) . 
                                   '" field_format="' . $field_format . '" ';

    else $cfields = "";
    
    $boxing = explode(',', @$this->boxing);

    /* builds syles */
    $style     = $this->style.
                 ($this->halign ? 'text-align:'.$this->halign : '');

    $css_style = $_->ROOT->boxing($this->boxing)
               . $_->ROOT->style_registry_add($style)
               . $this->class;

    $css_style = 'class="w0010 '.$css_style.'" ';

    $_->buffer[] = '<div wid="0010" '
                 . $css_style
                 . $_->ROOT->format_html_attributes($this)
                 . $cfields
                 . '>';

    $_->buffer[] = '<span class="w0011" >';

    $_->buffer[] = '<span style="vertical-align:'. $this->valign.'">';
    $_->buffer[] = $caption;
    $_->buffer[] = '</span>';
    $_->buffer[] = '</span>';
    $_->buffer[] = '</div>';
  }
}
?>