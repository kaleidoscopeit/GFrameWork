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
    if(isset($this->geometry)) {
      $this->geometry = explode(',',$this->geometry);
      $this->left     = isset($this->geometry[0]) ? $this->geometry[0] : NULL;
      $this->top      = isset($this->geometry[1]) ? $this->geometry[1] : NULL;
      $this->width    = isset($this->geometry[2]) ? $this->geometry[2] : NULL;
      $this->height   = isset($this->geometry[3]) ? $this->geometry[3] : NULL;
    }
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
