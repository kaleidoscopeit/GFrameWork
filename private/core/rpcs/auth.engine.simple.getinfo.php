<?php
/*
  * Get informations about a user from 'simle' database
 */

$rpc = array (array (

'user' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_STDIN["user"]',
)),


),

/* rpc function */
 
function(&$_, $_STDIN, &$_STDOUT) use (&$self)
{
  /* imports authentication files */
  include('vars/auth/passwd.php');
  
  if(!isset($passwd)) {
    $_STDOUT['STDERR'] = array(
      'signal'    => 'NO_PASSWD_ARRAY',
      'call'      => array($self['name']));

    return FALSE;  
  }
  
  //$groups = file('vars/auth/group.php');


  /* user info part */  
  if(!isset($passwd[$_STDIN['user']])) {
    $_STDOUT['STDERR'] = array(
      'signal'    => 'USER_NOT_FOUND',
      'call'      => array($self['name']));

    return FALSE;  
  }
  
  $_STDOUT['user_data']['id']    = $_STDIN['user'];
  $_STDOUT['user_data']          = $passwd[$_STDIN['user']];
  $_STDOUT['user_data']['group'] = array();
  
  
  /* group info part */
  /*foreach ($groups as $group) {
   $group = explode (':', $group);
   if (substr($group[0], 0, 2) == '//' && trim($group[1]) != '') {
    $users = explode(',', $group[1]);
    if (in_array($_buf['user_id'],$users))
     $_out['group'] = substr($group[0], 2);
   }
  }     */

  return TRUE;
});  

?>