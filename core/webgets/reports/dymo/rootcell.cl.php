<?php
class reports_dymo_rootcell
{
  public $req_attribs = array(
    'margin',
    'length',
    'lengthmode',
    'borderwidth',
    'borderstyle',
    'bordercolor'
 
  );
  
  function __define(&$_)
  {
    /* sets the default values */
    $default                  = array();
    $default['margin'][] = "0,0,0,0";
    $default['length'][]   = "10";
    $default['lengthmode'][]  = "Auto";
    $default['borderwidth'][]  = "0";
    $default['borderstyle'][]  = "Solid";
    $default['bordercolor'][]  = "255,0,0,0";

  

    foreach ($default as $key => $value)
      foreach ($value as $local)
      if ($local != null && !$this->$key) $this->$key=$local;
   }
 
    
  function __flush (&$_)  
  {
    $_->buffer[] = '<RootCell>';

    /* flushes children */
    gfwk_flush_children($this);

    $bordercolor = explode(',', $this->bordercolor);
    $margin = explode(',', $this->margin);
    
    $_->buffer[] = '<ObjectMargin Left="' . $margin[0]
                 . '" Top="' . $margin[1]
                 . '" Right="' . $margin[2]
                 . '" Bottom="' . $margin[3]
                 . '" />';
    $_->buffer[] = '<Length>' . $this->length . '</Length>';
    $_->buffer[] = '<LengthMode>' . $this->lengthmode . '</LengthMode>';
    $_->buffer[] = '<BorderWidth>' . $this->borderwidth . '</BorderWidth>';
    $_->buffer[] = '<BorderStyle>' . $this->borderstyle . '</BorderStyle>';
    $_->buffer[] = '<BorderColor Alpha="' . $bordercolor[0]
                 . '" Red="' . $bordercolor[1]
                 . '" Green="' . $bordercolor[2]
                 . '" Blue="' . $bordercolor[3]
                 . '" />';
    $_->buffer[] = '</RootCell>';

  }
}
?>
