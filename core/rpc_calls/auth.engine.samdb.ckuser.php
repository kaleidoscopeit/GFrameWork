<?php
/*
  * Authenticate the user against the server
 */

$rpc = array (array (

'user' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_buffer["user"]',
)),

'pass' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_buffer["pass"]',
)),

),

/* rpc function */
 
function(&$_, $_buffer, &$_output) use (&$self)
{
  /* build parrword hash */
  $pass = $_buffer["pass"];
  $pass=iconv('UTF-8','UTF-16LE',$pass);
  $MD4Hash=bin2hex(mhash(MHASH_MD4,$pass));
  $NTLMHash=strtoupper($MD4Hash);
  
  
  /* Check if the user exists and can login  */
  exec('../core/lib/bin/pdbbridge check_pass '.
       $_buffer["user"].' '.
       $NTLMHash,
       $result);

  switch($result[0]) {
    case 'true' :
      /* if accepted return the user information */      
      $_output = array();
      $_output[0]['signal'] = 'AUTH_CHECKUSER_ACCEPTED';
      $_output[0]['call']   = $self['name'];

      /* Get the users list from local server tdb database */
      exec('../core/lib/bin/pdbbridge get_uinfo '.
           $_buffer["user"],
           $user_info);

      $user_info = $user_info[0];
      $user_info = explode(':', $user_info);
      $user_info[2] = explode(',', $user_info[2]);

      $_output[1]  = array(
        'id'    => $user_info[0],
        'name'  => $user_info[1]);
              
      $_output[1]['group'] = $user_info[2];        

      return TRUE;
      break;            

    case 'false' :      
      $_output[0]['signal'] = 'AUTH_CHECKUSER_WRONGPASS';
      $_output[0]['call']   = $self['name'];
      $_output[1]  = array(
        'id'    => 	substr($record[0], 2),
        'name'  => $record[1]);
      return FALSE;            
      break;

    case 'error' :
      /* else gives 'wrong user' error */
      $_output[0]['signal'] ='AUTH_CHECKUSER_WRONGUSER';
      $_output[0]['call']   = $self['name'];
      $_output[1]  = array('id' => $_buffer['user']);
      return FALSE;
  }
});  

?>