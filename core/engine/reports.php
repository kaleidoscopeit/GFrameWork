<?php
// parse and returns a string contains the html code of a view
class _engine_reports {
  function build()
  {
    /* private loopback to the default name of the gide class */ 
    $_=$this;

    $_->webget_enum = 0;
    $_->webget_path = '../core/webgets/reports/';

    /* Check if the view exists */    
    if (!is_file('reports/'.$this->CALL_SOURCE.'.xml')) die("View not found!");

    /* load the code file */
    self::get_codes();

    /* parser initialization */
    $parser = xml_parser_create();
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, false);
    xml_set_element_handler($parser, array('self', "startelm"),
    array('self', "endelm"));

    /* load the xml source */
    $source = file_get_contents('reports/'.$this->CALL_SOURCE.'.xml');

    /* parse the content */
    if (!xml_parse($parser, $source, true))
      die (sprintf(
        "XML error in '%s' : %s at line %d",
        $this->CALL_SOURCE,
        xml_error_string(xml_get_error_code ($parser)),
        xml_get_current_line_number ($parser)
      ));

    xml_parser_free($parser);
    unset($source); 
  
    $_->ROOT->hierarchy = $this->hierarchy;

    /* execute the global php code */
    eval($_->codes['global']['php']['']); 

    /* starts the cascading execution */  
    $this->webgets['root']->__flush($this);

    /* Appends the code for displaying the system errors; This code has been
       designed to give the possibility to hide messages */
    if ($this->system_error_queue) {
      foreach ($this->system_error_queue as $error) {
        $this->buffer .= $error;
      }
    }

    /* prints end microtime (for benchmarking purpose) */
    //echo 'microtime = '.( microtime (true) - $microtime);                     
    return $this->buffer;
  }  

  
  /****************************************************************************/
  /*                   Make an object from the code file                      */
  /****************************************************************************/
                                                                               
  function get_codes()
  {
    /* read code-file, exits if none found */
    if (!($file = @file_get_contents('reports/'.$this->CALL_SOURCE.'.php')))
      return;
    
    /* strips comments if debug-mode is disabled */    
    if ($this->settings['debug'] == false)
      foreach (token_get_all($file) as $token) {
        $find = strpos($token[1],'//?global')
               .strpos($token[1],'//?webget')
               .strpos($token[1],'//?class');
               
        if ($token[0] != T_COMMENT || $find != '') continue;
        $file = str_replace($token[1], '', $file);
      }
  
    $file = explode("\n", ltrim(rtrim($file, "?> \n\t"), '<?php'));
    
    while (count($file)>0) {
      $row = array_shift($file);

      $find = strpos($row,'//?global')
             .strpos($row,'//?webget')
             .strpos($row,'//?class');
      
      if ($find != '') {
        if (isset($code)) $this->codes
          [$code['type']][$code['target']][$code['event']] = $code['data'];

        $row = explode('.', ltrim(trim($row), '//?'));
        $code = array(
          'type' => $row[0],
          'target' => $row[1],
          'event' => $row[2]);
      } else
        $code['data'] .= ($this->settings
          ['debug'] == true ? $row."\n" : trim($row).' ');
    }
    
    if ($code) $this->codes
     [$code['type']][$code['target']][$code['event']] = $code['data'];
  }
  

  /****************************************************************************/
  /*                do the build action for creating the webget               */
  /****************************************************************************/
  
  function startelm($parser, $library_name, $library_attribs)
  {
    $library_url   = str_replace(':', '/', $library_name);
    $library_class = str_replace(':', '_', $library_name);
  
    if (!is_file($this->webget_path.$library_url.'.cl.php'))
      die ('STOP! The library "'.$library_name.'" doesn\'t exists. ('.
           $this->webget_path.$library_url.'.cl.php)');
  
    require_once $this->webget_path.$library_url.'.cl.php';
    
    /* If no already registred, set this first webget as root webget */
    if (!$this->webgets['root']) $library_attribs['id'] = 'root';


    /* Request to register the explicit webget id in the global 
       javascript context */    
    if ($library_attribs['id'] && $this->webgets['root'])
      $this->webgets['root']->visible_webgets[] = $library_attribs['id'];   
  
    /* If not explicitly requested assigns an automatic id 
       (for internal coherence) */
    if (!$library_attribs['id']) $library_attribs['id'] 
      = 'wbg'.$this->webget_enum++;
  
    /* Checks if there's another webget with the same is already registred */
    if ($this->webgets[$library_attribs['id']])
      die ("STOP! Duplicated webget id : '".$library_attribs['id']."'");
  
    /* attach the external property to the webget by ID 
       if is not already defined in the XML file */
    if ($webget_props = $this->codes['webget'][$library_attribs['id']])
      foreach ($webget_props as $property => $value)
        if (!$library_attribs[$property])
          $library_attribs[$property] = trim($value);

    /* attach the external property to the webget by CLASS
       if is not already defined in the XML file */
    if ($webget_props = $this->codes['class'][$library_name])
      foreach ($webget_props as $property => $value)
        if (!$library_attribs[$property])
          $library_attribs[$property] = trim($value);
          
  
    /* sets the parent of the new class */
    if ($this->current_webget)
      $library_attribs['parent'] = &$this->current_webget;
    
    /* define the webget and add it to te webgets array */
    $this->webgets[$library_attribs["id"]]
      = new $library_class($this, $library_attribs);
  
    /* link the new webget to its parent as a child of it */
    if ($this->current_webget)
      $this->current_webget->childs[$library_attribs['id']]
        = &$this->webgets[$library_attribs['id']];
  
    /* sets the new webget as current webget */
    $this->current_webget = &$this->webgets[$library_attribs['id']];
  
    /* append the new webget to the hierarcy stack */
    $this->hierarchy_stack[] = $library_attribs["id"];
    eval('$this->hierarchy[\''
     .implode("']['", $this->hierarchy_stack)
     ."'] = array();");
  }
  
  function endelm($parser, $library_name)
  {
    /* go back to the parent webget */
    if ($this->current_webget->parent)
      $this->current_webget = &$this->current_webget->parent;
  
    /* remove the current webget from the hierarcy stack */
    array_pop ($this->hierarchy_stack);
  }
}
?>