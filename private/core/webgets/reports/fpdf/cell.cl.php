<?php
class reports_fpdf_cell
{
  public $req_attribs = array(
    'show_if',
    'geometry',
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
    $default                = array();
    $default['show_if'][]   = 'true';

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

  function __preflush(&$_)
  {
    $this->nopaint = NULL;

    if(eval('return(' . $this->show_if . ');') != true) $this->nopaint = true;
    else unset($this->nopaint);
  }

	function __flush(&$_)
	{
    /* apply local styles */
    $_->ROOT->set_local_style($this);

    /* calculate local geometry */
    $_->ROOT->calc_real_geometry($this);

    /* setup local offset */
    $this->offsetLeft = $this->pxleft + $this->parent->cellOffsetLeft;
    $this->offsetTop  = $this->pxtop  + $this->parent->cellOffsetTop;

    /* send local geometry to the parent iterator (DEPRECATED
        -> may be used in variable cell dimensions but not for now) */
//    $this->parent->cell_width = $this->pxwidth;
//    $this->parent->cell_height = $this->pxheight;

    /* paint contained webgets */
    gfwk_flush_children($this);

    /* restore parent styles, Void locally set styles */
    $_->ROOT->restore_style($this);
	}
}
?>
