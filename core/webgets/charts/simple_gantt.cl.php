<?php
class simple_gantt {
	function __construct(&$_, $attrs)
	{

		// sets the properties from the XML tag
		foreach ($attrs as $key=>$value) $this->$key=$value;
		
		// flow control server event		
 		eval($this->ondefine);
 	}
	
	
	function __flush(&$_)
	{		
		// flow control server event
		eval($this->onflush);

		// skips the rendering of this webget and its childs
		if ($this->nopaint) return;

		$_->buffer .=	'<div id="'.$this->id.'" style="overflow:hidden;'.$this->style.";".
								$_->webgets['root']->boxing($this->boxing).';" '.
				 				$_->webgets['root']->format_html_events($this).
								'class = "'.($this->boxing == null ? 'g-default' : '' ).$this->class.'" '.
				 				'> ';

		if ($this->childs)
			foreach ($this->childs as  $child)
				$child->__flush(&$_);

		$_->buffer .= '</div>';
	}	
}
?>