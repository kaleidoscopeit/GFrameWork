<?php
/*
  * Get user information
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
  $_STDOUT = array();
  $_STDOUT[0]['signal'] = 'AUTH_CHECKUSER_ACCEPTED';
  $_STDOUT[0]['call']   = $self['name'];

  /* Get the users list from local server tdb database */
  exec('../core/lib/bin/pdbbridge get_uinfo '.
       $_STDIN["user"],
       $user_info);

  $user_info = $user_info[0];
  $user_info = explode(':', $user_info);
  $user_info[2] = explode(',', $user_info[2]);

  $_STDOUT  = array(
    'id'   => $user_info[0],
    'name' => $user_info[1]);
          
  $_STDOUT['group'] = $user_info[2];        

  return TRUE;
});  

?>