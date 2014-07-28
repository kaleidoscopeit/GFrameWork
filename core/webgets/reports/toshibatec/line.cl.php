<?php
class reports_toshibatec_line
{
  public $req_attribs = array(
    'left',
    'top',
    'width',
    'height',
    'thickness'
  );
  
  function __define(&$_)
  {
    /* sets the default values */
    $default                = array();
    $default['thickness'][] = "1";

    foreach ($default as $key => $value)
      foreach ($value as $local)
      if ($local != null && !isset($this->$key)) $this->$key=$local;
   }
 
    
  function __flush (&$_)  
  {
    $loffset = $this->parent->hpitch * ($this->parent->clabel - 1);

    if((int)$this->thickness > 9)
      die("Thickness out of range in 'reports_toshibatec_box'");
    
    $_->buffer[] = '{LC;'
                 . sprintf("%04d", round(($this->left + $loffset) * 10)) . ','
                 . sprintf("%04d", round($this->top * 10)) . ','
                 . sprintf("%04d", 
                     round(($this->left + $loffset + $this->width) * 10)) . ','
                 . sprintf("%04d",
                     round(($this->top + $this->height) * 10)) . ','
                 . '0,'
                 . $this->thickness
                 . '|}';
  }
}
?>
