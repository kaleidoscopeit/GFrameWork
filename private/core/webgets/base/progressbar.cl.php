<?php
// Progress Bar
//
// Properties :
//
//  style -> webget style
//  bar_style -> progress bar style
// orientation -> progress bar orientation and growth direction
//    (LR -> Left-Right,RL -> Right-Left,TB -> Top-Bottom,BT -> Bottom-Top)

class base_progressbar
{
  public $req_attribs = array(
    'style',
    'class',
    'field',
    'field_format',
    'progress',
    'bar_style',
    'orientation'
  );

  function __define(&$_)
  {
    /* sets the default values */
    $default                  = array();
    $default['orientation'][] = "LR";
    $default['progress'][]    = "0";
    $default['bar_style'][]   = "";

    foreach ($default as $key => $value)
      foreach ($value as $local)
      if ($local !== null && !isset($this->$key)) $this->$key=$local;
  }

  function __flush(&$_)
  {
    /* set progress depending by the presence of 'field' property */
    if(isset($this->field)){
      $field        = explode(',', $this->field);
      $field_format = (isset($this->field_format) ? $this->field_format : '');

      foreach($field as $key => $param) {
        $param       = explode(':', $param);

        /* if no record on server resultset send fields definition to client */
        if(!$_->webgets[$param[0]]->current_record) $cfields[] = $field[$key];

        $field[$key] = array_get_nested
          ($_->webgets[$param[0]]->current_record, $param[1]);
      }

      $progress = preg_replace_callback(
        '/\{(\d+)\}/',
        function($match) use ($field) {
          return ($field[$match[1]] != null ? $field[$match[1]] : $match[0]);
        },
        $field_format
      );
    }

    else $progress = $this->progress;

    /* make sure progress is an integer and is rounded */
    $progress = round(intval($progress), 1);

    /* enable client field definition */
    if(isset($cfields)) $cfields = 'field="' . implode(',', $cfields)
                                 . '" field_format="' . $field_format . '" ';

    else $cfields = "";

    
    /* Orientation */
    switch($this->orientation){
      case 'LR':
        $bar_style = $this->bar_style
                   . 'left:0px;height:100%;width:' . $progress . '%;';
        break;

      case 'RL':
        $bar_style = $this->bar_style
                   . 'right:0px;height:100%;width:' . $progress . '%;';
        break;

      case 'TB':
        $bar_style = $this->bar_style
                   . 'top:0px;width:100%;height:' . $progress . '%;';

        break;

      case 'BT':
        $bar_style = $this->bar_style
                   . 'bottom:0px;width:100%;height:' . $progress . '%;';
        break;
    }



    if (!strpos($bar_style,'background-color')) $bar_style = '' . $bar_style;

    /* builds syles */
    $style  = (isset($this->style) ? $this->style : '');
    $boxing = (isset($this->boxing) ? $this->boxing : '');

    $css_style_bar  = ($bar_style != "" ? 'class="'
                    . $_->ROOT->style_registry_add($bar_style) . '" ' : '');

    $css_style  = 'class="w0030 '
                . $_->ROOT->boxing($boxing)
                . $_->ROOT->style_registry_add($style)
                . (isset($this->class) ? $this->class : '')
                . '" ';

    /* builds code */
    $_->buffer[] = '<div wid="0030" ' . $css_style
                 . $_->ROOT->format_html_attributes($this)
                 . ' ornt="' . $this->orientation . '" '
                 . $cfields
                 . '>';

    $_->buffer[] =  '<div ' . $css_style_bar . '></div>';

    gfwk_flush_children($this);

    $_->buffer[] = '</div>';
  }
}
?>
