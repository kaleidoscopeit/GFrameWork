<?php
class pack_stack {
  
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
		
		$preset = ($this->preset ? $this->preset : 0);

    /* builds syles */
		$css_style       = $_->ROOT->boxing($this->boxing).
		                   $_->ROOT->style_registry_add($this->style).
		                   $this->class;
		if($css_style!="") $css_style = 'class="'.$css_style.'" ';
		
    /* builds code */
		$_->buffer .=	'<div wid="0130" id="'.$this->id.'" '.$css_style.
								  ($this->mode ? 'mode="'.$this->mode.'" ':'mode="loop" ').
								  'preset="'.$preset.'" >';


    /* flushes children */
		$count = 0;

    foreach ((array) @$this->childs as $key => $child)  {
      if (get_class($child)=='pack_stackelm') {
        $child->index = $count+1;
        if ($preset == $child->index-1) $child->preset = true;
        else $child->preset = false;
        $child->__flush($_);
        $count++;
      }
    }

		$_->buffer .= '</div>';
	}
}
?>