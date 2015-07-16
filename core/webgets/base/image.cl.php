<?php
class base_image
{
  public $req_attribs = array(
    'style',
    'class',
    'field',
    'field_format',
    'src'
  );

  function __define(&$_)
  {
  }


  function __flush(&$_)
  {
    /* sets src depending by the presence of 'field' property */
    if(isset($this->field)) {
      $field        = explode(',', $this->field);
      $field_format = ($this->field_format ? $this->field_format : '');

      foreach($field as $key => $param) {
        $param       = explode(':', $param);

        /* if no record on server resultset send fields definition to client */
        if(!isset($_->webgets[$param[0]]->current_record))
          $cfields[] = $field[$key];

        $field[$key] = &array_get_nested
                       ($_->webgets[$param[0]]->current_record, $param[1]);
      }

      $src = preg_replace_callback(
        '/\{(\d+)\}/',
        function($match) use ($field) {
          return ($field[$match[1]] != null ? $field[$match[1]] : $match[0]);
        },
        $field_format
      );
    }

    else if(isset($this->src)) {
      $src = $this->src;
    }

    else {
      $src = '';
    }

    /* enable client field definition */
    if(isset($cfields)) $cfields = 'field="' . implode(',', $cfields) .
    '" field_format="' . $field_format . '" ';

    else $cfields = "";

    /* builds syles */
    $style  = (isset($this->style) ? $this->style : '');
    $boxing = (isset($this->boxing) ? $this->boxing : '');

    /* checks if the size of the image was given. If not, resets at the
       natural image dimensions */
    if($boxing != 'false' && $boxing != '') $size = explode(',', $boxing);

    $width  = isset($size[0]) ? $size[0] : '';
    $height = isset($size[1]) ? $size[1] : '';

    if(is_file($src)){
      $imagesize = getimagesize($src);
      $width     = ($width  != '' ? $width  : $imagesize[0]."px");
      $height    = ($height != '' ? $height : $imagesize[1]."px");
    }

    $css_style  = $_->ROOT->boxing($boxing, $width, $height)
                . $_->ROOT->style_registry_add($style)
                . (isset($this->class) ? $this->class : '');

    if($css_style!="") $css_style = 'class="' . $css_style . '" ';

    /* builds code */
    $_->buffer[] = '<img wid="0020" src="' . $src . '" '
                 . $_->ROOT->format_html_attributes($this)
                 . $css_style
                 . $cfields
                 . '/>';
  }
}
?>
