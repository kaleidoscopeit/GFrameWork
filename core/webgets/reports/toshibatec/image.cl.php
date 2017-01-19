<?php

/* Write a bitmap image in a label
 *
 * Units are of 1 mm
 *
 * left         : position from left edge
 * top          : position from top edge
 * src          : path of the image (B/W BMP)
 */
class reports_toshibatec_image
{
  public $req_attribs = array(
    'left',
    'top',
    'src'
  );

  function __define(&$_)
  {
    /* sets the default values */
    $default                = array();
    $default['left'][]      = "0";
    $default['top'][]       = "0";

    foreach ($default as $key => $caption)
      foreach ($caption as $local)
      if ($local !== null && !isset($this->$key)) $this->$key=$local;
  }


  function __flush (&$_)
  {
    /* positioning */
    $left = sprintf("%04d", round(($this->left + $this->parent->offsetLeft) * 10));
    $top  = sprintf("%04d", round(($this->top + $this->parent->offsetTop) * 10));

    /* open the image file (only B/W BMP is supported) */
//    $imgh = fopen($this->src, "rb");

    /* check if the given image is compatible */
/*    $data = fread($imgh, 2);
    if($data != "BM") die("Given image is no a bitmap");

    $image = imagecreatefrombmp($this->src);
    $width = imagesx($image);
    $height = imagesy($image);
    $colors = array();

    for ($y = 0; $y < $height; $y++)
    {
        for ($x = 0; $x < $width; $x++)
        {
            $rgb = imagecolorat($image, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;

            $black = ($r == 0 && $g == 0 && $b == 0);
            $colors[$x][$y] = $black;
        }
    }



fseek($imgh,)
    var_dump($data);
die;*/
    /* get image size */

  //  print_r($file_content);die;


    /* do printing */
    $_->buffer[] = '{SG;'
                 . $left . ','
                 . $top . ','
                 . '0000,0000,2,'
                 . file_get_contents($this->src)
                 . '|}';

  }
}
?>
