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

    /* builds syles */
    $style     = $this->style.
                 ($this->halign ? 'text-align:'.$this->halign : '');
                 
    $css_style = $_->ROOT->boxing($this->boxing).
                 $_->ROOT->style_registry_add($style).$this->class;
                 
    if($css_style!="") $css_style = 'class="'.$css_style.'" ';

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

    /* builds code */
    if ($this->valign == 'middle' || $this->valign == 'bottom')
   
    $_->buffer .= '<div id="'.$this->id.'" wid="0010" '.$css_style.
                  $_->ROOT->format_html_events($this, array('mouse')).'>'.
                  '<span><span style="vertical-align:'.$this->valign.'">'.
                  $caption.'</span></span></div>';
              
    else
  
    $_->buffer .= '<div id="'.$this->id.'" wid="0010" '.$css_style.
                  $_->ROOT->format_html_events($this, array('mouse')).'>'.
                  $caption.'</div>';
  }
}
?>