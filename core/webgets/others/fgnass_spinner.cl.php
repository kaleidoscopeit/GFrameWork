<?php
class others_fgnass_spinner
{
  public $req_attribs = array(
  );
  
  function __define(&$_)
  {
  }
	
	function __flush(&$_)
	{
    /* builds syles */
    $css_style = $_->ROOT->boxing($this->boxing)
               . $_->ROOT->style_registry_add('overflow:auto;' . $this->style)
               . $this->class;
                 
    if($css_style!="") $css_style = 'class="' . $css_style . '" ';
    
		$_->buffer[] = '<div type="fgnass_spinner" '
								 . $_->ROOT->format_html_attributes($this)
				 				 . '> ';

		$_->buffer .= '</div>';
	}	
}
?>