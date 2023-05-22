<?php
/*
  * Authenticate the user against the file based authentication method
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

'hashing_method' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_->settings["auth_hashing_method"]',
    'string:"md5"'
))

),

/* rpc function */
 
function(&$_, $_STDIN, &$_STDOUT) use (&$self)
{
  /* Import authentication files */
  $passwd = file($_->APP_PATH . '/etc/passwd.php');
  $groups = file($_->APP_PATH . '/etc/group.php');

  /* find user in the database file */
  foreach ($passwd as $record) {
    $record = explode(':', trim ($record));

    /* if at least in one record the user name exists checks the password */
    if ('//' . $_STDIN['user'] == $record[0]) {
      switch($_STDIN['hashing_method']) {
        case 'md5':
        default:
          /* bash: echo -n 'user-password' | md5sum */
          if (md5($_STDIN['pass']) == $record[1]) $accepted = TRUE;
          break;
      }

      /* if accepted return the user information */      
      if(isset($accepted)) {
        $_STDOUT = array();
        $_STDOUT['STDERR']['signal'] = 'AUTH_CHECKUSER_ACCEPTED';
        $_STDOUT['STDERR']['call']   = $self['name'];
        $record[3] = explode(',', trim ($record[2]));  // Info
        $_STDOUT[1]  = array(
          'id'    => substr($record[0], 2),
          'name'  => $record[2][0],
          'mail'  => $record[2][1]
        );

        /* Imports group where the user seats */
        foreach ($groups as $group) {
          if(substr($group,0,2) != "//") continue;

          $group = explode(':', $group);          
          $users = explode(',', trim($group[1]));
          $group = substr($group[0],2);

          if (in_array($_STDOUT[1]['id'], $users))
            $_STDOUT[1]['group'][] = $group;						
        }

        return TRUE;            
      }
      
      else {
        $_STDOUT['STDERR'] = array(
          'call'          => array($self['name']),
          'signal'        => 'AUTH_CHECKUSER_WRONGPASS',
          'detail'        => array(
            'id'    => 	substr($record[0], 2),
            'name'  => $record[1])
          );

        return FALSE;            
      }
    }
  }    

  /* else gives 'wrong user' error */
  $_STDOUT['STDERR'] = array(
    'call'          => array($self['name']),
    'signal'        => 'AUTH_CHECKUSER_WRONGUSER',
    'detail'        => array('id' => $_STDIN['user'])
    );

    return FALSE;
});  

?>