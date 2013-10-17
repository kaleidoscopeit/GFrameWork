<?php
class paint_svg
{
  public $req_attribs = array(
    'style',
    'class',
    'preset'
  );
    
	function __define(&$_)
	{
 	}
	
	function __flush(&$_)
	{
    /* builds syles */
    $this->attributes['class'] = $_->ROOT->boxing($this->boxing)
                               . $_->ROOT->style_registry_add($this->style)
                               . $this->class;

		/* builds code */
		$_->buffer[] = '<svg '
				 			   . $_->ROOT->format_html_attributes($this)
				 			   . '>';
		
		$_->buffer[] = '<ellipse cx="0" cy="100%" rx="100%" ry="100%" />';

		$_->buffer[] = '</svg>';
	}	
}
?>