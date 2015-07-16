<?php
class reports_fpdf_image
{
  public $req_attribs = array(
    'geometry',
    'url',
  );

	function __define(&$_)
  {
    // webget geometry
    $this->geometry = explode(',',$this->geometry);
    $this->left     = $this->geometry[0];
    $this->top      = $this->geometry[1];
    $this->width    = $this->geometry[2];
    $this->height   = $this->geometry[3];
	}

	function __flush(&$_)
	{
    /* setup local coordinates */
		$left	= $this->left + $this->parent->offsetLeft;
		$top	= $this->top  + $this->parent->offsetTop;

		// Paint image
		$_->ROOT->fpdf->Image($this->url,$left,$top,$this->width,$this->height);
	}
}
?>
