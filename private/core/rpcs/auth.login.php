<?php
/*
  * Authenticate the user within the application
 */

$rpc = array (array (

'user' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_STDIN["user"]',
)),

'pass' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_STDIN["pass"]',
)),

'domain' => array (
  'type'     => 'string',
  'required' => false,
  'origin'   => array (
    'variable:$_STDIN["domain"]',
)),

'auth_engine' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_STDIN["auth_engine"]',
    'variable:$_->settings["auth_engine"]',
))

),

/* rpc function */

function(&$_, $_STDIN, &$_STDOUT) use (&$self)
{
//  $user   = $_STDIN['user'];
//  $pass   = $_STDIN['pass'];
//  $domain = $_STDIN['domain'];

  /* check authentication credentials by using the specified engine */
  if(!_call("auth.engine." . $_STDIN["auth_engine"] . ".ckuser", $_STDIN)) {
    $_STDIN['STDERR']['call'][] = $self['name'];

    /* call the custom login function in config.php anyway */
    if (is_callable($_->settings['auth_login_event'])) {
      /* >>>>>>>>> ERROR BREAK POINT <<<<<<<<<<< */
      if (!$_->settings['auth_login_event']($_STDIN, $_)){
        $_STDIN['STDERR']['call'][] = $self['name'];
        $_STDIN['STDERR']['signal'] = 'AUTH_LOGINSTACK_ERROR';
      }
    }

    $_STDOUT = $_STDIN;
    return FALSE;
  }

  /* If the authentication engine validate the user credentials */
  if($_STDIN['STDERR']['signal'] == 'AUTH_CHECKUSER_ACCEPTED') {
    $_->static['auth']['user'] = $_STDIN[1];

    /* call the custom login function in config.php */
    if (is_callable($_->settings['auth_login_event'])) {
      /* >>>>>>>>> ERROR BREAK POINT <<<<<<<<<<< */
      if (!$_->settings['auth_login_event']($_STDIN, $_)){
        $_STDOUT['STDERR'] = array(
          'call'          => array($self['name']),
          'signal'        => 'AUTH_LOGINSTACK_ERROR',
          'detail'        => $_STDIN);

        return FALSE;
      }
    }

    /* allow current user to access the application */
    $_->static["auth"]["allowed"] = TRUE;

    $_STDOUT['STDERR'] = array(
      'call'          => array($self['name']),
      'signal'        => $_STDIN['STDERR']['signal']);

    return TRUE;
  }
});

?>
