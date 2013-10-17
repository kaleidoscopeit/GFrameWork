<?php
class fpdf_cell
{
	function __construct(&$_, $attrs)
	{
    /* imports properties */
    foreach ($attrs as $key=>$value) $this->$key=$value;
 		
		// Set default values
		$t              = array();
		$t['show_if'][] = 'true';

		foreach ($t as $key => $value)
			foreach ($value as $local)
				if ($local != null && !$this->$key)
					$this->$key=$local;
 	}

	function __flush(&$_)
	{
    /* flow control server event */
    eval($this->onflush);

    /* no paint switch */    
    if ($this->nopaint) return;  
		
		foreach ((array) @$this->childs as  $child) $child->__flush($_);
	}
	
	function check_show(&$_){
		return(eval('return('.$this->show_if.');'));
	}
}
?>