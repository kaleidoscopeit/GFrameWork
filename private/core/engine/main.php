<?php
class _
{
  function __construct($req_object)
  {
    /* Early enable the use of the global framework class variable $_ */
    $_ = $this;

    /* build global names */
    $_->set_global_names($req_object);

    /* imports the project configuration */
    require $_->APP_PATH . '/etc/config.php';

    /* start session.*/
    if(session_id() == '') session_start();

    /* imports the session data of this app-uuid */
    $_->static = &$_SESSION[$_->settings['app-uuid']];

    /* Session timeout handler */

    /* if the called framework object is not the special one used to check the
       session idle time expiration the the session time is set or updated */
    if($req_object != 'cksess') $_->static['time'] = time();

    /* If the config param 'session_timeout' is set check the time passed from
       the last session time refresh and if is greater than the seconds
       expected in 'session_timeout', the session is automatically void. */
    if(isset($_->settings['session_timeout'])) {
      if(time() - $_->static['time'] > $_->settings['session_timeout']) {
        $_->static = NULL;
      }
    }

    /* includes varius helpers functions */
    require_once 'helpers.php';
  }

  /* creates a number of predefined global names */
  function set_global_names($req_object){
    /* extract the type of object called ( must be the first part ) */
    $req_object = explode('/', rtrim($req_object,"/"));

    /* global names */    
    $this->CALL_OBJECT  = array_shift($req_object);    
    $this->CALL_PATH    = implode('/', $req_object);
    $this->CALL_URN     = implode('.', $req_object);
    $this->CALL_TARGET  = count($req_object)>0 ? $req_object[count($req_object)-1] : NULL;
    $this->APP_NAME     = basename(getcwd());
    $this->APP_PATH     = '../../private/' . $this->APP_NAME;
    $this->CORE_PATH    = '../../private/core';
    $this->WEBGETS_PATH = $this->CORE_PATH . '/webgets/';
  }

  //****************************************************************************
  //                            Called objects HUB
  //
  // Apply the security policy, if required, and act as hub to execute the right
  // handler based on the requested object
  // 
  // Object types:
  //
  // view    -> standard view object
  // subview -> same as standard view, but nested in a view
  // call    -> asyncronuos remote procedure call
  // lib     -> 
  //****************************************************************************
  function main()
  {
    /* loopback to the buck */
    $_ = $this;

    /* initialize the RPC engine */
    require_once __DIR__ . '/call.php';
    $_->rpc_engine = new _engine_call();

    /* TODO : Authentication checkpoint */
//    if (!$_->call('auth.check',$_buf))
//      $_->CALL_SOURCE = $_->settings['auth_login_page'];

    switch ($_->CALL_OBJECT) {
      case 'views' :
      case 'subview' :
        if (!_call('auth.check', $_buf))
          /* output the login page if is set in settings and auth.chek fails and */
          $_->set_global_names($_->settings['auth_login_page']);

        require_once __DIR__ . '/views.php';
        $_->ENGINE = new _engine_views($_);
        return $_->ENGINE->build($_);
        break;

      case 'call' :
        return $_->rpc_engine->build($_);
        break;


      case 'js' :
        require __DIR__ . '/js.php';
        return  _engine_djl::get($_);
        break;


      case 'css' :
        //if (!$_->call('auth.check',$_buf)) die('Access Denied');
        require_once __DIR__ . '/css.php';
        _engine_css::init($_);
        return _engine_css::build($_);
        break;


      case 'djs' :
        //if (!$_->call('auth.check',$_buf)) die('Access Denied');
        require_once __DIR__ . '/djs.php';
        _engine_js::init($_);
        return _engine_js::build($_);
        break;


      case 'upload' :
        require_once __DIR__ . '/upload.php';
        $_->ENGINE = new _engine_upload($_);
        return $_->ENGINE->build($_);
        break;

      /* Check session status, dont alter session timeout */
      case 'cksess' :
        if(!isset($_->static["auth"])) return 'SESSION_EXPIRED';
        if($_->static["auth"]["allowed"] == TRUE) {
          /* return env on request else return a signal */
          if(in_array("env",array_keys($_GET)) == true){
            echo '$_.env.user="' . $_->static['auth']['user']['id'] . '";'
               . '$_.env.uname="' . $_->static['auth']['user']['name'] . '";'
               . '$_.env.group="' . @implode(',', $_->static['auth']['user']['group']) . '";'
               . '$_.env.teams="' . @implode(',', $_->static['auth']['user']['teams']) . '";'
               . '$_.env.domain="' . @$_->static['auth']['user']['domain'] . '";'
               . '$_.env.client={};'
               . '$_.env.client.engine="' . @$_->static['client']['engine'] . '";'
               . '$_.env.settings={};'
               . '$_.env.settings.cs_debug="' . @$_->settings['cs_debug'] . '";';
          }

          else return 'SESSION_ACTIVE';
        }

        else return 'SESSION_EXPIRED';
        break;

      case 'compile' :
        if (!$_->call('auth.check',$_buf)) die('Access Denied');
        require_once __DIR__ . '/compile.php';
        _engine_compile::init();
        return _engine_compile::build($_->CALL_SOURCE);
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

  function execute ($req_object)
  {
    /* store locally the original environment */
    $ex_      = $GLOBALS['_'];         /* Store current core instance */
    $exGET    = $_GET;                 /* Store previous GET values */

    parse_str       ($req_object, $_GET);  /* Create a new _GET array from the source */

    /* get the path of called framework object  */
    $req_object   = array_keys($_GET);
    $req_object   = array_shift($req_object);

    /* launch a nested framework execution */
    $GLOBALS['_'] = new _($req_object);
    $_buffer      = $GLOBALS['_']->main();

    /* restore original environment */
    $GLOBALS['_'] = $ex_;     /* restore original scope */
    $_GET         = $exGET;   /* Restore previous _GET array */

    return $_buffer;
  }
}

?>
