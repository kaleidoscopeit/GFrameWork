<?php
class form_entry {
  
	function __construct(&$_, $attrs)
	{
    /* imports properties */
		foreach ($attrs as $key=>$value) $this->$key=$value;
		
    /* flow control server event */
    eval($this->ondefine);
 	}
	
	function __flush(&$_)
	{	
    /* flow control server event */
    eval($this->onflush);

    /* no paint switch */    
    if ($this->nopaint) return;

		$boxing = 'class="'.$_->ROOT->boxing($this->boxing).'" ';
		$css_style = $_->ROOT->style_registry_add($this->style).$this->class;
		if($css_style!="") $css_style = 'class="'.$css_style.'" ';
		
		$_->buffer .=	'<div wid="0210" '.$boxing.'>'.
							'<input name="'.$this->id.'" id="'.$this->id.'" wid="0211" type="text"'.$css_style.
		    				($this->disabled ? 'disabled ' : '').
		    				($this->readonly ? 'readonly ' : '').
		   				($this->tip ? 'title="'.$this->tip.'" ' : '').
						  	($this->value ? 'value="'.$this->value.'" ' : '').
							( $this->tabindex ? 'tabindex="'.$this->tabindex.'" ' : '').
		    				$_->ROOT->format_html_events($this, array('all')).
							'>'.
							'</div>';
	}
}
?>