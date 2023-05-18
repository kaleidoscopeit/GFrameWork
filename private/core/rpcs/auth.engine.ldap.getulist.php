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
    'variable:$_STDIN["ldap_user"]',
    'variable:$_->settings["auth_ldap_user"]',
)),

/* query allowed user pass */

'pass' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_STDIN["pass"]',
    'variable:$_->settings["auth_ldap_pass"]',
)),

/* search group */

'group' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_STDIN["group"]',
    'variable:$_->settings["auth_ldap_group"]',
)),

/* search group */

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
  $filter = "(&(objectClass=group)(cn=".$_STDIN['group']."))";
  $sr     = ldap_search($cnx, $_STDIN['basedn'], $filter);
  $entry  = ldap_first_entry($cnx, $sr);
  $users  = ldap_get_values($cnx, $entry, "member");

  unset($users['count']);
  $ulist  = array();

  /* prepares the default result with all users */
  foreach ($users as $user) {
    $uid = preg_replace(
      $_STDIN['member_mask'][0],
      $_STDIN['member_mask'][1],
      $user);

    // TODO : fix search by checking diabled flag
    $filter = "(&(objectClass=user)(cn=".$uid."))";
    $sr     = ldap_search($cnx, $_STDIN['basedn'], $filter);
    if (!($entry  = ldap_first_entry($cnx, $sr))) continue;
    $uid    = ldap_get_values($cnx, $entry, $_STDIN['uid_field']);
    $uname  = ldap_get_values($cnx, $entry, $_STDIN['uname_field']);

    $ulist[] = array(
      'uid'   => $uid[0],
      'uname' => $uname[0]
    );
  }

  $_STDOUT = $ulist;

  return TRUE;
});

?>
