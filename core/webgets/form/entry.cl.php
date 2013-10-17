<?php
class form_entry
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
  
  function __flush(&$_)
  {   
    /* set value depending by the presence of 'field' property */
    if($this->field){
      $field        = explode(',', $this->field);
      $field_format = ($this->field_format ? $this->field_format : '%s');

      foreach($field as $key => $param) {
        $param       = explode(':', $param);
        
        if(!$_->webgets[$param[0]]->current_record) $cfields[] = $field[$key];
          
        $field[$key] = &array_get_nested
                       ($_->webgets[$param[0]]->current_record, $param[1]);           
      }

      $value = vsprintf($field_format, $field); 
    }
    
    else $value = $this->value;

    /* enable client field definition */
    if(isset($cfields)) $cfields = 'field="' . implode(',', $cfields)  
                                 . '" field_format="' . $field_format . '" ';

    else $cfields = "";


    /* builds syles */    
    $w_class   = 'class="w0210 '
               . $_->ROOT->boxing($this->boxing)
               . '" ';
               
    $css_style = $_->ROOT->style_registry_add($this->style)
               . $this->class;
                              
    if($css_style!="") $css_style = 'class="' . $css_style . '" ';

    /* builds code */    
    $_->buffer[] = '<div ' . $w_class . '>';
    $_->buffer[] = '<input type="text" wid="0210" '
                 . $cfields
                 . (isset($value) ? ' value="'.$value.'" ' : '')
                 . $_->ROOT->format_html_attributes($this)
                 . $css_style . '>';
                 
    $_->buffer[] = '</div>';
  }
}
?>