<?php
class reports_dymo_textobject
{
  public $req_attribs = array(
    'string',
  );

  function __define(&$_)
  {
    /* sets the default values */
    $default                  = array();


    foreach ($default as $key => $value)
      foreach ($value as $local)
      if ($local !== null && !isset($this->$key)) $this->$key=$local;
   }


  function __flush (&$_)
  {
    $_->buffer[] = '<TextObject>';
    $_->buffer[] = '<Name>TESTO</Name>';
    $_->buffer[] = '<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />';
    $_->buffer[] = '<BackColor Alpha="0" Red="255" Green="255" Blue="255" />';
    $_->buffer[] = '<LinkedObjectName></LinkedObjectName>';
    $_->buffer[] = '<Rotation>Rotation0</Rotation>';
    $_->buffer[] = '<IsMirrored>False</IsMirrored>';
    $_->buffer[] = '<IsVariable>True</IsVariable>';
    $_->buffer[] = '<HorizontalAlignment>Center</HorizontalAlignment>';
    $_->buffer[] = '<VerticalAlignment>Middle</VerticalAlignment>';
    $_->buffer[] = '<TextFitMode>None</TextFitMode>';
    $_->buffer[] = '<UseFullFontHeight>True</UseFullFontHeight>';
    $_->buffer[] = '<Verticalized>False</Verticalized>';
    $_->buffer[] = '<StyledText>';
    $_->buffer[] = '<Element>';
    $_->buffer[] = '<String>'. $this->string .'</String>';
    $_->buffer[] = '<Attributes>';
    $_->buffer[] = '<Font Family="Franklin Gothic" Size="8" Bold="False" Italic="False" Underline="False" Strikeout="False" />';
    $_->buffer[] = '<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />';
    $_->buffer[] = '</Attributes>';
    $_->buffer[] = '</Element>';
    $_->buffer[] = '</StyledText>';
    $_->buffer[] = '</TextObject>';

  }
}
?>
