<?php
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
 * ribbon       : specify if ink ribbon is present (true or false)
 * density      : on time density adjustment (min -10 max +10)
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
    'ribbon',
    'density',
    'quantity',
    'cut_interval',
    'sensor',
    'speed',
    'orientation',
    'mirror'
   
  );
  
  function __define(&$_)
  {
    // FORCE DEBUG
    //error_reporting( E_ALL ); 
    //error_reporting(!E_NOTICE);
    //ini_set('display_errors',1);

    /* sets ROOT placeholder */
    $_->ROOT = $this;
     
    /* sets the default values */
    $default                    = array();
    $default['labels'][]        = "1";
    $default['width'][]         = $this->row_width;
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
      if ($local != null && !isset($this->$key)) $this->$key=$local;

    $this->textfield_idx = 1;
   }
 
    
  function __flush (&$_)
  {
    if($this->labels > 1)
      $this->hpitch  = ($this->row_width - ($this->width * $this->labels))
                     / ($this->labels -1) + $this->width;
    
    else $this->hpitch = $this->row_width;

    /* set label sizing */
    $_->buffer[] = '{D'
                 . sprintf("%04d", round($this->row_height * 10)) . ','
                 . sprintf("%04d", round($this->row_width * 10)) . ','
                 . sprintf("%04d", round($this->height * 10))
                 . '|}';

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
    
    /* fake field in order to make everything work good */
    $_->buffer[] = '{PV00;0001,0001,0030,0030,B,00,B,P3=.|}';

    for ($i = 0; $i < $this->labels; $i++) {

      $this->clabel = $i + 1;

      /* flushes children */
      gfwk_flush_children($this);
    }

    /* calculate quantity */    
    $quantity = ceil((int)$this->quantity / (int)$this->labels);

    if($quantity < 1) $quantity = 1;

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

    
    // Set appropriate output
    header('Content-type: text/teclabel');
    header('Content-Disposition: attachment; filename="toshiba.label"');

  }
}
?>

