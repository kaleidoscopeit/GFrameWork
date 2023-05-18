<?php
class reports_fpdf_box
{
  public $req_attribs = array(
    'geometry',
    'border',
    'fill_background',

    /* common document flow attributes */
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
    $default['left'][]             = "0";
    $default['top'][]              = "0";
    $default['width'][]            = "100%";
    $default['height'][]           = "100%";

    $default['border'][]           = 1;
    $default['fill_background'][]  = 1;

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
		$left	= $this->pxleft + $this->parent->offsetLeft;
		$top	= $this->pxtop  + $this->parent->offsetTop;

    $fill_background = ($this->fill_background == TRUE
      && $_->ROOT->get_local_style('fill_color') != "" ? "F" : "");

    $style = ($this->border == TRUE ? "D" : "")
           . $fill_background;

		/* paint the rectangle */
		$_->ROOT->fpdf->Rect($left, $top, $this->pxwidth, $this->pxheight, $style);

    /* restore parent styles */
    $_->ROOT->restore_style($this);
	}
}
?>
