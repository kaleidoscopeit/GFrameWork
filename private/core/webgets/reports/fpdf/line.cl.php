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
    /* Set default values */
    $default               = array();

    /* queue webget geometry if sets through the XML */
    if(isset($this->geometry)) {
      $this->geometry = explode(',', $this->geometry);
      $default['left'][]   = isset($this->geometry[0]) ? $this->geometry[0] : NULL;
      $default['top'][]    = isset($this->geometry[1]) ? $this->geometry[1] : NULL;
      $default['width'][]  = isset($this->geometry[2]) ? $this->geometry[2] : NULL;
      $default['height'][] = isset($this->geometry[3]) ? $this->geometry[3] : NULL;
    }

    /* then sets default geometry */
    $default['left'][]     = "0";
    $default['top'][]      = "0";
    $default['width'][]    = "100%";
    $default['height'][]   = "100%";

    foreach ($default as $key => $value)
      foreach ($value as $local)
        if ($local !== null && !isset($this->$key)) $this->$key=$local;
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
    $_->ROOT->restore_style($this);
  }
}
?>
