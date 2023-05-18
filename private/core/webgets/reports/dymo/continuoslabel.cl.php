<?php
/* Units twips
 * 1 twip = 1/1440 inch
 * 1440 twips = 1 inch
 * 566,929 twips = 1 cm
 */

class reports_dymo_continuoslabel
{
  public $req_attribs = array(
    'orientation',
    'papername',
    'lengthmode',
    'labellength'
  );

  function __define(&$_)
  {
    /* sets ROOT placeholder */
    $_->ROOT = $this;

    /* sets the default values */
    $default                  = array();
    $default['orientation'][] = "L";
    $default['papername'][]   = "9mm";
    $default['lengthmode'][]  = "Auto";

    foreach ($default as $key => $value)
      foreach ($value as $local)
      if ($local !== null && !isset($this->$key)) $this->$key=$local;
   }


  function __flush (&$_)
  {
    $orientation = ($this->orientation == "L" ? "Landscape" : "Portrait" );

    $_->buffer[] = '﻿<?xml version="1.0" encoding="utf-8"?>';
    $_->buffer[] = '<ContinuousLabel Version="8.0" Units="twips">';
    $_->buffer[] = '<PaperOrientation>' . $orientation . '</PaperOrientation>';
    $_->buffer[] = '<Id>Tape</Id>';
    $_->buffer[] = '<PaperName>' . $this->papername . '</PaperName>';
    $_->buffer[] = '<LengthMode>' . $this->lengthmode . '</LengthMode>';
    $_->buffer[] = '<LabelLength>5669.25</LabelLength>';

    /* flushes children */
    gfwk_flush_children($this);

    $_->buffer[] = '</ContinuousLabel>';

    // Set appropriate output
    header('Content-type: text/plain');
    header('Content-Disposition: attachment; filename="dymo.label"');

  }
}
?>
