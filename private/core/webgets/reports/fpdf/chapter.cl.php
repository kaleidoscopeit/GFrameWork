<?php
class reports_fpdf_chapter
{
  public $req_attribs = array(
    'orientation',
    'page_size',
    'margins',
    /* common document flow attributes */
    'text_color',
    'draw_color',
    'fill_color',
    'font_family',
    'font_style',
    'font_size',
    'line_width'
  );

	function __define(&$_)
	{
    /* Set default values */
    $default = array();

    /* queue webget margins if sets through the XML */
    if(isset($this->margins)) {
      $this->margins = explode(',', $this->margins);
      $default['marginLeft'][]   = isset($this->margins[0]) ? $this->margins[0] : NULL;
      $default['marginTop'][]    = isset($this->margins[1]) ? $this->margins[1] : NULL;
      $default['marginRight'][]  = isset($this->margins[2]) ? $this->margins[2] : NULL;
      $default['marginBottom'][] = isset($this->margins[3]) ? $this->margins[3] : NULL;
    }

    /* then sets default margins */
    $default['marginLeft'][]   = "10";
    $default['marginTop'][]    = "10";
    $default['marginRight'][]  = "10";
    $default['marginBottom'][] = "10";

    foreach ($default as $key => $value)
      foreach ($value as $local)
        if ($local !== null && !isset($this->$key)) $this->$key=$local;

	}

	function __flush (&$_)
	{
		/* apply local styles */
		$_->ROOT->set_local_style($this);

    /* flushes 'fpdf_body' */
    gfwk_flush_children($this, 'reports_fpdf_body');

		/* restore parent styles */
		$_->ROOT->restore_style($this);
	}

	function NewPage (&$_)
	{
		/* add a new page */
		$_->ROOT->fpdf->AddPage($this->orientation, @$this->page_size);

    /* build margins */
    $this->pxwidth = $_->ROOT->fpdf->GetPageWidth() - $this->marginLeft - $this->marginRight;
    $this->pxheight = $_->ROOT->fpdf->GetPageHeight() - $this->marginTop - $this->marginBottom;

		/* paint page mask */
		gfwk_flush_children($this, 'reports_fpdf_mask');
	}
}
?>
