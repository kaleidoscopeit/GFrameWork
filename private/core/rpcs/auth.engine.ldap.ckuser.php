<?php
/*
 * Authenticate the user against the file based user database
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

/* bind user name */

'user' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_STDIN["user"]',
)),

/* bind user pass */

'pass' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_STDIN["pass"]',
)),

/* bind realm */

'realm' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_STDIN["realm"]',
    'variable:$_->settings["auth_ldap_realm"]',
)),

/* base search */

'basedn' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_STDIN["basedn"]',
    'variable:$_->settings["auth_ldap_basedn"]',
)),

/* search group */

'group' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_STDIN["group"]',
    'variable:$_->settings["auth_ldap_group"]',
)),

/* uid field */

'uid_field' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_STDIN["uid_field"]',
    'variable:$_->settings["auth_ldap_uid_field"]',
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
  $ldapuser = $_STDIN['user'] . "@" . $_STDIN['realm'];

  $cnx = ldap_connect($_STDIN['server'])
    or die("Could not connect to LDAP");

  ldap_set_option($cnx, LDAP_OPT_PROTOCOL_VERSION, 3);
  ldap_set_option($cnx, LDAP_OPT_REFERRALS, 0);
//ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
//echo $ldapuser;
  // TODO : bind by using ldap trust user and get informations about user
  //        then try to bind with username

  if(@ldap_bind($cnx, $ldapuser, $_STDIN['pass']))
    $result = 'true';

  else
    $result = 'false';

  $_STDOUT = array();

  switch($result) {
    case 'true' :
      /* if accepted return the user information */

    	// TODO : fix search by checking diabled flag
    	$filter = "(&(objectClass=user)("
    	        . $_STDIN['uid_field']
    	        . "=" . $_STDIN['user'] . "))";
    	$sr     = ldap_search($cnx, $_STDIN['basedn'], $filter);
    	$entry  = ldap_first_entry($cnx, $sr);
    	$uid    = ldap_get_values($cnx, $entry, $_STDIN['uid_field']);
    	$uname  = ldap_get_values($cnx, $entry, $_STDIN['uname_field']);
    	$groups = @ldap_get_values($cnx, $entry, "memberof");

    	unset($groups['count']);

      $_STDOUT[1]  = array(
        'id'    => $uid[0],
        'name'  => $uname[0]);

      $_STDOUT['STDERR']['signal'] = 'AUTH_CHECKUSER_ACCEPTED';
      $_STDOUT['STDERR']['call']   = array($self['name']);

      if(!is_array($groups)) $groups = array();

      array_walk($groups, function(&$value, $key){
        $value = explode(',', $value);
        $value = explode('=',$value[0]);
        $value = $value[1];
      });

      $_STDOUT[1]['group'] = $groups;

      return TRUE;
      break;

    case 'false' :
      $_STDOUT['STDERR']['signal'] = 'AUTH_CHECKUSER_WRONGPASS';
      $_STDOUT['STDERR']['call']   = array($self['name']);

      break;

    case 'error' :
      /* else gives 'wrong user' error */
      $_STDOUT['STDERR']['signal'] = 'AUTH_CHECKUSER_WRONGUSER';
      $_STDOUT['STDERR']['call']   = array($self['name']);
      $_STDOUT[0]  = array('id' => $_STDIN['user']);
      return FALSE;
  }
});

?>
