<?php
class fpdf_chapter
{
	function __construct (&$_, $attrs)
	{
    /* imports properties */
		foreach ($attrs as $key=>$value) $this->$key=$value;
	}
	
	function __flush (&$_)	
	{
    /* flow control server event */
    eval($this->onflush);

    /* no paint switch */    
    if ($this->nopaint) return; 

		/* apply local styles */
		$_->ROOT->set_local_style('text_color',$this->text_color);
		$_->ROOT->set_local_style('draw_color',$this->draw_color);
		$_->ROOT->set_local_style('fill_color',$this->fill_color);
		$_->ROOT->set_local_style('font_family',$this->font_family);
		$_->ROOT->set_local_style('font_style',$this->font_style);
		$_->ROOT->set_local_style('font_size',$this->font_size);
		$_->ROOT->set_local_style('line_width',$this->border_width);

		/* Setup local coordinates */
		$this->left += $this->parent->offset[0];	
		$this->top  += $this->parent->offset[1];

    /* flushes 'fpdf_body' */
		foreach ((array) @$this->childs as  $child)
			if (get_class($child)=='fpdf_body') $child->__flush($_);

		/* restore parent styles */
		$_->ROOT->restore_style('text_color');
		$_->ROOT->restore_style('draw_color');
		$_->ROOT->restore_style('fill_color');
		$_->ROOT->restore_style('font_family');
		$_->ROOT->restore_style('font_style');
		$_->ROOT->restore_style('font_size');
		$_->ROOT->restore_style('line_width');
	}

	function NewPage (&$_)
	{
		/* add a new page */
		$_->ROOT->fpdf->AddPage($this->orientation);

		/* paint page mask */
		foreach ((array) @$this->childs as  $child)
			if (get_class($child)=='fpdf_mask') $child->__flush($_);
	}
}
?>