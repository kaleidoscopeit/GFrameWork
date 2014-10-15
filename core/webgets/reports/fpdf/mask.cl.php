<?php
class reports_fpdf_mask
{
  public $req_attribs = array(
    'geometry',
    'text_color',
    'draw_color',
    'fill_color',
    'font_family',
    'font_style',
    'font_size',
    'line_width'
  );
  
  function __define(&$_)
  {
    $this->left   = 0;
    $this->top    = 0;
    $this->width  = 0;
    $this->height = 0;

    // webget geometry
    if(isset($this->geometry)){
      $this->geometry = explode(',',$this->geometry);
      $this->left     += (isset($this->geometry[0]) ? $this->geometry[0] : 0);
      $this->top      += (isset($this->geometry[1]) ? $this->geometry[0] : 0);
      $this->width    += (isset($this->geometry[2]) ? $this->geometry[0] : 0);
      $this->height   += (isset($this->geometry[3]) ? $this->geometry[0] : 0);
    }
  }
  
  
  function __flush(&$_)  
  {
    /* apply local styles */
    $_->ROOT->set_local_style($this);
    
    /* setup local coordinates */
    $this->left += $this->parent->left;  
    $this->top  += $this->parent->top;

    gfwk_flush_children($this);

    /* restore parent coordinates */
    $this->left -= $this->parent->left;  
    $this->top  -= $this->parent->top;

    /* restore parent styles */
    $_->ROOT->restore_style();
  }  
}

?>