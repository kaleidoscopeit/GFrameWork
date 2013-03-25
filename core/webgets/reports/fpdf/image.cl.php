<?php
class fpdf_image {
	function __construct(&$_, $attrs)
	{
    /* imports properties */
		foreach ($attrs as $key=>$value) $this->$key=$value;	
 		
    // webget geometry
    $this->geometry = explode(',',$this->geometry);
    $this->left     = $this->geometry[0];
    $this->top      = $this->geometry[1];
    $this->width    = $this->geometry[2];
    $this->height   = $this->geometry[3];

    /* flow control server event */
    eval($this->ondefine);
	}
	
	function __flush(&$_)	
	{
    /* flow control server event */
    eval($this->onflush);

    /* no paint switch */    
    if ($this->nopaint) return;

    /* setup local coordinates */
		$left	= $this->left + $this->parent->left;
		$top	= $this->top  + $this->parent->top;

		// Paint image
		$_->ROOT->fpdf->Image($this->url,$left,$top,$this->width,$this->height);
	}
}
?>