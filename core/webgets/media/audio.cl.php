<?php
class media_audio {
	function __construct ( &$_gide, $attrs ) {

		// sets the properties from the XML tag
		foreach (  $attrs as $key=>$value ) $this->$key=$value;

		// flow control server event	
 		eval ( $this->ondefine );
 	}
	
	function __flush ( &$_gide ) {
		
		// flow control server event
		eval( $this->onflush );

		// skips the rendering of this webget and its childs
		if ( $this->nopaint ) return;

		$gide->buffer .=	'<audio id="'.$this->id.'" '.
								($this->src ? 'src="'.$this->src.'" ' : '').
								' >';

		$gide->buffer .= '</audio>';
	}	
}
?>