<?php
class fpdf_box
{
	function __construct (&$_, $attrs)
	{
    /* imports properties */
		foreach ($attrs as $key=>$value) $this->$key=$value;	

    // webget geometry
    $this->geometry = explode(',',$this->geometry);
    $this->left     = $this->geometry[0];
    $this->top      = $this->geometry[1];
    $this->width    = $this->geometry[2];
    $this->height   = $this->geometry[3];
	}
	
	function __flush (&$_)	
	{
    /* flow control server event */
    eval($this->onflush);

    /* no paint switch */    
    if ($this->nopaint) return;

		/* apply local styles */
		$_->ROOT->set_local_style('draw_color',$this->draw_color);
		$_->ROOT->set_local_style('fill_color',$this->fill_color);
		$_->ROOT->set_local_style('line_width',$this->line_width);

    /* setup local coordinates */
		$left	= $this->left + $this->parent->left;
		$top	= $this->top  + $this->parent->top;

		/* paint the rectangle */
		$_->ROOT->fpdf->Rect($left,$top,$this->width,$this->height,$this->style);

		/* restore parent styles */
		$_->ROOT->restore_style('draw_color');
		$_->ROOT->restore_style('fill_color');
		$_->ROOT->restore_style('line_width');
	}
}
?>