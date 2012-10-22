<?php
class others_fgnass_spinner {
	function __construct ( &$_, $attrs ) {

		// sets the properties from the XML tag
		foreach (  $attrs as $key=>$value ) $this->$key=$value;
		
		// flow control server event		
 		eval ( $this->ondefine );
 	}
	
	function __flush(&$_)
	{
		
		// flow control server event
		eval ( $this->onflush );

		// skips the rendering of this webget and its childs
		if ( $this->nopaint ) return;

		$_->buffer .=	'<div type="fgnass_spinner" id="'.$this->id.'" style="overflow:auto;"'.
								$_->webgets['root']->boxing( $this->boxing ).';" '.
				 				'> ';

		$_->buffer .= '</div>';
	}	
}
?>