<?php
class form_text {
  
	function __construct(&$_, $attrs)
	{
    /* imports properties */
		foreach ($attrs as $key=>$value) $this->$key=$value;
		
    /* flow control server event */
    eval($this->ondefine);
 	}
	
	function __flush(&$_ )
	{
    /* flow control server event */
    eval($this->onflush);

    /* no paint switch */    
    if ($this->nopaint) return;

		$boxing = 'class="'.$_->ROOT->boxing($this->boxing).'" ';
		$css_style = $_->ROOT->style_registry_add('resize: none;'.$this->style).$this->class;
		if($css_style!="") $css_style = 'class="'.$css_style.'" ';
		
		$_->buffer .=	'<div wid="0230" '.$boxing.'>'.
								'<textarea name="'.$this->id.'" id="'.$this->id.'" wid="0231" '.$css_style.
								($this->disabled ? 'disabled="true" ' : '').
			    				$_->ROOT->format_html_events($this, array('all')).
			   				($this->tip ? 'title="'.$this->tip.'" ' : '').
								'>'.$this->value.'</textarea>'.
							'</div>';
	}
}
?>