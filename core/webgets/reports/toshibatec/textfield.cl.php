<?php

/* Write a field of text in a label
 * 
 * Units are of 1 mm
 * 
 * left         : position from left edge
 * top          : position from top edge
 * width        : width of a single character
 * height       : height of characters in a line  
 * spacing      : character to character spacing in dots (format +|-nnn)
 * rotation     : 0 -> 0°, 1 -> 90°, 2 -> 180°, 3 -> 270°
 * style        : Character style. The decorated area surrounding the string
 *                depends on the 'padding' attribute. Possible decorationa
 *                are the following :
 * 
 *                B -> Black normal character
 *                W -> Reversed character; white on black
 *                F -> Text fit in a box
 *                C -> Double strike through character
 * 
 * padding      : comma separated width and height surround space in dots (1-99)
 * alignment    : [L]eft, [C]enter, [R]ight
 * font         : 
 * value        : text data to be written (max 255 characters)
 * increment    : increment step. The letters in the text will be removed 
 *                and remaining digit will be treated as an unique number
 *                to be incremented
 */
class reports_toshibatec_textfield
{
  public $req_attribs = array(
    'left',
    'top',
    'width',
    'height',
    'spacing',
    'rotation',
    'style',
    'padding',
    'alignment',
    'font',
    'value',
    'increment'    
  );
  
  function __define(&$_)
  {
    /* sets the default values */
    $default                = array();
    $default['width'][]     = "2.5";
    $default['height'][]    = "2.5";
    $default['rotation'][]  = "0";
    $default['style'][]     = "B";
    $default['padding'][]   = "1,1";
    $default['alignment'][] = "L";
    $default['font'][]      = "A";
    $default['value'][]     = "";
        
    foreach ($default as $key => $value)
      foreach ($value as $local)
      if ($local != null && !isset($this->$key)) $this->$key=$local;  
  }
 
    
  function __flush (&$_)  
  {

    /* positioning */
    $loffset = $this->parent->hpitch * ($this->parent->clabel - 1);
    $left = sprintf("%04d", round(($this->left + $loffset) * 10));
    $top  = sprintf("%04d", round($this->top * 10));
    $fwdt = sprintf("%04d", round($this->width * 10));
    $fhgt = sprintf("%04d", round($this->height * 10));

    /* auto increment section */
    if (isset($this->increment)) {
      $increment = str_split($this->increment);

      if(is_numeric($increment[0]))
        $inc_direction = '+';

      else
        $inc_direction = array_shift($increment);

      $increment = (int)implode('', $increment);
      $row_incr  = $increment * $this->parent->labels;
      $row_incr  = $inc_direction . sprintf("%010d", $row_incr);

      $value = $this->calc_increment($this->value,
                              ($this->parent->clabel - 1) * $increment);
    }

    else $value = $this->value;


    /* style */
    $padding = explode(',', $this->padding);
    $style = $this->style
           . ($this->style != 'B' ? sprintf("%02d", $padding[0]) : '')
           . ($this->style == 'W' || $this->style == 'F' ?
               sprintf("%02d", $padding[1]) : '');

    /* alignement */
    $alignment = ($this->alignment == 'L' ? 'P1' : '')
               . ($this->alignment == 'C' ? 'P2' : '')
               . ($this->alignment == 'R' ? 'P3' : '');

    /* character spacing */
    if (isset($this->spacing)) {
      $spacing = str_split($this->spacing);

      if(is_numeric($spacing[0]))
        $spacing_dir = '+';

      else
        $spacing_dir = array_shift($spacing);

      $spacing = (int)implode('', $spacing);
      $spacing = $spacing_dir . sprintf("%03d", $spacing);
    }
    
    /* do printing */
    if($this->font == 'A' || $this->font == 'B' ) {
     
      $_->buffer[] = '{PV'
                   . sprintf("%02d", $this->parent->textfield_idx) . ';'
                   . $left . ','
                   . $top . ','
                   . $fwdt . ','
                   . $fhgt . ','
                   . $this->font . ','
                   . (isset($spacing) ? $spacing . ',' : '')
                   . $this->rotation . $this->rotation . ','
                   . $style . ','
                   . (isset($row_incr) ? $row_incr . ',' : '')
                   . 'Z04,'
                   . $alignment
                   . '=' . $value
                   . '|}';
    }
    
    else {
      $_->buffer[] = '{PV'
                   . sprintf("%02d", $this->parent->textfield_idx) . ';'
                   . $left . ','
                   . $top . ','
                   . $fwdt . ','
                   . $fhgt . ','
                   . $this->font . ','
                   . '0,' // force fonts to be loaded from flash rom
                   . (isset($spacing) ? $spacing . ',' : '')
                   . $this->rotation . $this->rotation . ','
                   . $style
                   . ',L04'
                   . '=' . $value
                   . '|}';      
    }
    
    $this->parent->textfield_idx ++;

  }

  function calc_increment($string, $increment)
  {
    $string = str_split($string);
    $number = array_map(
      function($value){if(is_numeric($value)) return $value;},
      $string);

    $length = sprintf("%02d",count($number));
    $number = (int)implode('', $number);
    $number += $increment;
    $number = str_split(sprintf("%0". $length . "d", $number));

    $string = array_reverse($string);
    foreach($string as $key => $value){
      if(is_numeric($value)) $string[$key] = array_pop($number);
    }

    return implode('', array_reverse($string));    
  }
}
?>
