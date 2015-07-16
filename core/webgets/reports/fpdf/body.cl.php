<?php
class reports_fpdf_body
{
  public $req_attribs = array(
    'geometry',
    'columns',
    'rows',
    'pages',
    'text_color',
    'draw_color',
    'fill_color',
    'font_family',
    'font_style',
    'font_size'
  );

  function __define(&$_)
  {
    // webget geometry
    $this->geometry = explode(',',$this->geometry);
    $this->left     = $this->geometry[0];
    $this->top      = $this->geometry[1];
    $this->width    = $this->geometry[2];
    $this->height   = $this->geometry[3];

    // Set default values
    $default               = array();
    $default['columns'][]  = 1;
    $default['rows'][]     = 1;
    $default['left'][]     = "0";
    $default['top'][]      = "0";
    $default['width'][]    = "100%";
    $default['height'][]   = "100%";

    foreach ($default as $key => $value)
      foreach ($value as $local)
        if ($local != null && !isset($this->$key)) $this->$key=$local;
  }

  function __flush(&$_)
  {
    /* apply local styles */
    $_->ROOT->set_local_style($this);

    /* fake result set in order to output at least one page */
    if(!isset($this->result_set)) $this->result_set[] = '';

    /* count records, record per page, number of pages */
    $num_records  = count($this->result_set);
    $pag_records  = $this->rows*$this->columns;
    $num_pages    = intval(($num_records/$pag_records)+.99);

    /* reset page and record pointers */
    $pag_pointer  = 0;
    $rec_pointer  = 0;

    /* Starts page/rows/columns iterator */
    while($pag_pointer < $num_pages){
      // Starts a new page
      $this->parent->NewPage($_);

      /* calculate local geometry */
      $_->ROOT->calc_real_geometry($this);

      /* setup local coordinates */
      $this->offsetLeft = $this->pxleft + $this->parent->marginLeft;
      $this->offsetTop  = $this->pxtop  + $this->parent->marginTop;

      /* cell width and cell height based upon columns/rows number
      and body width/height */
      $cell_width   = $this->pxwidth/$this->columns;
      $line_height  = $this->pxheight/$this->rows;

      $cel_pointer          = 0;                 // reset record pointer
      $this->cellOffsetLeft = $this->offsetLeft; // reset subcell reference offset x
      $this->cellOffsetTop  = $this->offsetTop;  // reset subcell reference offset y

      /* single record iterator */
      while ($cel_pointer < $pag_records) {
        for ($icol=0;$icol<$this->columns;$icol++){
          foreach ((array) @$this->childs as $child){
            $this->current_record = $this->result_set[$rec_pointer];
            gfwk_flush_children($this, 'reports_fpdf_cell');
          }
          $rec_pointer++;                   // set next record pointer
          $cel_pointer++;                   // set next cell
          $this->cellOffsetLeft += $cell_width;         // set cell reference offset x
        }

        $this->cellOffsetLeft  = $this->offsetLeft;     // reset cell reference offset x
        $this->cellOffsetTop  += $line_height;          // set cell reference offset y
      }

      $pag_pointer++;                       // set next page

    }

    /* restore parent styles */
    $_->ROOT->restore_style();
  }
}
?>
