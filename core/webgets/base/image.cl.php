<?php
class base_image
{
  function __construct(&$_, $attrs)
  {
    /* imports properties */
    register_attributes($this, $attrs, array(
      'style','class','field','field_format','src'));
    
    /* flow control server event */
    eval($this->ondefine);
  }
  

  function __flush(&$_)
  {
    /* flow control server event */
    eval($this->onflush);

    /* no paint switch */    
    if ($this->nopaint) return;

    /* sets src depending by the presence of 'field' property */
    if($this->field){
      $field        = explode(',', $this->field);
      $field_format = ($this->field_format ? $this->field_format : '%s');

      foreach($field as $key => $param) {
        $param       = explode(':', $param);

        /* if no record on server resultset send fields definition to client */
        if(!$_->webgets[$param[0]]->current_record) $cfields[] = $field[$key];
        
        $field[$key] = &array_get_nested
                       ($_->webgets[$param[0]]->current_record, $param[1]);           
      }

      $src = vsprintf($field_format, $field);    
    }
    
    else $src = $this->src;

    /* enable client field definition */
    if($cfields) $cfields = 'field="' . implode(',', $cfields) .
    '" field_format="' . $field_format . '" ';
    
    else $cfields = "";
    
    /* checks if the size of the image was given. If not, resets at the 
       natural image dimensions */
    if($this->boxing != null || $this->boxing != 'false') {
      $boxing = explode(',', $this->boxing);
      $width  = ($boxing[0] != "" ? $boxing[0] : '100%');
      $height = ($boxing[1] != "" ? $boxing[0] : '100%');
    }
    
     if(is_file($src)){
      $imagesize = getimagesize($src);
      $width     = ($boxing[0] != "" ? $width : $imagesize[0]."px");
      $height    = ($boxing[1] != "" ? $height : $imagesize[1]."px");
    }

    /* builds syles */
    $css_style = $_->ROOT->boxing($this->boxing, $width, $height)
               . $_->ROOT->style_registry_add($this->style).$this->class;

    if($css_style!="") $css_style = 'class="'.$css_style.'" ';

    /* builds code */    
    $_->buffer[] = '<img wid="0020" src="' . $src . '" '
                 . $css_style . $_->ROOT->format_html_attributes($this) . ' '
                 . ($this->tip ? 'title="'.$this->tip.'"' : ' ')
                 . $cfields
                 . '/>';
  }  
}
?>