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
  function __construct(&$_, $attrs)
  {
    /* imports properties */
    foreach ($attrs as $key=>$value) $this->$key=$value;
    
    /* sets the default values */
    $default                  = array();
    $default['orientation'][] = "LR";
    $default['progress'][]    = "0";
 
    foreach ($default as $key => $value)
      foreach ($value as $local)
        if ($local != null && !$this->$key) $this->$key=$local;
           
    /* flow control server event */
    eval($this->ondefine);
   }
  
  function __flush(&$_)
  {
    /* flow control server event */
    eval($this->onflush);

    /* no paint switch */
    if ($this->nopaint) return;


    switch($this->orientation){
      case 'LR':
        $bar_style = $this->bar_style.'left:0px;height:100%;width:'.
                     round($this->progress,1).'%;';
        break;
        
      case 'RL':
        $bar_style = $this->bar_style.'right:0px;height:100%;width:'.
                     round($this->progress,1).'%;';
        break;
        
      case 'TB':
        $bar_style = $this->bar_style.'top:0px;width:100%;height:'.
                     round($this->progress,1).'%;';
        break;
        
      case 'BT':
        $bar_style = $this->bar_style.'bottom:0px;width:100%;height:'.
                     round($this->progress,1).'%;';
        break;
    }

    if (!strpos($bar_style,'background-color'))
      $bar_style = 'background-color:lightgreen;'.$bar_style;

    /* builds syles */
    $css_style_bar = ($bar_style!="" ? 'class="'.
                     $_->ROOT->style_registry_add($bar_style).'" ' : '');
                     
    $css_style     = 'class="w0030 '.$_->ROOT->boxing($this->boxing).
                     $_->ROOT->style_registry_add($this->style).
                     $this->class.'" ';
                     
    /* builds code */
    $_->buffer .= '<div id="'.$this->id.
                  '" wid="0030" ' . $css_style.
                  $_->ROOT->format_html_events($this).
                  'ornt="'.$this->orientation.'">'.
                  '<div '.$css_style_bar.'></div>';
              
    foreach ((array) @$this->childs as  $child) $child->__flush($_);

    $_->buffer .= '</div>';
  }  
}
?>