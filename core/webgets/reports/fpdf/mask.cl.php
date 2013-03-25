<?php
class fpdf_mask
{
  function __construct(&$_, $attrs)
  {
    /* imports properties */
    foreach ($attrs as $key=>$value) $this->$key=$value;  
     
    // webget geometry
    $this->geometry = explode(',',$this->geometry);
    $this->left     = $this->geometry[0];
    $this->top      = $this->geometry[1];
    $this->width    = $this->geometry[2];
    $this->height   = $this->geometry[3];

    /* flow control server event */
    eval($this->ondefine);
  }
  
  
  function __flush(&$_)  
  {
    /* flow control server event */
    eval($this->onflush);

    /* no paint switch */    
    if ($this->nopaint) return;  

    /* apply local styles */
    $_->ROOT->set_local_style('text_color',$this->text_color);
    $_->ROOT->set_local_style('draw_color',$this->draw_color);
    $_->ROOT->set_local_style('fill_color',$this->fill_color);
    $_->ROOT->set_local_style('font_family',$this->font_family);
    $_->ROOT->set_local_style('font_style',$this->font_style);
    $_->ROOT->set_local_style('font_size',$this->font_size);
    $_->ROOT->set_local_style('line_width',$this->border_width);
    
    /* setup local coordinates */
    $this->left += $this->parent->left;  
    $this->top  += $this->parent->top;

    /* flushes children */
    foreach ((array) @$this->childs as  $child) $child->__flush($_);

    /* restore parent coordinates */
    $this->left -= $this->parent->left;  
    $this->top  -= $this->parent->top;

    /* restore parent styles */
    $_->ROOT->restore_style('text_color');
    $_->ROOT->restore_style('draw_color');
    $_->ROOT->restore_style('fill_color');
    $_->ROOT->restore_style('font_family');
    $_->ROOT->restore_style('font_style');
    $_->ROOT->restore_style('font_size');
    $_->ROOT->restore_style('line_width');    
  }  
}

?>