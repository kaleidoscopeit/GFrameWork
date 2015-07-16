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
    $this->left     = $this->geometry[0];
    $this->top      = $this->geometry[1];
    $this->width    = $this->geometry[2];
    $this->height   = $this->geometry[3];

    /* Set default values */
    $default               = array();
    $default['left'][]     = "0";
    $default['top'][]      = "0";
    $default['width'][]    = "100%";
    $default['height'][]   = "100%";

    foreach ($default as $key => $value)
    foreach ($value as $local)
    if ($local != null && !$this->$key) $this->$key=$local;
  }

  function __flush (&$_)
  {
    /* apply local styles */
    $_->ROOT->set_local_style($this);

    /* calculate local geometry */
    $_->ROOT->calc_real_geometry($this);

    /* setup local coordinates */
    $start_x = $this->pxleft + $this->parent->offsetLeft;
    $start_y = $this->pxtop  + $this->parent->offsetTop;
    $end_x	 = $this->pxwidth + $this->parent->offsetLeft;
    $end_y	 = $this->pxheight + $this->parent->offsetTop;

    /* paint the line */
    $_->ROOT->fpdf->Line($start_x, $start_y, $end_x, $end_y);

    /* restore parent styles */
    $_->ROOT->restore_style();
  }
}
?>
