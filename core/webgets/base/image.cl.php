<?php
class base_image
{
  function __construct(&$_, $attrs)
  {
    /* imports properties */
    foreach ($attrs as $key=>$value) $this->$key=$value;
    
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
        $field[$key] = &array_get_nested
                       ($_->webgets[$param[0]]->current_record, $param[1]);           
      }

      $src = vsprintf($field_format, $field);    
    }
    
    else $src = $this->src;
      
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
    $css_style = $_->ROOT->boxing($this->boxing, $width, $height).
                 $_->ROOT->style_registry_add($this->style).$this->class;
                 
    if($css_style!="") $css_style = 'class="'.$css_style.'" ';

    /* builds code */    
    $_->buffer .= '<img id="'.$this->id.'" '.'src="'.$src.'" '.$css_style.                
                  $_->ROOT->format_html_events($this, array('mouse')).
                  ($this->tip ? 'title="'.$this->tip.'"' : ' ').
                  '/>';
  }  
}
?>