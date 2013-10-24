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
    'font_size',
    'line_width'
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
    $t = array();
    $t['columns'][] = 1;
    $t['rows'][] = 1;

    foreach ($t as $key => $value)
      foreach ($value as $local)
        if ($local != null && !isset($this->$key)) $this->$key=$local;
  }

  function __flush(&$_)  
  {
    /* apply local styles */
    $_->ROOT->set_local_style('text_color',@$this->text_color);
    $_->ROOT->set_local_style('draw_color',@$this->draw_color);
    $_->ROOT->set_local_style('fill_color',@$this->fill_color);
    $_->ROOT->set_local_style('font_family',@$this->font_family);
    $_->ROOT->set_local_style('font_style',@$this->font_style);
    $_->ROOT->set_local_style('font_size',@$this->font_size);
    $_->ROOT->set_local_style('line_width',@$this->line_width);
    
		/* Setup local coordinates */

    $this->left += $this->parent->left;  
    $this->top  += $this->parent->top;

    /* fake result set in order to output at least one page */
    if(!isset($this->result_set)) $this->result_set[] = '';

    /* cell width and cell height based upon columns/rows number 
       and body width/height */
    $cell_width   = $this->width/$this->columns;
    $line_height  = $this->height/$this->rows;              

    /* count records, record per page, number of pages */
    $num_records  = count($this->result_set);
    $pag_records  = $this->rows*$this->columns;
    $num_pages    = intval(($num_records/$pag_records)+.99);

    /* reset page and record pointers */
    $pag_pointer  = 0;
    $rec_pointer  = 0;

    /* Starts page/rows/columns iterator */
    while($pag_pointer < $num_pages){    
      $this->parent->NewPage($_);           // Starts a new page
      $cel_pointer       = 0;               // reset record pointer
      $this->offset_y    = $this->top;      // reset subcell reference offset y
      $this->offset_x    = $this->left;     // reset subcell reference offset x      

      while ($cel_pointer < $pag_records) {
        for ($icol=0;$icol<$this->columns;$icol++){
          foreach ((array) @$this->childs as $child){
            $this->current_record = $this->result_set[$rec_pointer];
            gfwk_flush_children($this, 'reports_fpdf_cell');
          }
          $rec_pointer++;                   // set next record pointer
          $cel_pointer++;                   // set next cell
          $this->offset_x += $cell_width;         // set cell reference offset x
        }

        $this->offset_x  = $this->left;           // reset cell reference offset x
        $this->offset_y += $line_height;          // set cell reference offset y
      }
      
      $pag_pointer++;                       // set next page
    }

    // restore previous styles
    $_->ROOT->restore_style('text_color');
    $_->ROOT->restore_style('draw_color');
    $_->ROOT->restore_style('fill_color');
    $_->ROOT->restore_style('font_family');
    $_->ROOT->restore_style('font_style');
    $_->ROOT->restore_style('font_size');
    $_->ROOT->restore_style('line_width');
  }
}
?>