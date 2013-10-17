<?php
class form_password
{
  public $req_attribs = array(
    'style',
    'class'
  );
  
  function __define(&$_)
  {
    if(isset($this->attributes['id']))
      $this->attributes['name'] = $this->attributes['id'];
  }
  
  function __flush(&$_)
  {  
    $w_class   = 'class="w0240 '
               . $_->ROOT->boxing($this->boxing)
               . '" ';
               
    $css_style = $_->ROOT->style_registry_add($this->style)
               . $this->class;
                   
    if($css_style!="") $css_style = 'class="'.$css_style.'" ';
    
    $_->buffer[] = '<div wid="0240" wbg ' . $w_class . '>';
    $_->buffer[] = '<input type="password" '
                 . $_->ROOT->format_html_attributes($this).' '
                 . $css_style . '>';
    $_->buffer[] = '</div>';
  }
}
?>