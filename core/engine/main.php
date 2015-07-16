<?php
class _
{
  function __construct($source)
  {
    /* start session */
    if(session_id() == '') session_start();

    /* imports the project configuration file and
       the configuration database engine */
    require "etc/config.php";

    /* imports GIDE static data from browser session */
    $this->static = &$_SESSION[$this->settings['app-uuid']];

    /* global names */
    $this->set_global_names($source);

    /* enable debug calls */
//    $message=array('message'=>'call debug');
//    _call('utils.write_debug',$message);
    /* other libraries */
    require_once 'helpers.php';
  }

  /* creates a number of predefined global names */
  function set_global_names($source){
    /* extract the type of object called ( must be the first part ) */
    $source = explode('/', rtrim($source,"/"));

    /* global names */
    $this->CALL_OBJECT = array_shift($source);
    $this->CALL_PATH   = implode('/', $source);
    $this->CALL_URI    = implode('.', $source);
    $this->CALL_TARGET = $source[count($source)-1];
    $this->APP_PATH = '//' . $_SERVER['HTTP_HOST']
                    . dirname($_SERVER['PHP_SELF']);
    $this->CORE_PATH    = '../core';
    $this->WEBGETS_PATH = $this->CORE_PATH . '/webgets/';
  }

  /****************************************************************************/
  /*                            Called objects HUB                            */
  /*                                                                          */
  /* apply the security policy if required and act as hub for various         */
  /* sub funcitions depending by the called object                            */
  /****************************************************************************/
  function main()
  {
    /* loopback to the buck */
    $_ = $this;

    /* initialize the RPC engine */
    require_once __DIR__ . '/call.php';
    $this->rpc_engine = new _engine_call();

    /* TODO : Authentication checkpoint */
//    if (!$_->call('auth.check',$_buf))
//      $this->CALL_SOURCE = $this->settings['auth_login_page'];

    switch ($this->CALL_OBJECT) {
      case 'views' :
      case 'view' :
      case 'subview' :

        if (!_call('auth.check',$_buf))
          $this->set_global_names($this->settings['auth_login_page']);

        require_once __DIR__ . '/views.php';
        $this->ENGINE = new _engine_views($this);
        return $this->ENGINE->build($this);
        break;


      case 'reports' :

        if (!_call('auth.check',$_buf)) die('Access Denied');
        require_once __DIR__ . '/reports.php';
        return _engine_reports::build();
        break;


      case 'call' :

        return $this->rpc_engine->build($_);
        break;


      case 'lib' :
        require __DIR__ . '/djl.php';
        return  _engine_djl::get();
        break;


      case 'css' :

        //if (!$_->call('auth.check',$_buf)) die('Access Denied');
        require_once __DIR__ . '/css.php';
        _engine_css::init();
        return _engine_css::build();
        break;


      case 'js' :

        //if (!$_->call('auth.check',$_buf)) die('Access Denied');
        require_once __DIR__ . '/js.php';
        _engine_js::init();
        return _engine_js::build();
        break;


      case 'upload' :

        require_once __DIR__ . '/upload.php';
        _engine_upload::init();
        return _engine_upload::build();
        break;


      case 'compile' :

        if (!$_->call('auth.check',$_buf)) die('Access Denied');
        require_once __DIR__ . '/compile.php';
        _engine_compile::init();
        return _engine_compile::build($this->CALL_SOURCE);
        break;


      default :

				die ('UNKNOWN_OBJECT');
    }
  }

  function __destruct(){
    /* dump static data to session (TODO : to be removed) */
    $_SESSION[$this->settings['app-uuid']] = $this->static;
  }



  /****************************************************************************/
  /*                          In-code view generator                          */
  /*                                                                          */
  /* Loopback function for retrieving the generated code of a view. Let to    */
  /* call a view during the code execution then store its generated code      */
  /* inside a variable                                                        */
  /****************************************************************************/

  function execute ($source)
  {
    $_        = &$GLOBALS['core'];  /* Store current core instance */
    $exGET    = $_GET;              /* Store previous GET values */

    parse_str   ($source, $_GET);  /* Create a new _GET array from the source */
    $source   = array_keys($_GET);
    $source   = array_shift($source);

    $submain  = new _($source);    /* Creates a new core instance */
    $GLOBALS['_'] = &$submain; /* sets new core instance as the current */
    $_buffer  = $submain->main();  /* Start the subview */

    $GLOBALS['_'] = &$_;   /* restore original scope */
    $_GET     = $exGET;            /* Restore previous _GET array */

    return      $_buffer;
  }
}

?>
