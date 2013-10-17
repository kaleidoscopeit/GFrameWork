<?php
/*
 * attributes :
 *
 * view   : initial view
 * tcn    : class swap -> default class
 * tci    : class swap -> final 'in' class
 * tco    : class swap -> final 'out' class
 * tct    : class swap -> transition effect class
 */
 
class pack_iview
{
  public $req_attribs = array(
    'style',
    'class',
    'enableparent'
  );

  function __define(&$_)
  {
  }
 
  function __flush(&$_)
  {
    /* Enable a reference to te parent View in the contained iframe View
       NOTE : parentView became available only after the onload event in 
       the contained document */       
    if($this->enableparent)
      $this->attributes['onload'] = 'this.contentWindow.parentView=window;'
                                  . (isset($this->attributes['onload']) 
                                  ? $this->attributes['onload'] : '');

    /* builds syles */
    $css_style = 'class="w0150 '
               . $_->ROOT->boxing($this->boxing)
               . $_->ROOT->style_registry_add($this->style)
               . $this->class . '" ';


    /* builds code */
    $_->buffer[] = '<div wid="0150" '. $css_style
                 . $_->ROOT->format_html_attributes($this).'>';

    $_->buffer[] = '</div>'; 
  } 
}
?>