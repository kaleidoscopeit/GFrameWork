<?php

/* views builder
 *
 * This class contains all tool in order to address and builds views.
 *
 */

class _engine_views
{

  function __construct(&$_)
  {
    /* initialization */
    $this->webget_enum = 0;

    /* clean session data of current view from previous call
     * TODO : evaluate the need to create a real garbage collector
     */
    unset($_->static[$_->CALL_URN]);

    /* setup output buffer */
    $_->buffer = array();
  }

  /****************************************************************************/
  /*                   Main function that build the view.                     */
  /*                                                                          */
  /* By reading and 'decoding' the XML code in the view file, let the webget  */
  /* classes generate client code.                                            */
  /****************************************************************************/

  function build(&$_)
  {
    
    /* Check if the view exists */
    if(is_file($_->APP_PATH . '/views/' . $_->CALL_URN . '/_this.xml'))
      $view_path = $_->APP_PATH . '/views/' . $_->CALL_URN;

    else die("<!--\n\n-->View not found!");

    /* builds the ROOT object containing the view structure */
    $this->populate_root_object($view_path);

    /**** from now we use $_->ROOT to refer to the view structure ******/

    /* assign global scope javascript code in the root webget
       ( TODO: has to be more abstract ) */

    $_->static[$_->CALL_URN]['js']['raw'] = Array();

    // Probably useless code never used, then may be removed in the next release
    //if(isset($this->codes['global']))
    //  $_->static[$_->CALL_URN]['js']['raw'][] =
    //    $this->codes['global']['javascript']['default'];

    /* the initial global scope php code will be executed */
    if(isset($this->codes['global']['before'])) eval($this->codes['global']['before']);

    /* starts cascading execution of ROOT obj. witch will fill output buffer */
    gfwk_flush($_->ROOT);

    /* the final global scope php code will be executed */
    if(isset($this->codes['global']['after'])) eval($this->codes['global']['after']);

    /* eventually appends system errors HTML code (needs some improovements) */
    if (isset($this->system_error_queue))
      foreach ($this->system_error_queue as $error)
        $_->buffer[] = $error;

    return $_->buffer;
  }


  /****************************************************************************/
  /*              Build the ROOT objeact containig the view                   */
  /*                                                                          */
  /* By reading the XML source of the view make an object (ROOT) containing   */
  /* the view structure ready to be "flushed"                                 */
  /****************************************************************************/

  function populate_root_object($view_path)
  {
    /* grab the buck */
    global $_;

    /* import code file and parse it */
    $this->get_codes($view_path);

    /* XML parsing initialization and rules defining */
    $parser = xml_parser_create();

    xml_parser_set_option(
        $parser,
        XML_OPTION_CASE_FOLDING,
        false);

    xml_set_element_handler(
        $parser,
        array($this, "startelm"),
        array($this, "endelm"));

    /* loads main XML view and eventually merges nested overlays */
    $source = file_get_contents($view_path . '/_this.xml');

    /* TODO : preprocess XML view in order to modify on the fly the link to
    * special resources (as firefox does using chrome:// path)
    */

    /* Code here */

    /* sets the parser in the buck, for __autoload function */
    $_->parser = $parser;

    /* parses the complete view. This will build the big ROOT object */
    if (!xml_parse($parser, $source, true))
      die (sprintf(
        "XML error in '%s' : %s at line %d",
        $view_path,
        xml_error_string(xml_get_error_code($parser)),
        xml_get_current_line_number($parser)));


    /* clean-up */
    xml_parser_free($parser);
    unset($source);
  }


  /****************************************************************************/
  /* TODO : describe this function                                            */
  /****************************************************************************/
  function extend_root($view_path){
    $ths->populate_root_object($view_path);
  }


  /****************************************************************************/
  /* register a number of predefined attributes plus those listed in webget   */
  /* variable $req_attribs                                                    */
  /****************************************************************************/

  function register_attributes(&$webget, $attributes, $grab)
  {
    $grab = array_merge($grab,
    array (
    'ondefine',
    'onflush',
    'nopaint',
    'boxing',
    'parent'));

    foreach ($grab as $attribute_name) {
      if(isset($attributes[$attribute_name])){
        $webget->$attribute_name = $attributes[$attribute_name];                  // register the attribute
        $webget->defaults[$attribute_name] = $attributes[$attribute_name];        // register webget xml defaults attributes
        unset($attributes[$attribute_name]);                                      // unset the xml attribute
      }
    }

    if(isset($attributes['id'])) $webget->id = $attributes['id'];
    $webget->attributes = $attributes;
  }

  //****************************************************************************
  //               Handles codes file associated with the view.
  //
  // By reading the server side code file will make an object and attach the
  // code stub to the respective target
  //
  // Available targets :
  //
  // global  -> generic code stub, may be executed ad the "begin" or at the
  //            "end" of the view life cycle
  //            -- Example : //?global.begin | //?global.end
  //
  // webget  -> Targets a webget by name and attach the stub to the specified
  //            server side event.
  //            -- Example : //?webget.foo_button.onflush
  //
  // class   -> Targets one or more webgets according to their type and attach
  //            the stub to the specified server side event.
  //            -- Example : //?class.base.label.onflush
  /****************************************************************************/

  function get_codes($view_path)
  {
    /* grab the buck */
    global $_;

    /* checks if codes file exists */
    if (!is_file($view_path . '/_this.php')) return;
    $file = @file_get_contents($view_path . '/_this.php');

    /* transform it in an array */
    $file = explode("\n", ltrim(rtrim($file, "?> \n\t"), '<?php'));

    /* Parse the PHP and bind the portions of code to the target webget */
    foreach ($file as $row) {
      $find = strpos($row,'//?global').
              strpos($row,'//?webget').
              strpos($row,'//?class');

      if ($find != '') {
        $row    = explode('.', ltrim(trim($row), '//?'));
        $type   = array_shift($row);
        $event  = array_pop($row);
        $target = implode('.', $row);

        $this->codes[$type][$target][$event] = '';
      }

      else if(isset($type))
        $this->codes[$type][$target][$event] .= $row . "\n";
    }
  }


  /****************************************************************************/
  /*                  Code executed on webgets TAG opening                    */
  /****************************************************************************/
  function startelm($parser, $library_name, $library_attribs)
  {
    /* grab the buck */
    global $_;

    $library_class = str_replace(':', '_', $library_name);

    /* If no already registred, force the first webegt as root webget */
    if (!isset($_->ROOT)) $library_attribs['id'] = 'root';

    /* If not explicitly requested assigns an automatic id
       (for internal coherence) */
    if (!isset($library_attribs['id'])) $cwid = 'wbg' . $this->webget_enum++;
    else $cwid = $library_attribs['id'];

    /* attach the external property to the webget by ID if is not already
       defined in the XML file */
    if (isset($this->codes['webget'][$cwid]))
      foreach ($this->codes['webget'][$cwid] as $property => $value)
        if (!isset($library_attribs[$property]))
          $library_attribs[$property] = trim($value);

    /* attach the external property to the webget by CLASS if is not already
       defined in the XML file */
    if (isset($this->codes['class'][$library_name]))
      foreach ($this->codes['class'][$library_name] as $property => $value)
        if (!isset($library_attribs[$property]))
          $library_attribs[$property] = trim($value);

    /* try to sets the parent of the new webget */
    if (isset($this->current_webget))
      $library_attribs['parent'] = &$this->current_webget;

    /* create the istance of the webget and add it to the webgets registry */
    $_->webgets[$cwid] = new $library_class($_);

    /* creates a shotcut to the first root webget */
  //  if (!isset($_->ROOT))

    /* register the webget attrtibutes */
    $this->register_attributes($_->webgets[$cwid],
                                $library_attribs,
                                $_->webgets[$cwid]->req_attribs);

    /* Sets the webget scope as gkfw buck */
    //$_->webgets[$cwid]->scope = &$_;

    if(method_exists($_->webgets[$cwid],"__define"))
      $_->webgets[$cwid]->__define($_);

    /* bound 'ondefine' server event to the webget and trig-it */
    if(isset($_->webgets[$cwid]->ondefine)){
      $ondefine = function() use (&$_) {eval($this->ondefine . ';');};
      $boundClosure = $ondefine->bindTo($_->webgets[$cwid]);
      $boundClosure();
    }

    /* link the new webget to its parent as a child of it */
    if (isset($this->current_webget))
      $this->current_webget->childs[] =
        &$_->webgets[$cwid];

    /* sets the new webget as current webget */
    $this->current_webget = &$_->webgets[$cwid];
  }


  /****************************************************************************/
  /*                  Code executed on webgets TAG closing                    */
  /****************************************************************************/
  function endelm($parser, $library_name)
  {
    /* go back to the parent webget */
    if (isset($this->current_webget->parent))
      $this->current_webget = &$this->current_webget->parent;
  }
}

function gfwk_flush(&$webget, $dry_run = null)
{
  /* grab the buck */
  global $_;

  /* executes preflush if exists */
  if(method_exists($webget, '__preflush')) $webget->__preflush($_);

  /* bound 'onflush' server event to the webget and trig-it */
  if(isset($webget->onflush)){
    $onflush = function() use (&$_) {eval($this->onflush . ';');};
    $boundClosure = $onflush->bindTo($webget);
    $boundClosure();
  }

  /* inhibit painting if required */
  if(isset($webget->nopaint) || isset($dry_run)) return;

  /* flush the nested webgets */
  $webget->__flush($_);
}

function gfwk_flush_children(&$webget, $filter = NULL)
{
  if(isset($webget->childs)) {
    if(isset($filter)) {
      foreach ($webget->childs as $child)
        if (get_class($child) == $filter)
          gfwk_flush($child);
    }
    else
      foreach ($webget->childs as $child) {gfwk_flush($child);}
  }
}

/* returns a webget from the ROOT object */
function _w($w)
{
  /* grab the buck */
  global $_;

  /* return the required webget */
  return isset($_->webgets[$w]) ? $_->webgets[$w] : false;
}

spl_autoload_register(function ($class_name)
{
  /* grab the buck */
  global $_;

  $library_url = str_replace('_', '/', $class_name);
  if (!is_file($_->WEBGETS_PATH . $library_url . '.cl.php'))
    die ('STOP! The library "' . $class_name . '" doesn\'t exists in source'
        .' view "' . $_->CALL_URN . '" at line number '
        . xml_get_current_line_number($_->parser) . '.');

  $_->libraries[] = $library_url;
  require $_->WEBGETS_PATH . $library_url . '.cl.php';
});
?>
