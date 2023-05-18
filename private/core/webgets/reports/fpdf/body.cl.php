<?php
class reports_fpdf_body
{
  public $req_attribs = array(
    'geometry',
    'columns',
    'rows',
    'direction',
    'fill_page',      /* draws all cells despite the records are ended */

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
    // Set default values
    $default                = array();
    $default['columns'][]   = 1;
    $default['rows'][]      = 1;
    $default['direction'][] = "ltr";

    /* queue webget geometry if sets through the XML */
    if(isset($this->geometry)) {
      $this->geometry = explode(',', $this->geometry);
      $default['left'][]   = isset($this->geometry[0]) ? $this->geometry[0] : NULL;
      $default['top'][]    = isset($this->geometry[1]) ? $this->geometry[1] : NULL;
      $default['width'][]  = isset($this->geometry[2]) ? $this->geometry[2] : NULL;
      $default['height'][] = isset($this->geometry[3]) ? $this->geometry[3] : NULL;
    }

    /* then sets default geometry */
    $default['left'][]   = "0";
    $default['top'][]    = "0";
    $default['width'][]  = "100%";
    $default['height'][] = "100%";

    foreach ($default as $key => $value)
      foreach ($value as $local)
        if ($local !== null && !isset($this->$key)) $this->$key=$local;
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

    /* reset pointers */
    $pag_pointer  = 0;
    $rec_pointer  = 0;
    $this->current_col = 0;
    $this->current_row = 0;

    /* Starts page/rows/columns iterator */
    while($pag_pointer < $num_pages){
      // Starts a new page
      $this->parent->NewPage($_);

      /* calculate local geometry */
      $_->ROOT->calc_real_geometry($this);

      /* setup local coordinates */
      $this->offsetLeft = $this->pxleft + $this->parent->marginLeft;
      $this->offsetTop  = $this->pxtop  + $this->parent->marginTop;

      /* reset pxwidth and pxheight as virtual dimensions for the childrend
         cell. Width and height are based upon columns/rows number and body
         width/height */
      $this->pxwidth   = $this->pxwidth/$this->columns;
      $this->pxheight  = $this->pxheight/$this->rows;

      /* move offseLeft to the last cell offset in case of rtl direction */
      if($this->direction == "rtl" )
          $this->offsetLeft  += $this->pxwidth * ($this->columns - 1);

      $cel_pointer           = 0;                 // reset record pointer
      $this->cellOffsetLeft  = $this->offsetLeft; // reset subcell reference offset x
      $this->cellOffsetTop   = $this->offsetTop;  // reset subcell reference offset y

      /* single record iterator */
      while ($cel_pointer < $pag_records) {
        /* row iterator */
        for ($icol=0;$icol<$this->columns;$icol++){
          /* flush child cells */
          foreach ((array) @$this->childs as $child){
            /* stop the execution if the recordset is finished */
            if(!isset($this->result_set[$rec_pointer])
              && !isset($this->fill_page)) break;

            $this->current_record = $this->result_set[$rec_pointer];
            gfwk_flush_children($this, 'reports_fpdf_cell');
          }

          $rec_pointer++;                             // set next record pointer
          $this->current_col = $icol;
          $cel_pointer++;                             // set next cell

          /* set cell reference offset x depending by direction */
          if($this->direction == "rtl" ) {
            $this->cellOffsetLeft -= $this->pxwidth;
          }
          else {
            $this->cellOffsetLeft += $this->pxwidth;
          }
        }

        $this->current_row++;
        $this->cellOffsetLeft  = $this->offsetLeft;   // reset cell reference offset x
        $this->cellOffsetTop  += $this->pxheight;     // set cell reference offset y
      }

      $pag_pointer++;                                 // set next page

    }

    /* restore parent styles */
    $_->ROOT->restore_style($this);
  }
}
?>
