<?php
class reports_fpdf_barcode
{
  public $req_attribs = array(
    'geometry',                   // left,top,width,height
    'color',
    'rotation',
    'data',
    'data_condition',
    'type',                       // barcode type
    'field',
    'field_format'
  );

  function __define(&$_)
  {
    // requires the barcode library
    require_once('lib/standalone/barcode.php');

    // Set default values
    $t = array();

    /* queue webget geometry to if sets through the XML */
    if(isset($this->geometry)) {
      $this->geometry = explode(',', $this->geometry);
      $default['left'][]   = isset($this->geometry[0]) ? $this->geometry[0] : NULL;
      $default['top'][]    = isset($this->geometry[1]) ? $this->geometry[1] : NULL;
      $default['width'][]  = isset($this->geometry[2]) ? $this->geometry[2] : NULL;
      $default['height'][] = isset($this->geometry[3]) ? $this->geometry[3] : NULL;
    }

    $default['type'][]     = "code128noext";
    $default['rotation'][] = "0";
    $default['color'][]    = "000000";

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

      $data = preg_replace_callback(
        '/\{(\d+)\}/',
        function($match) use ($field) {
          return $field[$match[1]];
        },
        $field_format
      );

      /* force caption property content in case the "caption condition"
         is satisfied */
      if(isset($this->data_condition))
        if(eval($this->data_condition))
          $data = $this->data;
    }

    else $data = $this->data;

    /* calculate local geometry */
    $_->ROOT->calc_real_geometry($this);

    /* setup local coordinates */
    $left = $this->pxleft + $this->parent->offsetLeft;
    $top  = $this->pxtop  + $this->parent->offsetTop;

    Barcode::fpdf($_->ROOT->fpdf, $this->color, $left, $top, $this->rotation, $this->type, $data, $this->width, $this->pxheight);

    /* restore parent styles, Void locally set styles */
    $_->ROOT->restore_style();
  }
}
?>
