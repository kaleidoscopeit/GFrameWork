<?php
/*
  * Returns the list of existing users from local samdb using pdbedit.
  * su privileges
 */

$rpc = array (array (

/* authentication server */
 
'server' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_STDIN["server"]',
    'variable:$_->settings["auth_ldap_server"]',
)),

/* base search */
 
'basedn' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_STDIN["basedn"]',
    'variable:$_->settings["auth_ldap_basedn"]',
)),

/* realm */
 
'realm' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_STDIN["realm"]',
    'variable:$_->settings["auth_ldap_realm"]',
)),


/* query allowed user name */
 
'ldap_user' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_->settings["auth_ldap_user"]',
)),

/* query allowed user pass */
 
'pass' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_->settings["auth_ldap_pass"]',
)),

/* search path */
 
'member_mask' => array (
  'type'     => 'array',
  'required' => false,
  'origin'   => array (
    'variable:$_STDIN["member_mask"]',
    'variable:$_->settings["auth_ldap_member_mask"]',
)),

/* uid field */

'uid_field' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_STDIN["uid_field"]',
    'variable:$_->settings["auth_ldap_uid_field"]',
)),

/* required output fields */

'output_fields' => array (
  'type'     => 'array',
  'required' => true,
  'origin'   => array (
    'variable:$_STDIN["output_fields"]',
    'variable:$_->settings["auth_ldap_output_fields"]',
)),
 
/* uname field */

'uname_field' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_STDIN["uname_field"]',
    'variable:$_->settings["auth_ldap_uname_field"]',
)),

),

/* rpc function */
 
function(&$_, $_STDIN, &$_STDOUT) use (&$self)
{

  /* Get the users list from ldap server */ 
  $cnx = ldap_connect($_STDIN['server'])
    or die("Could not connect to LDAP");
  ldap_set_option($cnx, LDAP_OPT_PROTOCOL_VERSION, 3);
  ldap_set_option($cnx, LDAP_OPT_REFERRALS, 0);
  
  ldap_bind($cnx, $_STDIN['ldap_user'] . "@" . $_STDIN['realm'], $_STDIN['pass'])
    or die("Could not bind to LDAP");
    
    
	$filter = "(&(objectClass=user)(" 
	        . $_STDIN['uid_field']
	        . "=" . $_STDIN['user'] . "))";
       
  $sr     = ldap_search($cnx, $_STDIN['basedn'], $filter);
  $entry  = ldap_first_entry($cnx, $sr);
  
  $_STDOUT = ldap_get_values($cnx, $entry, $_STDIN['uid_field']);
  $_STDOUT = array(
    'uid' => $_STDOUT[0]
  );

  foreach ($_STDIN["output_fields"] as $query => $target){
    $query = explode(',/', $query);

    $_STDOUT[$target] = ldap_get_values($cnx, $entry, $query[0]);

    if($_STDOUT[$target]['count']>1) {
      unset($_STDOUT[$target]['count']);
      foreach ($_STDOUT[$target] as $index => $value)
        $_STDOUT[$target][$index] = preg_replace($query[1], '', $value);
    }
    else 
      $_STDOUT[$target] = $_STDOUT[$target][0]; 
  }

  return TRUE;

});  

?>
