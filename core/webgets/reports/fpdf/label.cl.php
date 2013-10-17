<?php
class fpdf_label
{
  function __construct (&$_, $attrs)
  {
    /* imports properties */
		foreach ($attrs as $key=>$value) $this->$key=$value;	

    // webget geometry
    $this->geometry = explode(',',$this->geometry);
    $this->left     = $this->geometry[0];
    $this->top      = $this->geometry[1];
    $this->width    = $this->geometry[2];
    $this->height   = $this->geometry[3];
  }
  
  
  function __flush (&$_)  
  {
    /* flow control server event */
    eval($this->onflush);

    /* no paint switch */    
    if ($this->nopaint) return;
                                               

    /* apply local styles */
    $_->ROOT->set_local_style('text_color',$this->text_color);
    $_->ROOT->set_local_style('draw_color',$this->draw_color);
    $_->ROOT->set_local_style('fill_color',$this->fill_color);
    $_->ROOT->set_local_style('font_family',$this->font_family);
    $_->ROOT->set_local_style('font_style',$this->font_style);
    $_->ROOT->set_local_style('font_size',$this->font_size);
    $_->ROOT->set_local_style('line_width',$this->line_width);

    /* set caption depending by the presence of 'field' property */
    if($this->field){
      $field        = explode(',', $this->field);
      $field_format = ($this->field_format ? $this->field_format : '%s');

      foreach($field as $key => $param) {
        $param       = explode(':', $param);
        $field[$key] = &array_get_nested
                       ($_->webgets[$param[0]]->current_record, $param[1]);           
      }

      $caption = vsprintf($field_format, $field);    
    }
    
    else $caption = $this->caption;

    /* setup local coordinates */
  		$left	= $this->left + $this->parent->left;
  		$top	= $this->top  + $this->parent->top;
    $_->ROOT->fpdf->SetXY($left,$top);                                          

    /* sets toration */
    if($this->rotation != 0) $_->ROOT->fpdf->Rotate($this->rotation);

    /* paints multicell label */
    $_->ROOT->fpdf->MultiCell(
      $this->width,
      $this->height,
      utf8_decode($caption),
      ($_->ROOT->get_local_style('line_width')>0 ? '1' : '0'),
      $this->align,
      ($this->fill_color ? true : false)
    );

    // restore previous styles
    $_->ROOT->restore_style('text_color');
    $_->ROOT->restore_style('draw_color');
    $_->ROOT->restore_style('fill_color');
    $_->ROOT->restore_style('font_family');
    $_->ROOT->restore_style('font_style');
    $_->ROOT->restore_style('font_size');
    $_->ROOT->restore_style('line_width');
    $_->ROOT->fpdf->Rotate(0);
  }
}
?>