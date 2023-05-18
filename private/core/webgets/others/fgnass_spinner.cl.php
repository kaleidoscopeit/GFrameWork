<?php
class fgnass_spin
{
  public $req_attribs = array(
  );

  function __define(&$_)
  {
  }

	function __flush(&$_)
	{
    /* builds syles */
    $style  = (isset($this->style) ? $this->style : '');
    $boxing = (isset($this->boxing) ? $this->boxing : '');

    $css_style = $_->ROOT->boxing($boxing)
               . $_->ROOT->style_registry_add('overflow:auto;' . $style)
               . $this->class;

    if($css_style!="") $css_style = 'class="' . $css_style . '" ';

		$_->buffer[] = '<div type="fgnass_spinner" '
								 . $_->ROOT->format_html_attributes($this)
				 				 . '> ';

		$_->buffer .= '</div>';
	}
}
?>
