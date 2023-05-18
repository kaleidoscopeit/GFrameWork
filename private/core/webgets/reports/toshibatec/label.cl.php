<?php

// ENABLE DEBUG FOR THIS LIBRARY SET
//ini_set('display_errors',1);

/* Outputs a file to send directly to a Toshiba TEC Labeler
 *
 * Units are of 1 mm
 *
 * Max values for rheight and height depends on the print head version :
 *
 * for 203dpi - max 999.0mm
 * for 300dpi - max 452.2mm
 *
 *
 * row_height   : total height of a row
 * row_width    : total width of a row from left edge of the first label to the
 *                right edge of the last label
 * width        : single label width
 * height       : single label height
 * labels       : labels per row
 * iterator     : use [e]mbedded or [s]oftware iterator for multiple labels
 *                embedded iterator is used only when the "increment" feature
 *                is available for the elements inside the label. Otherwise
 *                use software iterator to print any custom data
 * -----------------------------------------------------------------------------
 * feed_offset  : offset applied to the start of printed are vs label start 
 * 
 *                +50.0mm to -50.0mm
 * 
 * -----------------------------------------------------------------------------
 * ribbon       : specify if ink ribbon is present (true or false)
 * density      : on time density adjustment (min -10 max +10)
 * quantity     : number of labels issued if "embedded" iterator is selected
 * cut_interval : number of row printed every paper cut
 * sensor       : sensor type [r]eflective, [t]ransmissive, [n]o sensor
 * speed        : specific code depending by the following table :
 *
 *                1: 2 inches/sec
 *                2: 2 inches/sec
 *                3: 3 inches/sec
 *                4: 4 inches/sec
 *                5: 5 inches/sec (300dpi 4inches/sec)
 *                6: 5 inches/sec (300dpi 4inches/sec)
 *                7: 5 inches/sec (300dpi 4inches/sec)
 *                8: 5 inches/sec (300dpi 4inches/sec)
 *                9: 5 inches/sec (300dpi 4inches/sec)
 *                A: Max. 2 inches/sec. or 5 inches/sec. (Depending on a
 *                   duplicate printing area setting for duplicate labels.
 *                   Only for the 203 dpi model)
 *                B: Max. 5 inches/sec. (When using duplicate labels and no
 *                   duplicate printing is necessary. Only for the 203 dpi
 *                   model)
 *
 * orientation  : The side of the label will exits from the labeler
 *                0 - the bottom first
 *                1 - the top
 * mirror       : specify if the label has to be mirrored
 *                0 - normal
 *                1 - mirrored
 */

class reports_toshibatec_label
{
  public $req_attribs = array(
    'row_height',
    'row_width',
    'width',
    'height',
    'labels',
    'iterator',
    'feed_offset',
    'ribbon',
    'density',
    'quantity',
    'cut_interval',
    'sensor',
    'speed',
    'orientation',
    'mirror'
  );

  /* counters store for label elements */
  public $counters = array();

  function __define(&$_)
  {
    /* sets ROOT placeholder */
    $_->ROOT = $this;

    /* sets the default values */
    $default                    = array();
    $default['labels'][]        = "1";
    $default['width'][]         = $this->row_width;
    $default['iterator'][]      = "e";
    $default['feed_offset'][]   = "0";
    $default['ribbon'][]        = "false";
    $default['density'][]       = "0";
    $default['quantity'][]      = "1";
    $default['cut_interval'][]  = "0";
    $default['sensor'][]        = "n";
    $default['speed'][]         = "1";
    $default['orientation'][]   = "0";
    $default['mirror'][]        = "0";

    foreach ($default as $key => $value)
      foreach ($value as $local)
      if ($local !== null && !isset($this->$key)) $this->$key=$local;
  }


  function __flush (&$_)
  {
    /* calculate area occupied by one label included the padding */
    if($this->labels > 1)
      $this->hpitch  = ($this->row_width - ($this->width * $this->labels))
                     / ($this->labels - 1) + $this->width;

    else $this->hpitch = $this->row_width;

    /* set the sizing of the whole row of labels */
    $_->buffer[] = '{D'
                 . sprintf("%04d", round($this->row_height * 10)) . ','
                 . sprintf("%04d", round($this->row_width * 10)) . ','
                 . sprintf("%04d", round($this->height * 10))
                 . '|}';

    /* offset */
    $feed_offset = str_split($this->feed_offset);

    if(is_numeric($feed_offset[0]))
      $feed_offset_dir = '+';
    else
      $feed_offset_dir = array_shift($feed_offset);

    $feed_offset = (int)implode('', $feed_offset);
    if($feed_offset > 50) die("Feed offset out of range in 'reports_toshibatec_label'");
    $feed_offset = $feed_offset_dir . sprintf("%03d", $feed_offset * 10);

    /* set offset adjustment */
    $_->buffer[] = '{AX;'
      . $feed_offset . ','
      . '+000,+00|}';

    /* density */
    $density = str_split($this->density);

    if(is_numeric($density[0]))
      $density_dir = '+';
    else
      $density_dir = array_shift($density);

    $density = (int)implode('', $density);
    if($density > 10) die("Density out of range in 'reports_toshibatec_label'");
    $density = $density_dir . sprintf("%02d", $density);

    /* set density fine adjustment */
    $_->buffer[] = '{AY;'
                 . $density . ','
                 . ($this->ribbon == 'false' ? '1' : '0')
                 . '|}';

    /* clear image buffer */
    $_->buffer[] = '{C|}';

    /* fake field in order to make everything works good */
    $_->buffer[] = '{PV00;0001,0001,0030,0030,B,00,B,P3=.|}';

    /* start the main iterator, if the embedded is selected only one iteration
       will be performed otherwise is calculated by the record_set size.
       Furthermore the number of label per issue is calculated */
    if($this->iterator == "s" && isset($this->result_set)) {
      $rows_quantity = floor(count($this->result_set) / $this->labels);
      $quantity = 1;
    }

    else  {
      $rows_quantity = 1;
      $quantity = ceil((int)$this->quantity / (int)$this->labels);
    }

    for ($crow=0;$crow<$rows_quantity;$crow++){
      /* for each label in a row flushes the child webgets */
      for ($i = 0; $i < $this->labels; $i++) {

        /* set the current label index */
        $this->clabel = $i + 1;

        /* set current coordinates */
        $this->offsetTop  = 0;
        $this->offsetLeft = $this->hpitch * $i;

        /* set the current_record */
        $record_pointer = $crow * $this->labels + $i;
        if(isset($this->result_set))
          $this->current_record = $this->result_set[$record_pointer];

        /* flushes children */
        gfwk_flush_children($this);
      }

      /* issue command */
      $_->buffer[] = '{XS;I,'
                   . sprintf("%04d", $quantity) . ','
                   . sprintf("%03d", $this->cut_interval)
                   . ($this->sensor == 'n' ? '0' : '')
                   . ($this->sensor == 'r' ? '1' : '')
                   . ($this->sensor == 't' ? '2' : '')
                   . 'C'
                   . $this->speed
                   . ($this->ribbon == 'false' ? '0' : '1')
                   . bindec($this->mirror . $this->orientation)
                   . '0|}';

      /* clear image buffer */
      $_->buffer[] = '{C|}';

      /* reset the counters store */
      $this->counters = array();
    }

    /* reset feed offset */
    $_->buffer[] = "{AX;+000,+000,+00|}";

    // Set appropriate output
    header('Content-type: text/teclabel');
    header('Content-Disposition: attachment; filename="toshiba.label"');

  }
}
?>
