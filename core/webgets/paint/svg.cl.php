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
    $style  = (isset($this->style) ? $this->style : '');
    $boxing = (isset($this->boxing) ? $this->boxing : '');

    $this->attributes['class'] = $_->ROOT->boxing($boxing)
                               . $_->ROOT->style_registry_add($style)
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
