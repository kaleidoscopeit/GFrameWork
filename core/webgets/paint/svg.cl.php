<?php
class paint_svg {
	function __construct(&$_, $attrs)
	{
		foreach ($attrs as $key=>$value) $this->$key=$value;
 		eval($this->ondefine);
 	}
	
	function __flush(&$_)
	{
		eval($this->onflush);
		if ($this->nopaint)return;

		$css_style = $_->ROOT->boxing($this->boxing).$_->ROOT->style_registry_add($this->style).$this->class;
		if($css_style!="") $css_style = 'class="'.$css_style.'" ';


		$_->buffer[] = '<svg id="' . $this->id . '" ' . $css_style
				 			   . $_->ROOT->format_html_events($this, array('mouse'))
				 			   . '>';
		$_->buffer[] = '<ellipse cx="0" cy="100%" rx="100%" ry="100%" />';

		//foreach ((array) @$this->childs as  $child) $child->__flush(&$_);

		$_->buffer[] = '</svg>';
	}	
}
?>