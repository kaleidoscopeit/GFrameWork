<?php
class form_list
{
  function __construct(&$_, $attrs)
  {
    /* imports properties */
    foreach ($attrs as $key=>$value) $this->$key=$value;
    
    /* flow control server event */
    eval($this->ondefine);
   }
  
  function __flush(&$_)
  {
    /* flow control server event */
    eval($this->onflush);

    /* no paint switch */    
    if ($this->nopaint) return;

    // Import static values from the view
    if ($this->values) {
      $this->items['values'] = explode('|', $this->values);
      $this->items['labels'] = explode('|', $this->labels);
      if (count($this->items['labels']) != count($this->items['values']))
        $this->items['labels'] = $this->items['values'];
    }

    /* builds syles */
    $wclass    = 'class="w02B0 '.$_->ROOT->boxing($this->boxing).'" ';
                 
    $css_style = $_->ROOT->style_registry_add($this->style).
                 $this->class;

    if($css_style!="") $css_style = 'class="'.$css_style.'" ';


    $item_style = $_->ROOT->style_registry_add($this->item_style).
                  $this->item_class;
                  
    if($item_style!="") $item_style = 'class="'.$item_style.'" ';


    /* builds code */    
    $_->buffer .= '<div wid="02B0" '.$wclass.' opt'.$item_style.'>'.
                  '<select name="'.$this->id.'" id="'.$this->id.'" '.
                  'multiple '.$css_style.                  
                  ($this->disabled ? 'disabled="true" ' : '').
                  $_->ROOT->format_html_events($this).
                  ($this->tip ? 'title="'.$this->tip.'" ' : '').'>';


    
    if($this->items['values']) {
      foreach ($this->items['values'] as $key => $value) {
        $_->buffer .= '<option value="'.$value.'" '.$item_style.
                      ($this->default == $value ? 'selected' : '').'>'.
                      $this->items['labels'][$key].
                      '</option>';
      }        
    }    
        
    $_->buffer .= '</select></div>';
  }  



  function items_insert($value, $label = -1, $index = null)
  {
    $index = $index === null ? count($this->items['values']) : $index;
    $index = intval($index);

    $this->items['values'] = array_merge(
      ($this->items['values'] ?
        array_slice($this->items['values'], 0, $index) : array()),        
      array("$value"),      
      ($this->items['values'] ?
        array_slice($this->items['values'], $index) : array()));
       
    $this->items['labels'] = array_merge(
      ($this->items['labels'] ? 
        array_slice($this->items['labels'], 0, $index) : array()),
      array(($label != -1 ? $label : $value)),
      ($this->items['labels'] ?
        array_slice($this->items['labels'], $index) : array()));
  }

  // delete item by value or by index
  function items_remove($item)
  {
    if (count($this->items['values']) == 0) return false;

    if (is_int($item) && $this->items['values'][$item]) {
      unset($this->items['values'][$item]);
      unset($this->items['labels'][$item]);
      return true;
    }
    
    if (is_string($item)) {
      foreach ($this->items['values'] as $key => $value) {
        if ($value === $item) {
          unset($this->items['values'][$key]);
          unset( $this->items['labels'][$key]);
          return true;
        }
      }
    }
    
    return false;    
  }
}
?>