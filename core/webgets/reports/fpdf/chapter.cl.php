<?php
class reports_fpdf_chapter
{
  public $req_attribs = array(
    'orientation',
    'page_size',
    'margins',
    'text_color',
    'draw_color',
    'fill_color',
    'font_family',
    'font_style',
    'font_size',
  );

	function __define(&$_)
	{
    /* grab the offset */
    $this->margins      = explode(',', $this->margins);
    $this->marginLeft   = $this->margins[0];
    $this->marginTop    = $this->margins[1];
    $this->marginRight  = $this->margins[2];
    $this->marginBottom = $this->margins[3];

    /* Set default values */
    $default = array();
    $default['marginLeft'][]   = "10";
    $default['marginTop'][]    = "10";
    $default['marginRight'][]  = "10";
    $default['marginBottom'][] = "10";

    foreach ($default as $key => $value)
      foreach ($value as $local)
        if ($local != null && !$this->$key) $this->$key=$local;

	}

	function __flush (&$_)
	{
		/* apply local styles */
		$_->ROOT->set_local_style($this);

    /* flushes 'fpdf_body' */
    gfwk_flush_children($this, 'reports_fpdf_body');

		/* restore parent styles */
		$_->ROOT->restore_style();
	}

	function NewPage (&$_)
	{
		/* add a new page */
		$_->ROOT->fpdf->AddPage($this->orientation, @$this->page_size);

        /* build margins */
        $this->pxwidth = $_->ROOT->fpdf->w - $this->marginLeft - $this->marginRight;
        $this->pxheight = $_->ROOT->fpdf->h - $this->marginTop - $this->marginBottom;

		/* paint page mask */
		gfwk_flush_children($this, 'reports_fpdf_mask');
	}
}
?>
