<?php
class reports_fpdf_box
{
  public $req_attribs = array(
    'geometry',
    'style',
    'draw_color',
    'fill_color',   // filling
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
    $_->ROOT->set_local_style($this, array('fill_color'));

    /* calculate local geometry */
    $_->ROOT->calc_real_geometry($this);

    /* setup local coordinates */
		$left	= $this->pxleft + $this->parent->offsetLeft;
		$top	= $this->pxtop  + $this->parent->offsetTop;

		/* paint the rectangle */
		$_->ROOT->fpdf->Rect($left,$top,$this->pxwidth,$this->pxheight,@$this->style);

    /* restore parent styles */
    $_->ROOT->restore_style(array('fill_color'));
	}
}
?>
