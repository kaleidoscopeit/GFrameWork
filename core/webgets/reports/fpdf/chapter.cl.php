<?php
class reports_fpdf_chapter
{
  public $req_attribs = array(
    'orientation',
    'page_size',
    'text_color',
    'draw_color',
    'fill_color',
    'font_family',
    'font_style',
    'font_size',
    'border_width'
  );
  
	function __define(&$_)
	{
	}  
	
	function __flush (&$_)	
	{  
		/* apply local styles */
  	$_->ROOT->set_local_style('text_color',@$this->text_color);
    $_->ROOT->set_local_style('draw_color',@$this->draw_color);
    $_->ROOT->set_local_style('fill_color',@$this->fill_color);
	  $_->ROOT->set_local_style('font_family',@$this->font_family);
	  $_->ROOT->set_local_style('font_style',@$this->font_style);
	  $_->ROOT->set_local_style('font_size',@$this->font_size);
	  $_->ROOT->set_local_style('line_width',@$this->line_width);
    $_->ROOT->update_styles();
    
		/* Setup local coordinates */
		$this->left = $this->parent->offset[0];	
		$this->top  = $this->parent->offset[1];

    /* flushes 'fpdf_body' */
    gfwk_flush_children($this, 'reports_fpdf_body');

		/* restore parent styles */
		$_->ROOT->restore_style('text_color');
		$_->ROOT->restore_style('draw_color');
		$_->ROOT->restore_style('fill_color');
		$_->ROOT->restore_style('font_family');
		$_->ROOT->restore_style('font_style');
		$_->ROOT->restore_style('font_size');
		$_->ROOT->restore_style('line_width');
    $_->ROOT->update_styles();
	}

	function NewPage (&$_)
	{
		/* add a new page */
		$_->ROOT->fpdf->AddPage($this->orientation, @$this->page_size);

		/* paint page mask */
		gfwk_flush_children($this, 'reports_fpdf_mask');
	}
}
?>