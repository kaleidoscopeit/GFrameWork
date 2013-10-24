<?php
class reports_fpdf_cell
{
  public $req_attribs = array(
    'show_if'
  );
  
	function __define(&$_)
	{
 	}

  function __preflush(&$_)
  {
    $this->nopaint = NULL;

    $this->left = $this->parent->offset_x;     // set cell left
    $this->top  = $this->parent->offset_y;     // set cell top

    if(eval('return('.$this->show_if.');') != true) $this->nopaint = true;
  }
  
	function __flush(&$_)
	{
	  
		gfwk_flush_children($this);
	}

}
?>