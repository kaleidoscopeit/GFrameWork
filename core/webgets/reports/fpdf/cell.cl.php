<?php
class reports_fpdf_cell
{
  public $req_attribs = array(
    'show_if',
    'geometry',
  );

	function __define(&$_)
	{
    // webget geometry
    $this->geometry = explode(',',$this->geometry);
    $this->left     = 0;
    $this->top      = 0;
    $this->width    = $this->geometry[0];
    $this->height   = $this->geometry[1];

    /* Set default values */
    $t                   = array();
    $t['show_if'][]      = 'true';
    $default['width'][]  = "10%";
    $default['height'][] = "10%";

    foreach ($t as $key => $value)
      foreach ($value as $local)
        if ($local != null && !$this->$key) $this->$key=$local;
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

    /* send local geometry to the parent iterator */
    $this->parent->cell_width = $this->pxwidth;
    $this->parent->cell_height = $this->pxheight;

    /* paint contained webgets */
    gfwk_flush_children($this);

    /* restore parent styles, Void locally set styles */
    $_->ROOT->restore_style();
	}
}
?>
