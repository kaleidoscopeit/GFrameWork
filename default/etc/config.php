<?php
require 'etc/secpol.php';

$this->settings = array(

  /* gfwk stuff */
  
  'app-uuid'          => '33815ed0-0d33-4fac-8721-55dccb86f424',
  'gfwkconf_admin'    => 'administrator',
  'debug'             => false,
  'formatted_output'  => true,  
  
  /* authentication stuff */
  
  'auth_engine'       => 'samdb',
//'auth_hashing_method' => 'md5',
  'auth_admin_group'  => 'administrators',
  'auth_login_page'   => false, //'login',
  'auth_logout_page'  => 'logout',
  'auth_denied_page'  => 'denied',
  'auth_login_event'  => 

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

   
      //if (!$_->call("user.common.logs.trace_me", $buffer)) return FALSE;

      return TRUE;
    },
    

  /* mailing stuff */
  
  'mail_smtp_server'  => 'smtp.server.com',
  'mail_smtp_user'    => 'test@server.com',
  'mail_smtp_pass'    => 'test',
  'mail_sender'       => 'test@server.com',
  'mail_sender_name'  => 'G-Framework Toolkit v2.0',
  
      

  //'calls' => array('system.locale' => array('lc_path') => array('locale/');  


  /* default locale */
  
  'default_i18n'      => 'en_EN',


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
