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
 *                depends on the 'padding' attribute. Possible decoration
 *                are the following :
 *
 *                B -> Black normal character
 *                W -> Reversed character; white on black
 *                F -> Text fit in a box
 *                C -> Double strike through character
 *
 * padding      : comma separated width and height surround space in dots (1-99)
 * alignment    : [L]eft, [C]enter, [R]ight
 * font         : two types of fonts exists "true type" and "non true type"
                  True Type doesn't support numbering, alignement and W,F,C
                  styles, then related passed paramenters (padding, alignment
                  and increment) will be ignored.

 * caption      : text data to be written (max 255 characters)
 * increment    : increment step. The letters in the text will be removed
 *                and remaining digit will be treated as an unique number
 *                to be incremented
 * field        : field/fields linked to the data source in the root iterator
 * field_format : mask to be applied to the data obtained from fields
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
    'caption',
    'increment',
    'field',
    'field_format'
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

    foreach ($default as $key => $caption)
      foreach ($caption as $local)
      if ($local !== null && !isset($this->$key)) $this->$key=$local;
  }


  function __flush (&$_)
  {
    /* iteration loop sensing */
    if(!isset(_w('root')->counters['textfield'])) {
      _w('root')->counters['textfield'] = 1;
    }

    /* text field limit reached */
    if(_w('root')->counters['textfield'] > 99)
      die('Number of text fields exceeds the allowed number (100)');

    /* positioning */
    $left = sprintf("%04d", round(($this->left + $this->parent->offsetLeft) * 10));
    $top  = sprintf("%04d", round(($this->top + $this->parent->offsetTop) * 10));
    $fwdt = sprintf("%04d", round($this->width * 10));
    $fhgt = sprintf("%04d", round($this->height * 10));

    /* set caption depending by the presence of 'field' property */
    if(isset($this->field)){
      $field        = explode(',', $this->field);
      $field_format = (isset($this->field_format) ? $this->field_format : '{0}');

      foreach($field as $key => $param) {
        $param       = explode(':', $param);

        /* if no record on server resultset or forcing sending field to client
         * send fields definition to client */
        if(!isset(_w($param[0])->current_record) || isset($this->send_field))
          $cfields[] = $field[$key];

        $field[$key] = array_get_nested
                       (_w($param[0])->current_record, $param[1]);

      }

      $caption = preg_replace_callback(
        '/\{(\d+)\}/',
        function($match) use ($field) {
          return $field[$match[1]];
        },
        $field_format
      );
    }

    /* else get caption and try to compute the auto increment */
    else if (isset($this->increment)) {
      $increment = str_split($this->increment);

      if(is_numeric($increment[0]))
        $inc_direction = '+';

      else
        $inc_direction = array_shift($increment);

      $increment = (int)implode('', $increment);
      $row_incr  = $increment * _w('root')->labels;
      $row_incr  = $inc_direction . sprintf("%010d", $row_incr);

      $caption = $this->calc_increment($this->caption,
                              (_w('root')->clabel - 1) * $increment);
    }

    /* simply put the caption content as is */
    else $caption = $this->caption;


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

    /* non true type fonts */
    if(!is_numeric($this->font)) {

      $_->buffer[] = '{PV'
                   . sprintf("%02d", _w('root')->counters['textfield']) . ';'
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
                   . '=' . $caption
                   . '|}';
    }

    /* true type fonts (NOT FULLY TESTED )*/
    else {
      $_->buffer[] = '{PV'
                   . sprintf("%02d", _w('root')->counters['textfield']) . ';'
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
                   . '=' . $caption
                   . '|}';
    }

    _w('root')->counters['textfield']++;

  }

  function calc_increment($string, $increment)
  {
    $string = str_split($string);
    $number = array_map(
      function($caption){if(is_numeric($caption)) return $caption;},
      $string);

    $length = sprintf("%02d",count($number));
    $number = (int)implode('', $number);
    $number += $increment;
    $number = str_split(sprintf("%0". $length . "d", $number));

    $string = array_reverse($string);
    foreach($string as $key => $caption){
      if(is_numeric($caption)) $string[$key] = array_pop($number);
    }

    return implode('', array_reverse($string));
  }
}
?>
