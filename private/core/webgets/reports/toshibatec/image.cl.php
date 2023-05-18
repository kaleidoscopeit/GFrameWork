<?php

/* Write a bitmap image in a label
 *
 * Units are of 1 mm
 *
 * left         : position from left edge
 * top          : position from top edge
 * src          : path of the image (B/W BMP)
 */
class reports_toshibatec_image
{
  public $req_attribs = array(
    'left',
    'top',
    'src'
  );

  function __define(&$_)
  {
    /* sets the default values */
    $default                = array();
    $default['left'][]      = "0";
    $default['top'][]       = "0";

    foreach ($default as $key => $caption)
      foreach ($caption as $local)
      if ($local !== null && !isset($this->$key)) $this->$key=$local;
  }


  function __flush (&$_)
  {
    /* positioning */
    $left = sprintf("%04d", round(($this->left + $this->parent->offsetLeft) * 10));
    $top  = sprintf("%04d", round(($this->top + $this->parent->offsetTop) * 10));

    /* do printing (only B/W BMP is supported) */
    $_->buffer[] = '{SG;'
                 . $left . ','
                 . $top . ','
                 . '0000,0000,2,'
                 . file_get_contents($this->src)
                 . '|}';

  }
}
?>
