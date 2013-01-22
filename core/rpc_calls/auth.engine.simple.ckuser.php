<?php
/*
  * Authenticate the user against the server
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
    'variable:$this->settings["auth_hashing_method"]',
    'string:"md5"'
))

),

/* rpc function */
 
function(&$_, $_STDIN, &$_STDOUT) use (&$self)
{
  /* Import authentication files */
  $passwd = file('vars/auth/passwd.php');
  $groups = file('vars/auth/group.php');

  /* find user in the database file */
  foreach ($passwd as $record) {
    $record = explode(':', trim ($record));

    /* if at least in one record the user name exists checks the password */
    if ('//'.$_STDIN['user'] == $record[0]) {
      switch($_STDIN['hashing_method']) {
        case 'md5':
          if (md5($_STDIN['pass']) == $record[2]) $accepted = TRUE;
          break;
      }

      /* if accepted return the user information */      
      if($accepted) {
        $_STDOUT = array();
        $_STDOUT[0]['signal'] = 'AUTH_CHECKUSER_ACCEPTED';
        $_STDOUT[0]['call']   = $self['name'];
        $_STDOUT[1]  = array(
          'id'    => 	substr($record[0], 2),
          'name'  => $record[1]);

    					/* Imports group where the user seats */
    					foreach ($groups as $group) {
    						$group = explode(':', $group);
    						$users = explode(',', trim($group[1]));
    						$group = substr($group[0],2);
    
    						if (in_array($uid, $users))
    						  $_STDOUT[1]['group'][] = $group;						
    					}

        return TRUE;            
      }
      
      else {
        $_STDOUT[0]['signal'] = 'AUTH_CHECKUSER_WRONGPASS';
        $_STDOUT[0]['call']   = $self['name'];
        $_STDOUT[1]  = array(
          'id'    => 	substr($record[0], 2),
          'name'  => $record[1]);
        return FALSE;            
      }
    }
  }    

  /* else gives 'wrong user' error */
  $_STDOUT[0]['signal'] ='AUTH_CHECKUSER_WRONGUSER';
  $_STDOUT[0]['call']   = $self['name'];
  $_STDOUT[1]  = array('id' => $_STDIN['user']);
  return FALSE;
});  

?>