<?php
class data_table
{
  public $req_attribs = array(
    'style',
    'class',
    'max_records',
    'result_set',
    'rowheight',
    'data',
    'send_to_client',
    'columns',
    'rows'
  );

  function __define (&$_)
  {
    /* Set default values */
    $default                   = array();
    $default['columns'][]      = "1";
    $default['current_page'][] =
      isset($_->GET[$this->id.'Ofst']) ? $_->GET[$this->id.'Ofst'] : null;
    $default['current_page'][] = "1";

    foreach ($default as $key => $value)
      foreach ($value as $local)
        if ($local != null && !isset($this->$key)) $this->$key=$local;
  }

  function __flush(&$_)
  {
    /* Sets an empty default record if the result_set is empty */
    if(count($this->result_set) < 1) $this->result_set = array('empty');
    $this->max_records = count($this->result_set);
    $this->page_pointer = 0;

    /* If a specific row quantity is sets, the paging will be enabled */
    if(isset($this->rows)) {
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
    $style  = (isset($this->style) ? $this->style : '');
    $boxing = (isset($this->boxing) ? $this->boxing : '');
    $class  = (isset($this->class) ? $this->class : '');

    $this->attributes['class'] = 'w0300 '
                               . $_->ROOT->boxing($boxing)
                               . $_->ROOT->style_registry_add($style)
                               . $class;

    /* builds code */
    $_->buffer[] = '<div wid="0300" '
                 . (isset($this->send_to_client) ? 'result_set="'
                 . clean_xml(json_encode($this->result_set)).'" ':'')
                 . $_->ROOT->format_html_attributes($this)
                 . '>';


    /* Rows/columns iterator */
    while ($this->page_pointer < $this->page_records) {
      for ($icol=0;$icol<$this->columns;$icol++){
        if ($this->page_pointer < $this->page_records){
          foreach ((array) @$this->childs as $child){
            if (get_class($child) == 'data_tablecell') {
              gfwk_flush($child);
            }
          }
        }

        /* increment record pointer */
        $this->page_pointer++;
      }
    }

    $_->buffer[] = '</div>';
  }
}
?>
