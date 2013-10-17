<?php
class data_jtable
{ 
  public $req_attribs = array(
    'style',
    'class'
  );
   
  function __define(&$_)
  {
  }

  function __flush(&$_)
  {
    /* builds syles */
   $css_style = $_->ROOT->boxing($this->boxing).
                $_->ROOT->style_registry_add($this->style).
                $this->class;
                 
    if($css_style!="") $css_style = 'class="w0310 '.$css_style.'" ';

    /* builds code */
    $_->buffer[] = '<div wid="0310" '
                 . $_->ROOT->format_html_attributes($this) . ' '
                 . $css_style.'>'
                 . '</div>';
    
    $_->buffer[] = '<div>';

    gfwk_flush_children($this, 'data_jtablecell');
        
    $_->buffer[] = '</div>';
  }  

}
?>