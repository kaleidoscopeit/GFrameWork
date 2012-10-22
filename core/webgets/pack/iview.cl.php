<?php
class pack_iview {
  
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
		
		$onload = $this->onload;
		
    /* Enable a reference to te parent View in the contained iframe View
       NOTE : parentView became available only after the onload event in 
       the contained document */       
    if($this->enableparent)
      $onload = 'this.contentWindow.parentView=window;'.$onload;
    
    if($onload)
      $onload = 'onload="'.$onload.'" ';

    /* builds syles */		
		$style       = $_->ROOT->boxing($this->boxing).$this->style;
		$css_style   = $_->ROOT->style_registry_add($style).$this->class;
		if($css_style) $css_style = 'class="'.$css_style.'" ';

    /* builds code */
 		$_->buffer .=	'<div wid="0150" id="'.$this->id.'" '.$css_style.
								($this->view ? 'view="'.$this->view.'" ' : NULL).
								($this->onload ? 'onload="'.$this->onload.'" ' : NULL).
 								($this->normal_class ? 'tcn="'.$this->normal_class.'" ' : NULL).
 								($this->in_class ? 'tci="'.$this->in_class.'" ' : NULL).
 								($this->out_class ? 'tco="'.$this->out_class.'" ' : NULL).
  								($this->trans_class ? 'tct="'.$this->trans_class.'" ' : NULL).
								$_->ROOT->format_html_events($this, array('mouse')).'>'.
								'<iframe></iframe>'.
								'<iframe></iframe>'.
								'<iframe></iframe>'.
				    		'</div>'; 
	}
	
}
?>