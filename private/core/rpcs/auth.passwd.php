<?php
/*
 * Change the user password
 */

$rpc = array(array(

/* user id */

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

/* don't verify the old password */

'skip_check' => array (
  'type'     => 'boolean',
  'required' => false,
  'origin'   => array (
      'variable:$_STDIN["skip_check"]'
)),

/* old password */

'old_password' => array (
  'type'     => 'string',
  'required' => false,
  'origin'   => array (
      'variable:$_STDIN["old_password"]',
)),

),

/* rpc function */
  
function(&$_, $_STDIN, &$_STDOUT) use (&$self)
{
  return FALSE;
});



	// Exit codes :
	//
	// 0	: All ok
	// 1	: No user found
	// 2	: Previous password check failed or previous password is not passed

	// ===================================================================================================
		
/*	function passwd ( $uid , $password , $skip_check = false , $old_password = null ) {
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

		// Checks if the value $uid is passed regularly and exists
		// The only characters admitted are : alfabetic ( uppercase end lower ), numbers, dot 
		if ( 
			$uid == null or
			$uid == '' or
			preg_match( '/^[a-zA-Z0-9\.]+$/i' , $uid ) == 0 or
			!$db_uid[$uid]
		) return 1;

		// Cheks previous password if not disabled
		if ( $skip_check == false )
			if ( md5 ( $old_password ) != $db_uid[$uid][pass] ) return 2;

		// Update the password
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
		
		return false;
	}	*/
?>