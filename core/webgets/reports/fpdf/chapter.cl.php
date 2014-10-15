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
  );
  
	function __define(&$_)
	{
	}  
	
	function __flush (&$_)	
	{  
		/* apply local styles */
		$_->ROOT->set_local_style($this);
    
		/* Setup local coordinates */
		$this->left = $this->parent->offset[0];	
		$this->top  = $this->parent->offset[1];

    /* flushes 'fpdf_body' */
    gfwk_flush_children($this, 'reports_fpdf_body');

		/* restore parent styles */
		$_->ROOT->restore_style();
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