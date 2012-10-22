<?php

/* views builder
 *
 * This class contains all tool in order to address and builds views.
 * 
 */

class _engine_views {

  /****************************************************************************/
  /*                       Main function of this class.                       */
  /*                                                                          */
  /* By reading and 'decoding' the XML code in the view file, let the webget  */
  /* classes generate client code.                                            */
  /****************************************************************************/    
  function build()
  {
                  
    /* initialization */
    $_                        = $this;                                          /* loopback to the buck */
    $_->webget_enum           = 0;
    $_->webget_path           = '../core/webgets/';
    $_->static['js-includes'] = '';                                             /* resets js inclusion index */

    /* Check if the view exists */
    if(!is_file('views/'.$this->CALL_SOURCE.".xml"))
       die("View not found!");

    /* import code file and parse it */
    self::get_codes(); 


    /* XML parsing initialization and rules defining */
    $parser = xml_parser_create();
    
    xml_parser_set_option(
        $parser,
        XML_OPTION_CASE_FOLDING,
        false);
        
    xml_set_element_handler(
        $parser,
        array('self', "startelm"),
        array('self', "endelm"));
        
    //xml_set_processing_instruction_handler ($parser, array ($this, "pihdl"));
    //xml_set_character_data_handler($parser, array($this, "codeblk"));


    /* loads XML view code and launch parsing.
       Parsing procedure builds the big object (ROOT) witch defines
       the view */     
    $source = file_get_contents('views/'.$this->CALL_SOURCE.'.xml');

    if (!xml_parse($parser, $source, true))
      die (sprintf(
        "XML error in '%s' : %s at line %d",
        $this->CALL_SOURCE,
        xml_error_string(xml_get_error_code ($parser)),
        xml_get_current_line_number($parser)));

    xml_parser_free($parser);
    unset($source); 

    /****** from now we use ROOT as placeholder as the main view class ********/
    
    /* webgets tree hierarchy */
    $_->ROOT->hierarchy = $this->hierarchy;

    /* assign global scope javascript code in the root webget 
       ( TODO: has to be more abstract ) */
    $_->ROOT->global_javascript = $this->codes['global']['javascript'][''];

    /* the view php code in the global scope will be executed */
    eval($_->codes['global']['php']['']);
                                              
    /* starts cascading execution witch will fills output buffer */
    $_->ROOT->__flush($_);
    
    /* eventually appends system errors HTML code (needs improovements) */
    if ($this->system_error_queue)
      foreach ($this->system_error_queue as $error)
        $this->buffer .= $error;

    /* prints end microtime (for benchmarking purpuose) */
    //echo 'microtime = '.( microtime (true) - $microtime);

    return $this->buffer;
  }
  

  /****************************************************************************/
  /*               Handles codes file associated with the view.               */
  /*                                                                          */
  /* By reading the codes file will make an object and will attach the code   */
  /* stub to the respective target                                            */
  /****************************************************************************/
      
  function get_codes()
  {
    /* checks if codes file exists */
    if (!is_file('views/'.$this->CALL_SOURCE.'.php')) return;
    $file = @file_get_contents('views/'.$this->CALL_SOURCE.'.php');
    
    /* strips comments if debug-mode is disabled */    
    if ($this->settings['debug'] == false)
      foreach (token_get_all($file) as $token) {
        $find = strpos($token[1],'//?global').
                strpos($token[1],'//?webget').
                strpos($token[1],'//?class');
        if ($token[0] != T_COMMENT || $find != '') continue;
         $file = str_replace($token[1], '', $file);
      }

    /* transform it in an array */  
    $file = explode("\n", ltrim(rtrim($file, "?> \n\t"), '<?php'));

    
    while (count($file)>0) {
      
      $row  = array_shift($file);
      
      $find = strpos($row,'//?global').
              strpos($row,'//?webget').
              strpos($row,'//?class');
      
      if ($find != '') {
        if (isset($code))
          $this->codes
            [$code['type']]
            [$code['target']]
            [$code['event']] = $code['data'];

        $row = explode('.', ltrim(trim($row), '//?'));
        
        $code = array(
          'type'   => $row[0],
          'target' => $row[1],
          'event'  => $row[2]);
      } 
      
      else $code['data'] .= 
        ($this->settings['debug'] == true ? $row."\n" : trim($row).' ');
    }


    if ($code)
      $this->codes
        [$code['type']]
        [$code['target']]
        [$code['event']] = $code['data'];
  }
  

  /****************************************************************************/
  /*                  Code executed on webgets TAG opening                    */
  /****************************************************************************/
  
  function startelm($parser, $library_name, $library_attribs)
  {
    $library_url   = str_replace(':', '/', $library_name);
    $library_class = str_replace(':', '_', $library_name);
  
    if (!is_file($this->webget_path.$library_url.'.cl.php'))
      die ('STOP! The library "'.$library_name.'" doesn\'t not exists.');
  
    require_once $this->webget_path.$library_url.'.cl.php';
    
    /* If no already registred, set this first webegt as root webget */
    if (!$this->ROOT) $library_attribs['id'] = 'root';

    /* Request to register the explicit webget id in the global 
       javascipt context */    
    if ($library_attribs['id'] && $this->ROOT)
      $this->ROOT->visible_webgets[] = $library_attribs['id'];   
  
    /* If not explicitly requested assigns an automatic id 
       (for internal coherence) */
    if (!$library_attribs['id']) $library_attribs['id'] =
      'wbg'.$this->webget_enum++;
  
    // Checks if there's another webget with the same is already registred
    if ($this->webgets[$library_attribs['id']])
      die ("STOP! Duplicated webget id : '".$library_attribs['id']."'");
  
    /* attach the external property to the webget by ID if is not already
       defined in the XML file */
    if ($webget_props = $this->codes['webget'][$library_attribs['id']])
      foreach ($webget_props as $property => $value)
        if (!$library_attribs[$property])
          $library_attribs[$property] = trim($value);

    /* attach the external property to the webget by CLASS if is not already
       defined in the XML file */
    if ($webget_props = $this->codes['class'][$library_name])
      foreach ($webget_props as $property => $value)
        if (!$library_attribs[$property])
          $library_attribs[$property] = trim($value);
          
  
    // sets the parent of the new class
    if ($this->current_webget)
      $library_attribs['parent'] = &$this->current_webget;
    
    // define the webget and add it to te webgets array
    $this->webgets[$library_attribs["id"]] =
      new $library_class($this, $library_attribs);
  
    // link the new webget to its parent as a child of it
    if ($this->current_webget)
      $this->current_webget->childs[$library_attribs['id']] = 
        &$this->webgets[$library_attribs['id']];
  
    // sets the new webget as current webget
    $this->current_webget = &$this->webgets[$library_attribs['id']];

    // append the new webget to the hierarcy stack
    $this->hierarchy_stack[] = $library_attribs["id"];
    eval('$this->hierarchy[\'' .
         implode("']['", $this->hierarchy_stack).
         "'] = array();");
  }


  /****************************************************************************/
  /*                  Code executed on webgets TAG closing                    */
  /****************************************************************************/  

  function endelm($parser, $library_name)
  {
    // go back to the parent webget
    if ($this->current_webget->parent)
      $this->current_webget = &$this->current_webget->parent;
  
    // remove the current webget from the hierarcy stack
    array_pop($this->hierarchy_stack);
  }
}
?>