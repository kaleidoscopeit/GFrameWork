<?php
/*
  * Returns the list of configured users
 */

$rpc = array (array (

/* group name filter */
 
'filter' => array (
  'type'     => 'array',
  'required' => false,
  'origin'   => array (
    'variable:$_buffer["filter"]',
))

),

/* rpc function */
 
function(&$_, $_buffer, &$_output) use (&$self)
{
  /* Import authentication files */
  $passwd = file('vars/auth/passwd.php');
  $groups = file('vars/auth/group.php');
  $ulist  = array();

  /* prepare the default result with all users */
  foreach ($passwd as $record) {
   $record = explode(':', $record);
   if (substr($record[0], 0, 2) == '//') {
    $ulist[substr($record[0], 2)]['uid']   = substr($record[0], 2);
    $ulist[substr($record[0], 2)]['uname'] = $record[1];
   }
  }

  foreach ($groups as $group) {
   $group = explode(':', $group);
   if (substr($group[0], 0, 2) == '//' && trim($group[1]) != '') {
    $users = explode(',',$group[1]);
    $group = substr($group[0], 2);

    foreach ($users as $user) {
     $ulist[trim($user)]['group'][] = $group;
    }
   }
  }     

  // Filter results
  if ($_buffer['filter']){
   foreach ($ulist as $key => $user) {
    if (!array_intersect($_buffer['filter'], $user['group']))
    unset($ulist[$key]);
   }  
  }

  $_output[0]['signal'] = 'AUTH_USERLIST_READY';
  $_output[0]['call']   = $self['name'];
  $_output[1] = $ulist;
  return TRUE;      
});  

?>