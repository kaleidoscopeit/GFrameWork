<?php
class base_label
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

    /* set caption depending by the presence of 'field' property */
    if($this->field){
      $field        = explode(',', $this->field);
      $field_format = ($this->field_format ? $this->field_format : '%s');

      foreach($field as $key => $param) {
        $param       = explode(':', $param);
        $field[$key] = &array_get_nested
                       ($_->webgets[$param[0]]->current_record, $param[1]);           
      }

      $caption = vsprintf($field_format, $field);    
    }
    
    else $caption = $this->caption;


    /* label type switch */
    if($this->valign == 'middle' || $this->valign == 'bottom') $align = 10;

    $boxing = explode(',', $this->boxing);
    
    if(preg_match('/x|%/',substr($boxing[2], -1).substr($boxing[3], -1)))
      $margins = 1; 

    /* builds syles */
    $style     = $this->style.
                 ($this->halign ? 'text-align:'.$this->halign : '');
                 
    $css_style = $_->ROOT->boxing($this->boxing).
                 $_->ROOT->style_registry_add($style).$this->class;
                 

    /* builds code */
    switch($align+$margins){
      case 11:

        if($css_style!="") $css_style = 'class="w0010 '.$css_style.'" ';      
        
        $_->buffer .= '<div id="'.$this->id.'" wid="0010" wbg '.$css_style.
                      $_->ROOT->format_html_events($this).'>'.
                      '<span class="w0011" style="vertical-align:'.
                      $this->valign.'"><span>'.
                      $caption.'</span></span></div>';
        break;


      case 10 :             

        if($css_style!="") $css_style = 'class="w0010 w0011 '.$css_style.'" ';
        
        $_->buffer .= '<div id="'.$this->id.'" wid="0010" wbg '.$css_style.
                      $_->ROOT->format_html_events($this).'>'.
                      '<span style="vertical-align:'.$this->valign.'">'.
                      $caption.'</span></div>';

        break;


      default :

        if($css_style!="") $css_style = 'class="w0010 '.$css_style.'" ';      
                            
        $_->buffer .= '<div id="'.$this->id.'" wid="0010" wbg '.$css_style.
                      $_->ROOT->format_html_events($this).'>'.
                      $caption.'</div>';

        break;
    }
    
 //   $_->ROOT->attach_client_events($this,$this->id);
  }
}
?>