<?php
require $_->APP_PATH . '/etc/secpol.php';

$this->settings = array(

  /* gfwk stuff */

  'app-uuid'          => 'f55d681b-dcb8-490b-b5e8-60302020478e',                // unique application id (make id with bash -> uuidgen)
  'app_admin_pwd'     => 'test',                                                // application admin password (MD5 hash, not actually used, for 'the builder app')
  'debug'             => false,                                                 // enable server side debug
  'cs_debug'          => true,                                                  // enable client side debug
  'formatted_output'  => true,                                                  // HTML output to client sent formatted


  /* authentication stuff */

  'auth_engine'         => 'samdb',                                             // Authentication engine
//'auth_hashing_method' => 'md5',                                               // Used in simple auth engine
  'auth_admin_group'    => 'administrators',
  'auth_login_page'     => NULL,                                                // Login view. Set to to null to disable login features,
  'auth_logout_page'    => 'logout',                                            // Logout view, currently not used
  'auth_denied_page'    => 'denied',                                            // Access denied view, currently not used
  'auth_login_event'    =>                                                      // Default login event

    function($_STDIN, &$_)
    {
      $signal = $_STDIN[0]['signal'];

      if ($signal == 'AUTH_CHECKUSER_ACCEPTED')

        $buffer = array (
          'agent'       => 'auth',
          'event'       => 'login-accepted',
          'param1'      => $_SERVER['REMOTE_ADDR'],
          'description' => "User logged-in from the machine IP ".
                           $_SERVER['REMOTE_ADDR']
        );

      else

        $buffer = array (
          'agent'       => 'auth',
          'event'       => 'login-error',
          'param1'      => $_SERVER['REMOTE_ADDR'],
          'param2'      => $signal,
          'description' => "Rejected user login with following signal : ".
                           $signal."' (Machine IP ".$_SERVER['REMOTE_ADDR'].")"
        );


      //if (!$_->call("common.logs.trace_me", $buffer)) return FALSE;

      return TRUE;
    },


  /* mailing stuff */

  'mail_smtp_server'  => 'smtp.server.com',
  'mail_smtp_user'    => 'test@server.com',
  'mail_smtp_pass'    => 'test',
  'mail_sender'       => 'test@server.com',
  'mail_sender_name'  => 'G-Framework Toolkit v2.0',



  //'calls' => array('locale' => array('lc_path') => array('locale/');


  /* default locale */

  'default_i18n'      => 'en_EN',                                               // Internazionalization, currently not used


  /* database connections */

  'rdbmsc' => array (

    'default' => array (

      'db_host' => "localhost",
      'db_user' => "gfwk",
      'db_pass' => "gfwk", //password
      'db_name' => "sakila"
    ),

    'alternate' => array (

      'db_host' => "localhost",
      'db_user' => "alternate-user",
      'db_pass' => "alternate-password",
      'db_name' => "alternate-db"
    ),

  ),


  /* Security policy */

  'secpol' => $secpol
);



?>
