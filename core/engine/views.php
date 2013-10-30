<?php

/* views builder
 *
 * This class contains all tool in order to address and builds views.
 * 
 */

class _engine_views
{

  function init()
  {
    /* initialization */
    $this->webget_enum           = 0;
    unset($this->static[$this->CALL_URI]);
    $this->buffer = array();
  }
  
  /****************************************************************************/
  /*                       Main function of this class.                       */
  /*                                                                          */
  /* By reading and 'decoding' the XML code in the view file, let the webget  */
  /* classes generate client code.                                            */
  /****************************************************************************/    
  function build($source_url)
  {
    /* loopback to the buck */
    $_ = &$this;

    /* Check if the view exists */
    $source_url_uri = "views/"
                    . $this->CALL_URI
                    . "/"
                    . $this->CALL_TARGET;

    /* Check if the view exists */
    $source_url_url = "views/"
                    . $this->CALL_URI
                    . "/_this";
//                  . $this->CALL_TARGET;

    if(is_file($source_url.".xml")) $source_url = $source_url_uri;
    if(is_file($source_url_uri.".xml")) $source_url = $source_url_uri;
    else if(is_file($source_url_url.".xml"))$source_url = $source_url_url;
    else die("<!--\n\n-->View not found!");
 
    self::populate_root_object($source_url);

    /****** from now we use ROOT as placeholder as the main view class ********/
    
    /* assign global scope javascript code in the root webget 
       ( TODO: has to be more abstract ) */

    $_->static[$_->CALL_URI]['js']['raw'][] =
      $this->codes['global']['javascript']['default'];

    if (is_file($source_url.'.js'))
      $_->static[$_->CALL_URI]['js']['raw'][] =
        file_get_contents($source_url.'.js');    
      
    /* the view php code in the global scope will be executed */
    if(isset($this->codes['global']['php']['default']))
      eval($this->codes['global']['php']['default']);
                                              
    /* starts cascading execution witch will fills output buffer */
    gfwk_flush($this->ROOT, $this);

    /* eventually appends system errors HTML code (needs improovements) */
    if (isset($this->system_error_queue))
      foreach ($this->system_error_queue as $error)
        $this->buffer[] = $error;

    return $this->buffer;
  }

  
  function populate_root_object($source_url)
  {
    /* import code file and parse it */
    self::get_codes($source_url); 

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
        
    /* loads main XML view and eventually merges nested overlays */
    $source = file_get_contents($source_url.'.xml');

    /* parses the complete view. This will build the big ROOT object */
    if (!xml_parse($parser, $source, true))
      die (sprintf(
        "XML error in '%s' : %s at line %d",
        $source_url,
        xml_error_string(xml_get_error_code ($parser)),
        xml_get_current_line_number($parser)));

    xml_parser_free($parser);
    unset($source); 
  }
  
  
  /****************************************************************************/
  /*               Handles codes file associated with the view.               */
  /*                                                                          */
  /* By reading the codes file will make an object and will attach the code   */
  /* stub to the respective target                                            */
  /****************************************************************************/
      
  function get_codes($source_url)
  {
    /* checks if codes file exists */
    if (!is_file($source_url.'.php')) return;
    $file = @file_get_contents($source_url.'.php');

    /* strips comments if debug-mode is disabled */
    if ($this->settings['debug'] == false)
      foreach (token_get_all($file) as $token) {
         @$find = strpos($token[1],'//?global')
              . strpos($token[1],'//?webget')
              . strpos($token[1],'//?class');
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
          @ $this->codes
            [$code['type']]
            [$code['target']]
            [$code['event']] = $code['data'];

        $row = explode('.', ltrim(trim($row), '//?'));
        
        $code = array(
          'type'   => array_shift($row),
          'event'  => array_pop($row),
          'target' => implode('.', $row));
      } 
      
      else  @$code['data'] .= 
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
    $_ = &$this;
    $library_class = str_replace(':', '_', $library_name);
    $this->parser = $parser;
    
    /* If no already registred, set this first webegt as root webget */
    if (!isset($this->ROOT) && !isset($library_attribs['id']))
      $library_attribs['id'] = 'root';

    /* If not explicitly requested assigns an automatic id 
       (for internal coherence) */
    if (!isset($library_attribs['id'])) $cwid =
      'wbg'.$this->webget_enum++;
    else $cwid = $library_attribs['id'];

    /* attach the external property to the webget by ID if is not already
       defined in the XML file */
    if ($webget_props = @$this->codes['webget'][$cwid])
      foreach ($webget_props as $property => $value)
        if (!isset($library_attribs[$property]))
          $library_attribs[$property] = trim($value);

    /* attach the external property to the webget by CLASS if is not already
       defined in the XML file */
    if ($webget_props = @$this->codes['class'][$library_name])
      foreach ($webget_props as $property => $value)
        if (!isset($library_attribs[$property]))
          $library_attribs[$property] = trim($value);
          
    /* sets the parent of the new class */
    if (isset($this->current_webget))
      $library_attribs['parent'] = &$this->current_webget;

    /* define the webget and add it to the webgets array */
    $this->webgets[$cwid] = new $library_class($this);      

    if(isset($this->webgets[$cwid]->req_attribs))
      register_attributes($this->webgets[$cwid],
                          $library_attribs,
                          $this->webgets[$cwid]->req_attribs);

    $this->webgets[$cwid]->scope = &$this;

    if(method_exists($this->webgets[$cwid],"__define"))
      $this->webgets[$cwid]->__define($_);

    /* bound 'ondefine' server event to the webget and trig-it */
    if(isset($this->webgets[$cwid]->ondefine)){
      $ondefine = function() use (&$_){eval($this->ondefine.';');};
      $boundClosure = $ondefine->bindTo($this->webgets[$cwid]);
      $boundClosure();
    }
    
    /* link the new webget to its parent as a child of it */
    if (isset($this->current_webget))
      $this->current_webget->childs[] = 
        &$this->webgets[$cwid];
  
    /* sets the new webget as current webget */
    $this->current_webget = &$this->webgets[$cwid];
  }


  /****************************************************************************/
  /*                  Code executed on webgets TAG closing                    */
  /****************************************************************************/  

  function endelm($parser, $library_name)
  {
    /* go back to the parent webget */
    if ($this->current_webget->parent)
      $this->current_webget = &$this->current_webget->parent;
  }
}

function gfwk_flush(&$webget)
{
  $_ = $webget->scope;

  /* executes preflush if exists */
  if(method_exists($webget, '__preflush')) $webget->__preflush($_);
  
  /* bound 'onflush' server event to the webget and trig-it */
  if(isset($webget->onflush)){
    $onflush = function() use (&$_){eval($this->onflush.';');};
    $boundClosure = $onflush->bindTo($webget);
    $boundClosure();
  }

  /* inhibit painting if required */
  if(isset($webget->nopaint)) return;
  
  $webget->__flush($_);
}

function gfwk_flush_children($webget, $filter = NULL)
{
  if(isset($webget->childs)) {
    if(isset($filter)) {
      foreach ($webget->childs as  $child) 
        if (get_class($child) == $filter)
          gfwk_flush($child);
    }
    else 
      foreach ($webget->childs as  $child) gfwk_flush($child);
  }
}


function __autoload($class_name)
{
  global $_;

  $library_url = str_replace('_', '/', $class_name);

  if (!is_file($_->WEBGETS_PATH.$library_url.'.cl.php'))
    die ('STOP! The library "' . $class_name . '" doesn\'t exists in source'
        .' view "' . $_->CALL_SOURCE . '" at line number '        
        . xml_get_current_line_number($_->parser) . '.');


  $_->libraries[] = $library_url;
  require $_->WEBGETS_PATH.$library_url.'.cl.php';
}
?>
