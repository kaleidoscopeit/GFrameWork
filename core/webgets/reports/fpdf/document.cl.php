<?php
class fpdf_document
{
	function __construct (&$_, $attrs)
	{
    /* imports properties */
		foreach ($attrs as $key=>$value) $this->$key=$value;	

     /* sets ROOT placeholder */
 		$_->ROOT = $this;
 		
 		/* setup fpdf libray */
		require($_->CORE_PATH.'/3rd/FPDF/fpdf.php');
		$this->fpdf=new FPDF();
		
		/* declare default styles */
 		$this->style = array();
		$this->style['text_color'][]	= '0';
		$this->style['draw_color'][]	= '0';
		$this->style['fill_color'][]	= '255';
		$this->style['font_family'][]	= 'arial';
		$this->style['font_style'][]	= '';
		$this->style['font_size'][]		= '10';
		$this->style['line_width'][]	= '0';

    /* set styles */
		$this->update_styles();
		$this->fpdf->SetMargins(0,0);
 	}
 
  	
	function __flush (&$_)	
	{
    /* flow control server event */
    eval($this->onflush);

    /* no paint switch */    
    if ($this->nopaint) return; 

		/* default offset if not specified */
		$this->offset = explode(',', $this->offset);
		if(!isset($this->offset[0]))$this->offset[0] = '10';
		if(!isset($this->offset[1]))$this->offset[1] = '10';

		/* default font */
		$this->fpdf->AddFont('arialn');
		$this->fpdf->SetAutoPageBreak('',5);

    /* flushes children */
		foreach ((array) @$this->childs as $child) $child->__flush($_);

		/* trigger the pdf document creation */
		$this->fpdf->Output();
	}


	function update_styles ()
	{
		$text_color = explode(',', $this->style['text_color'][0]);
		$this->fpdf->SetTextColor($text_color[0], $text_color[1], $text_color[2]);
		
		$draw_color = explode(',', $this->style['draw_color'][0]);
		$this->fpdf->SetDrawColor($draw_color[0], $draw_color[1], $draw_color[2]);

		$fill_color = explode(',', $this->style['fill_color'][0]);
		$this->fpdf->SetFillColor($fill_color[0], $fill_color[1], $fill_color[2]);
				
		$this->fpdf->SetFont($this->style['font_family'][0],
		                     $this->style['font_style'][0],
		                     $this->style['font_size'][0]);
		
		$this->fpdf->SetLineWidth($this->style['line_width'][0]);	
	}

		
	function set_local_style ($style_name, $style_value)
	{
		if($style_name == null) return false;
		if($style_value === null) $style_value = $this->style[$style_name][0];
		array_unshift($this->style[$style_name], $style_value);
		$this->update_styles();
	}


	function get_local_style ($style_name)
	{
		if($style_name == null) return false;
		return $this->style[$style_name][0];
	}

		
	function restore_style ($style_name)
	{
		if($style_name == null) return false;
		array_shift($this->style[$style_name]);
		$this->update_styles();
	}	
}
?>
