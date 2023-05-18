<?php
class reports_fpdf_datagrid
{
  public $req_attribs = array(
    'geometry',
    'columns',
    'rows',
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

    foreach ($default as $key => $value)
      foreach ($value as $local)
        if ($local !== null && !isset($this->$key)) $this->$key=$local;
  }

  function __flush(&$_)
  {
    /* apply local styles */
    $_->ROOT->set_local_style($this);

    /* calculate local geometry */
    $_->ROOT->calc_real_geometry($this);

    /* setup local offset */
    $this->offsetLeft	= $this->pxleft + $this->parent->offsetLeft;
    $this->offsetTop	= $this->pxtop  + $this->parent->offsetTop;

    /* gets rows and columns if not explicitly declared */
    if (!isset($this->colums))
      $this->columns = count($this->result_set[0]);

    if (!isset($this->rows))
      $this->rows = count($this->result_set);

    /* cell width and cell height based upon columns/rows number
       and body width/height */
    $cell_width   = $this->pxwidth/$this->columns;
    $line_height  = $this->pxheight/$this->rows;

    /* reset record pointer */
    $rec_pointer  = 0;

    $cols_width  = array();
    $rows_height = array();

    $this->curr_row = 0;
    $this->curr_col = 0;
    $this->cellOffsetTop = $this->offsetTop;

    for ($irow=0; $irow<$this->rows; $irow++) {
      $this->cellOffsetLeft = $this->offsetLeft;
      $this->curr_row = $irow;

      for ($icol=0; $icol<$this->columns; $icol++) {

        $this->curr_col = $icol;
        $this->current_record['value'] = $this->result_set[$irow][$icol];

        gfwk_flush_children($this, 'reports_fpdf_cell');

        if(!isset($rows_height[$irow]))
          $rows_height[$irow] = $line_height;

        if(!isset($cols_widths[$icol]))
          $cols_width[$icol] = $cell_width;

        $rec_pointer++;                         // set next record pointer
        $cel_pointer++;                         // set next cell
        $this->cellOffsetLeft += $cols_width[$icol];  // set cell reference offset x
      }

      $this->cellOffsetTop += $rows_height[$irow];
    }

    /* restore parent styles */
    $_->ROOT->restore_style($this);
  }
}
?>
