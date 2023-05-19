<?php
/*
 * Add a new user
 */

$rpc = array(array(

/* new user id */

'uid' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
      'variable:$_STDIN["uid"]',
)),

/* new user password */

'password' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
      'variable:$_STDIN["password"]',
)),

/* user name */

'uname' => array (
  'type'     => 'string',
  'required' => false,
  'origin'   => array (
      'variable:$_STDIN["uname"]',
)),

),

/* rpc function */
  
function(&$_, $_STDIN, &$_STDOUT) use (&$self)
{
  return FALSE;
});

  // ===================================================================================================
  // Add a new user
  // ===================================================================================================
  
  // Exit codes :
  //
  // 0  : All ok
  // 1  : User already exists
  // 2  : UserId rule check

  // ===================================================================================================
    
/*  function useradd ( $uid, $password, $uname = '' ) {
    global $_;
    
    // Loads the authentication file
    $passwd = file ( 'etc/auth/passwd.php' );

    // Load all user database in memory
    foreach ( $passwd as $value ) {
      $value = explode ( ':', $value );
      if ( substr ( $value[0] , 0 , 2 ) == '//' ) {
        $value[0] = substr ( $value[0] , 2 );
        $db_uid[$value[0]]['uid'] = $value[0];
        $db_uid[$value[0]]['uname'] = $value[1];
        $db_uid[$value[0]]['pass'] = $value[2];
      }
    } 

    // Checks if the user exists
    if ( $db_uid[$uid] ) return 1;

    // Checks if the value $uid is passed regularly
    // The only characters admitted are : alfabetic ( uppercase end lower ), numbers, dot
    if (
      $uid == null or
      $uid == '' or
      preg_match( '/^[a-zA-Z0-9\.]+$/i' , $uid ) == 0
    ) return 2;

    // Add the new user in the user DB
    $db_uid[$uid][uid] = $uid;
    $db_uid[$uid][uname] = $uname;
    $db_uid[$uid][pass] = md5 ( $password ) ;

    // Save the users database on disk
    $fp = fopen('etc/auth/passwd.php', 'w');
    $stream = "<?php\n";
        
    foreach ( $db_uid as $record ) {
      $record = implode ( ':' , $record );
      $stream .= "//".$record . "\n";
    }
    
    $stream .= "?>";
    fwrite($fp, $stream);
    fclose($fp);          
  }*/
?>