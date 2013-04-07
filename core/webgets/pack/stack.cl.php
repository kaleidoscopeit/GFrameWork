<?php
class pack_stack {
  
	function __construct(&$_, $attrs)
	{
    /* imports properties */
    register_attributes($this, $attrs, array(
      'style','class'));
		
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
		$css_style = $_->ROOT->boxing($this->boxing)
							 . $_->ROOT->style_registry_add($this->style)
							 . $this->class;
							 
		if($css_style!="") $css_style = 'class="w0130 ' . $css_style . '" ';
		
    /* builds code */
		$_->buffer[] = '<div wid="0130" '
                 . $_->ROOT->format_html_events($this)
                 . $_->ROOT->format_html_attributes($this)
                 . $css_style . '> ';


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

		$_->buffer[] = '</div>';
	}
}
?>