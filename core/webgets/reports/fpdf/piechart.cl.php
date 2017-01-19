<?php
class reports_fpdf_piechart
{
  public $req_attribs = array(
    'geometry',                   // left,top,width as radius
    'labels',                     // labels enabled (1,0)
    'labels_position',            // position from the center (1 = radius)
    'start_angle',                // default : "0" (sin = 1) (0-359 degrees)
    'direction',                  // default : "CW"          (CW, CCW)
    'data',                       // accepts an array of subarray with :
                                  //   label --> label
                                  //   value --> numeric value (in %)
                                  //   color --> RED,GREEN,BLUE
    'field',

    /* common document flow attributes */
    'text_color',
    'draw_color',
    'font_family',
    'font_style',
    'font_size',
    'line_width',                 // thickness (default as parent)
  );

  function __define(&$_)
  {
    // Set default values
    $default = array();

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

    $default['start_angle'][] = "0";
    $default['direction'][] = "CW";
    $default['labels_position'][] = "0.7";

    foreach ($default as $key => $value)
      foreach ($value as $local)
        if ($local !== null && !isset($this->$key)) $this->$key=$local;
  }

  function __flush (&$_)
  {
    /* apply local styles */
    $_->ROOT->set_local_style($this);

    /* setup local coordinates */
    $left = $this->left + $this->parent->left;
    $top  = $this->top  + $this->parent->top;

    //Sectors
    $angleStart = $this->start_angle;
    $angleEnd = 0;

    foreach($this->data as $val) {
      $colors = explode(',', $val[2]);
      $angle  = $val[1] * 3.6;

      if ($angle != 0) {
        $angleEnd = $angleStart + $angle;
        $_->ROOT->fpdf->SetFillColor($colors[0], $colors[1], $colors[2]);
        $_->ROOT->fpdf->Sector($left, $top, $this->width/2, $angleStart, $angleEnd);


        /* Paint labels */
        $diff_left = $left+sin(deg2rad($angleStart+$angle/2))
                   * $this->labels_position
                   * $this->width/2;
        $diff_top  = $top-cos(deg2rad($angleStart+$angle/2))
                   * $this->labels_position
                   * $this->width/2;

        $_->ROOT->fpdf->SetXY($diff_left -
          $_->ROOT->fpdf->GetStringWidth($val[0])/2, $diff_top);

        $_->ROOT->fpdf->Write(0, $val[0] );

//        $_->ROOT->fpdf->Line($left, $top,$diff_left,$diff_top);
        $angleStart += $angle;
      }
    }

    if ($angleEnd != 360) {
        $_->ROOT->fpdf->SetFillColor(255, 255, 255);
       // $_->ROOT->fpdf->Sector($left, $top, $this->width/2, $angleStart, 360);
    }

    /* restore parent styles, Void locally set styles */
    $_->ROOT->restore_style();
  }
}
?>
