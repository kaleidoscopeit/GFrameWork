<?php
/*
  * Returns the list of existing users from local samdb using pdbedit.
  * su privileges
 */

$rpc = array (array (

/* group name filter */
 
'filter' => array (
  'type'     => 'array',
  'required' => false,
  'origin'   => array (
    'variable:$_STDIN["filter"]',
))

),

/* rpc function */
 
function(&$_, $_STDIN, &$_STDOUT) use (&$self)
{
  /* Get the users list from local server tdb database */
  exec('../core/lib/bin/pdbbridge get_ulist', $passwd);
  $ulist  = array();

  /* prepare the default result with all users */
  foreach ($passwd as $record) {
    $record = explode(':', $record);
    $ulist[$record[0]]['uid']   = $record[0];
    $ulist[$record[0]]['uname'] = $record[1];
    
    $groups = explode(',', $record[2]);
    foreach ($groups as $group) {
      $ulist[$record[0]]['group'][] = $group;
    }
  }

  /* Filter results */
  if ($_STDIN['filter']){
   foreach ($ulist as $key => $user) {
    if (!array_intersect($_STDIN['filter'], $user['group']))
    unset($ulist[$key]);
   }  
  }
  
  $_STDOUT[0]['signal'] = 'AUTH_USERLIST_READY';
  $_STDOUT[0]['call']   = $self['name'];
  $_STDOUT[1] = $ulist;
  return TRUE;      
});  

?>