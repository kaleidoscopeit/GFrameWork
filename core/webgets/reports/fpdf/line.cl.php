<?php
class reports_fpdf_line
{
  public $req_attribs = array(
    'geometry',
    'draw_color',
    'line_width'
  );
  
  function __define(&$_)
  {
    // webget geometry
    $this->geometry = explode(',',$this->geometry);
    $this->start_x  = $this->geometry[0];
    $this->start_y  = $this->geometry[1];
    $this->end_x    = $this->geometry[2];
    $this->end_y    = $this->geometry[3];
  }
  
  function __flush (&$_)  
  {
    /* apply local styles */
    $_->ROOT->set_local_style($this);
    
    /* setup local coordinates */
    $start_x = $this->start_x + $this->parent->left;
    $start_y = $this->start_y + $this->parent->top;
    $end_x   = $this->end_x   + $this->parent->left;
    $end_y   = $this->end_y   + $this->parent->top;
    
    /* paint the rectangle */
    $_->ROOT->fpdf->Line($start_x, $start_y, $end_x, $end_y);

    /* restore parent styles */
    $_->ROOT->restore_style();
  }
}
?>