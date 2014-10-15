<?php
class reports_fpdf_label
{
  public $req_attribs = array(
    'geometry',                                                                    // position
    'text_color',                                                                  // foreground text color
    'draw_color',                                                                  // border color
    'fill_color',                                                                  // line background color
    'font_family',
    'font_style',
    'font_size',
    'line_width',
    'align',
    'rotation',
    'caption',
    'field',
    'field_format'
  );
  
  
  function __define(&$_)
  {
    if(!isset($this->rotation))$this->rotation = 0;
    
    // webget geometry
    $this->geometry = explode(',',$this->geometry);
    $this->left     = $this->geometry[0];
    $this->top      = $this->geometry[1];
    $this->width    = $this->geometry[2];
    $this->height   = $this->geometry[3];
  }
  
  
  function __flush (&$_)
  {
    /* apply local styles */
    $_->ROOT->set_local_style($this);

    /* sets locally the fill color */
    if(isset($this->fill_color)){
      $fill_color = explode(',', $this->fill_color);
      $_->ROOT->fpdf->SetFillColor($fill_color[0], @$fill_color[1],
                                @$fill_color[2]);
    }
    
    /* set caption depending by the presence of 'field' property */
    if(isset($this->field)){
      $field        = explode(',', $this->field);
      $field_format = (isset($this->field_format) ? $this->field_format : '%s');

      foreach($field as $key => $param) {
        $param       = explode(':', $param);
        $field[$key] = array_get_nested
                       ($_->webgets[$param[0]]->current_record, $param[1]);           
      }

      $caption = vsprintf($field_format, $field);    
    }
    
    else $caption = $this->caption;

    /* setup local coordinates */
  	$left	= $this->left + $this->parent->left;
  	$top	= $this->top  + $this->parent->top;
    $_->ROOT->fpdf->SetXY($left,$top);                                          

    /* sets rotation */
    if($this->rotation != 0) $_->ROOT->fpdf->Rotate($this->rotation);

    /* paints multicell label */
    $_->ROOT->fpdf->MultiCell(
      $this->width,
      $this->height,
      utf8_decode($caption),
      (@$this->line_width>0 ? '1' : '0'),
      $this->align,
      (isset($this->fill_color) ? '1' : '0')
    );

    /* restore parent styles, Void locally set styles */
    $_->ROOT->restore_style();
    $_->ROOT->fpdf->Rotate(0);
  }
}
?>