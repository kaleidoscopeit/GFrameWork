<?php
class data_table
{
  function __construct (&$_, $attrs)
  {
    /* imports properties */
    foreach ($attrs as $key=>$value) $this->$key=$value;
    
    /* Set default values */
    $default                   = array();
    $default['columns'][]      = "1";
    $default['current_page'][] = $_->GET[$this->id.'Ofst'];
    $default['current_page'][] = "1";
    
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

    /* Sets an empty default record if the result_set is empty */
    if(count($this->result_set) < 1)$this->result_set = array(false);
    $this->max_records = count($this->result_set);  
    $this->page_pointer = 0;
    
    /* If a specific row quantity is sets, the paging will be enabled */
    if($this->rows) {
      $this->page_records  = ($this->max_records < $this->rows * $this->columns? 
                             $this->max_records : $this->rows * $this->columns);
      $this->start_pointer = (($this->current_page - 1) * $this->page_records) ;
      $this->max_pages     = intval((count($this->result_set)/
                             $this->page_records)+.99);
    }
    
    else {
      $this->page_records  = $this->max_records;
      $this->start_pointer = 0;
      $this->max_pages     = 1;
    }    

    /* builds syles */
    $css_style = 'class="w0300 '.$_->ROOT->boxing($this->boxing).
                 $_->ROOT->style_registry_add($this->style).
                 $this->class.'" ';

    /* builds code */
    $_->buffer .=  '<div id="'.$this->id.'" wid="0300" '.$css_style.
                   ($this->send_to_client ? 'result_set="'.
                   clean_xml(json_encode($this->result_set)).'" ':'').
                   $_->ROOT->format_html_events($this).
                   '>';

    /* Starts rows/columns iterator */
    while ($this->page_pointer < $this->page_records) {
      for ($icol=0;$icol<$this->columns;$icol++){
        if ($this->page_pointer < $this->page_records){          
          foreach ((array) @$this->childs as $child){
            
            /* make current record available inside the cell and also in table*/
            $child->index          = $this->start_pointer+$this->page_pointer;
            $child->current_record = $this->result_set[$child->index];
            $this->current_record  = &$child->current_record;    
                  
            if (get_class($child) == 'data_tablecell' && 
                $child->check_show($_)) {
              $child->__flush($_);
              break;
            } 
          }
        }

        /* increment record pointer */
        $this->page_pointer++;
      }
    }
  
    $_->buffer .= '</div>';
  }  
}
?>