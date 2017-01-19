<?php
class _
{
  function __construct($fwk_object)
  {
    /* start session.*/
    if(session_id() == '') session_start();

    /* imports the project configuration file and
       the configuration database engine */
    require "etc/config.php";

    /* imports the static data of this app-uuid from the browser session */
    $this->static = &$_SESSION[$this->settings['app-uuid']];

    /* if the called framework object is not the special one used to check the
       session idle time expiration the the session time is set or updated */
    if($fwk_object != 'cksess') $this->static['time'] = time();

    /* If the config param 'session_timeout' is set check the time passed from
       the last session time refresh and if is greater than the seconds
       expected in 'session_timeout', the session is automatically void. */
    if(isset($this->settings['session_timeout'])) {
      if(time() - $this->static['time'] > $this->settings['session_timeout']) {
        $this->static = NULL;
      }
    }

    /* build global names */
    $this->set_global_names($fwk_object);

    /* enable debug calls */
//    $message=array('message'=>'call debug');
//    _call('utils.write_debug',$message);

    /* include varius helpers funztions */
    require_once 'helpers.php';
  }

  /* creates a number of predefined global names */
  function set_global_names($fwk_object){
    /* extract the type of object called ( must be the first part ) */
    $fwk_object = explode('/', rtrim($fwk_object,"/"));

    /* global names */
    $this->CALL_OBJECT  = array_shift($fwk_object);
    $this->CALL_PATH    = implode('/', $fwk_object);
    $this->CALL_URI     = implode('.', $fwk_object);
    $this->CALL_TARGET  = count($fwk_object)>0 ? $fwk_object[count($fwk_object)-1] : NULL;
    $this->APP_PATH     = '//' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
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
//    $_ = $this;

    /* initialize the RPC engine */
    require_once __DIR__ . '/call.php';
    $this->rpc_engine = new _engine_call();

    /* TODO : Authentication checkpoint */
//    if (!$this->call('auth.check',$_buf))
//      $this->CALL_SOURCE = $this->settings['auth_login_page'];

    switch ($this->CALL_OBJECT) {
      case 'views' :
      case 'view' :
      case 'subview' :
        if (!_call('auth.check', $_buf))
          $this->set_global_names($this->settings['auth_login_page']);

        require_once __DIR__ . '/views.php';
        $this->ENGINE = new _engine_views($this);
        return $this->ENGINE->build($this);
        break;

      case 'call' :
        return $this->rpc_engine->build($this);
        break;


      case 'lib' :
        require __DIR__ . '/djl.php';
        return  _engine_djl::get($this);
        break;


      case 'css' :
        //if (!$this->call('auth.check',$_buf)) die('Access Denied');
        require_once __DIR__ . '/css.php';
        _engine_css::init($this);
        return _engine_css::build($this);
        break;


      case 'js' :
        //if (!$this->call('auth.check',$_buf)) die('Access Denied');
        require_once __DIR__ . '/js.php';
        _engine_js::init($this);
        return _engine_js::build($this);
        break;


      case 'upload' :
        require_once __DIR__ . '/upload.php';
        _engine_upload::init();
        return _engine_upload::build();
        break;

      case 'cksess' :
        if(isset($this->static['time'])) return 'SESSION_ACTIVE';
        else return 'SESSION_EXPIRED';
        break;

      case 'compile' :
        if (!$this->call('auth.check',$_buf)) die('Access Denied');
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
//    if(isset($this->static))
//      $_SESSION[$this->settings['app-uuid']] = $this->static;
  }



  /****************************************************************************/
  /*                          In-code view generator                          */
  /*                                                                          */
  /* Loopback function for retrieving the generated code of a view. Let to    */
  /* call a view during the code execution then store its generated code      */
  /* inside a variable                                                        */
  /****************************************************************************/

  function execute ($fwk_object)
  {
    /* store locally the original environment */
    $ex_      = $GLOBALS['_'];         /* Store current core instance */
    $exGET    = $_GET;                 /* Store previous GET values */

    parse_str       ($fwk_object, $_GET);  /* Create a new _GET array from the source */

    /* get the path of called framework object  */
    $fwk_object   = array_keys($_GET);
    $fwk_object   = array_shift($fwk_object);

    /* launch a nested framework execution */
    $GLOBALS['_'] = new _($fwk_object);
    $_buffer      = $GLOBALS['_']->main();

    /* restore original environment */
    $GLOBALS['_'] = $ex_;     /* restore original scope */
    $_GET         = $exGET;   /* Restore previous _GET array */

    return $_buffer;
  }
}

?>
