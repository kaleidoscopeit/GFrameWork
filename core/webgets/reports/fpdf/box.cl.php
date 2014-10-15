<?php
class reports_fpdf_box
{
  public $req_attribs = array(
    'geometry',
    'style',
    'draw_color',
    'fill_color',
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
	}
	
	function __flush (&$_)	
	{
    /* apply local styles */
    $_->ROOT->set_local_style($this, array('fill_color'));

    /* setup local coordinates */
		$left	= $this->left + $this->parent->left;
		$top	= $this->top  + $this->parent->top;

		/* paint the rectangle */
		$_->ROOT->fpdf->Rect($left,$top,$this->width,$this->height,@$this->style);

    /* restore parent styles */
    $_->ROOT->restore_style(array('fill_color'));
	}
}
?>