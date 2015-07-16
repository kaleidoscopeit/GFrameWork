<?php
class pack_stack
{
  public $req_attribs = array(
    'style',
    'class',
    'preset'
  );

	function __define(&$_)
	{
 	}

	function __flush(&$_)
	{
		if(!isset($this->preset)) $this->preset = 1;
    $this->attributes['preset'] = $this->preset;

    /* builds syles */
    $style  = (isset($this->style) ? $this->style : '');
    $boxing = (isset($this->boxing) ? $this->boxing : '');

    $this->attributes['class'] = 'w0130 '
      . $_->ROOT->boxing($boxing)
      . $_->ROOT->style_registry_add($style)
      . $this->class;

    /* builds code */
		$_->buffer[] = '<div wid="0130" '
                 . $_->ROOT->format_html_attributes($this)
                 . '> ';

    /* flushes children */
		$this->count = 1;

    gfwk_flush_children($this, 'pack_stackelm');

		$_->buffer[] = '</div>';
	}
}
?>
