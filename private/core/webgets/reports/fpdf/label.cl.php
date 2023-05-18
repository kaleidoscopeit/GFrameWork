<?php
class reports_fpdf_label
{
  public $req_attribs = array(
    'geometry',                                                                    // position
    'align',
    'rotation',
    'caption',
    'caption_condition',
    'field',
    'field_format',
    'multiline',
    'border',
    'fill_background',

    /* common document flow attributes */
    'text_color',
    'draw_color',
    'fill_color',
    'font_family',
    'font_style',
    'font_size',
    'line_width'
  );


  function __define(&$_)
  {
    /* Set default values */
    $default               = array();

    /* queue webget geometry if sets through the XML */
    if(isset($this->geometry)) {
      $this->geometry = explode(',', $this->geometry);
      $default['left'][]   = isset($this->geometry[0]) ? $this->geometry[0] : NULL;
      $default['top'][]    = isset($this->geometry[1]) ? $this->geometry[1] : NULL;
      $default['width'][]  = isset($this->geometry[2]) ? $this->geometry[2] : NULL;
      $default['height'][] = isset($this->geometry[3]) ? $this->geometry[3] : NULL;
    }

    /* then sets default geometry */
    $default['left'][]     = "0";
    $default['top'][]      = "0";
    $default['width'][]    = "100%";
    $default['height'][]   = "100%";

    $default['rotation'][] = "0";
    $default['align'][]    = "left";
    $default['border'][]   = "0";
    $default['fill_background'][] = FALSE;

    $default['caption'][]   = "";

    foreach ($default as $key => $value)
      foreach ($value as $local)
        if ($local !== null && !isset($this->$key)) $this->$key=$local;

  }

  function __flush (&$_)
  {
    /* apply local styles */
    $_->ROOT->set_local_style($this);

    /* set caption depending by the presence of 'field' property */
    if(isset($this->field)){
      $field        = explode(',', $this->field);
      $field_format = (isset($this->field_format) ? $this->field_format : '{0}');

      foreach($field as $key => $param) {
        $param       = explode(':', $param);
        $field[$key] = array_get_nested
                       ($_->webgets[$param[0]]->current_record, $param[1]);
      }

      $caption = preg_replace_callback(
        '/\{(\d+)\}/',
        function($match) use ($field) {
          return $field[$match[1]];
        },
        $field_format
      );

      /* force caption property content in case the "caption condition"
         is satisfied */
      if(isset($this->caption_condition))
        if(eval($this->caption_condition))
          $caption = $this->caption;
    }

    else $caption = $this->caption;
    //echo $caption . "\n";

    /* calculate local geometry */
    $_->ROOT->calc_real_geometry($this);

    /* setup local coordinates */
  	$left	= $this->pxleft + $this->parent->offsetLeft;
  	$top	= $this->pxtop  + $this->parent->offsetTop;

    $_->ROOT->fpdf->SetXY($left,$top);

    /* sets rotation */
    $_->ROOT->fpdf->Rotate($this->rotation);

    $fill_background = ($this->fill_background == TRUE
      && $_->ROOT->get_local_style('fill_color') != "" ? TRUE : FALSE);

    if(isset($this->multiline)) {
      /* paints multicell label */
      $_->ROOT->fpdf->MultiCell(
        $this->pxwidth,
        $this->pxheight,
        utf8_decode($caption),
        $this->border,
        $this->align,
        $fill_background
      );
    }

    else {
      /* paints a cell label */
      $_->ROOT->fpdf->Cell(
        $this->pxwidth,
        $this->pxheight,
        utf8_decode($caption),
        $this->border,
        1,
        $this->align,
        $fill_background
      );
    }
    
    /* restore parent styles, Void locally set styles */
    $_->ROOT->restore_style($this);
    $_->ROOT->fpdf->Rotate(0);
  }
}
?>
