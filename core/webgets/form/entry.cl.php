<?php
class form_entry {
  
  function __construct(&$_, $attrs)
  {
    /* imports properties */
    register_attributes($this, $attrs, array(
      'style','class','disabled','readonly','field','field_format'));

    /* flow control server event */
    eval($this->ondefine);
  }
  
  function __flush(&$_)
  {    
    /* flow control server event */
    eval($this->onflush);

    /* no paint switch */    
    if ($this->nopaint) return;

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
    if($cfields) $cfields = 'field="' . implode(',', $cfields) . 
                           '" field_format="' . $field_format . '" ';

    else $cfields = "";
    
    /* builds syles */    
    $w_class          = 'class="w0210 '.$_->ROOT->boxing($this->boxing).'" ';
    $css_style        = $_->ROOT->style_registry_add($this->style).
                        $this->class;
                              
    if($css_style!="")  $css_style = 'class="'.$css_style.'" ';

    /* builds code */    
    $_->buffer[] = '<div ' . $w_class . '>';
    $_->buffer[] = '<input id="' . $this->id
                 . '" name="' . $this->id . '" wid="0210" '
                 . $_->ROOT->format_html_attributes($this) . ' '
                 . 'type="text" ' . $css_style . $cfields
                 . ($this->disabled ? ' disabled ' : '')
                 . ($this->readonly ? ' readonly ' : '')
                 . ($this->tip ? ' title="'.$this->tip.'" ' : '')
                 . ($value ? ' value="'.$value.'" ' : '') . '>';
    $_->buffer[] = '</div>';
  }
}
?>