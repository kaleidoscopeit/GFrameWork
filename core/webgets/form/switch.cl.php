<?php
class form_switch
{
  public $req_attribs = array(
    'style',
    'class',
    'disabled',
    'labels'
  );
  
  function __define(&$_)
  {
    /* Set default values */
    $t              = array();
    $t['labels'][]  = 'ON,OFF';

    foreach ($t as $key => $value)
      foreach ($value as $local)
        if ($local != null && !$this->$key) $this->$key=$local;
        

  }
  
  function __flush (&$_)
  {
    if(isset($this->attributes['id']))
      $name = 'name="' . $this->attributes['id'] .'" ';

    /* builds labels */
    $labels = explode(',',$this->labels);
    
    /* builds syles */  
    $css_style = 'class="w0290 '
               . $_->ROOT->boxing($this->boxing)
               . $_->ROOT->style_registry_add($this->style)
               . $this->class
               . '" ';
                 
    /* builds code */
    $_->buffer[] = '<div id="' . $this->id . '" wid="0290" '
                 . ($this->disabled ? 'disabled="disabled" ' : '')
                 . $css_style
                 . $_->ROOT->format_html_attributes($this)
                 .  '>';
    $_->buffer[] = '<input type="text" value="" '
                 . $name
                 . '></input>';
    $_->buffer[] = '<span>';
    $_->buffer[] = '<span>' . $labels[0] . '</span>';
    $_->buffer[] = '<span>' . $labels[1] . '</span>';
    $_->buffer[] = '</span>';
    $_->buffer[] = '<div>';
    $_->buffer[] = '<button type="button" disabled></button>';
    $_->buffer[] = '</div>';
    $_->buffer[] = '<div></div>';
    $_->buffer[] = '</div>';
  }  
}
?>


