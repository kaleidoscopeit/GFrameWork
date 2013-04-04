<?php
class data_tablecell
{
  function __construct(&$_, $attrs)
  {
    /* imports properties */
    register_attributes($this, $attrs, array(
      'style','class', 'show_if'));

    // Set default values
    $t              = array();
    $t['show_if'][] = 'true';

    foreach ($t as $key => $value)
      foreach ($value as $local)
        if ($local != null && !$this->$key) $this->$key=$local;

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
    $style     =  ($this->parent->columns > 1 ? 'width:'.
                  (100/$this->parent->columns).'% !important;' : '').
                  'height:'.$this->parent->rowheight.';'.$this->style;
                 
    $css_style =  'class="w0301 '.$_->ROOT->style_registry_add($style).
                  $this->class.'" ';
    
    /* builds code */
    $_->buffer[] = '<div parent="' . $this->parent->id
                 . '" wid="0301" index="' . $this->index . '" ' . $css_style
                 . $_->ROOT->format_html_events($this)
                 . $_->ROOT->format_html_attributes($this)
                 . '>';
  
    foreach ((array) @$this->childs as  $child) $child->__flush($_);
  
    $_->buffer[] = '</div>';  
  }
   
   
  function check_show(&$_)
  {
    $self = $this;
    return (eval('return('.$this->show_if.');'));
  }
}
?>