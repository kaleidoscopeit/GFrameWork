<?php
class auth {

	
	// ===================================================================================================
	// Various checks in order to determine if the access to the current object is allowed
	// ===================================================================================================
		
	function check(&$_, $_buf, &$_out)
	{
		// Temporary escape
		if (isset($_->settings['auth_login_page']) &&
		    !isset($_->static['auth']['user']['id'])) {
			$error['title'] = "User not authenticated.";
			$error['caller'] = $this->_path.'.'.$this->_subject;
			$_out = $error;
			return FALSE;
		}
		else 	return TRUE;	
/*			header (
				"location: ?".( 
				$_->settings['auth_login_page'] ? 
				"views/".$_->settings['auth_login_page'] : 
				"views/login")
			);
		 else return ;*/

		 
		// Reads the GIDE configuration database for the policy

		// Checks if the main area of the called page is locked ( i.e. views lock all views )
		$deny = $_->config->get ( '`policy`.`'.preg_replace('/\//', '.`', $_->source, 1 ).'`.`lock`');
		print_r($_->config->get_subs ( '`policy`.`'.preg_replace('/\/.*/', '', $_->source ).'`' ));
		die;
		echo 'policy.'.preg_replace('/\/.*/', '', $_->source );die;
		$deny = $_->config->get ( 'policy.'.preg_replace('/\/.*/', '', $_->source ).'.lock' );
		echo $deny;
		$deny = $_->config->get ( 'policy.'.preg_replace('/\/.*/', '', $_->source ).'.lock' );
		echo $deny;
		die;
		// If deny is true the default policy will be to deny unless otherwise specified  
		if ( $deny !== null || $deny == true ) {
			
		}

		$deny = $_->config->get ( 'policy.'.preg_replace('/\//', '.`', $_->source, 1 ).'`.lock');
		if ( $deny !== null ) $lock = $deny;

		// Reads the users database and relative group
		
		// Do various job according the $action requested
		switch ( $action ) {
			case 0 :
				return $lock;
				break;
			case 1 :
				if ( $lock == true ) 
					header ( 
						"location: ?".( 
							$_->settings['auth_login_page'] ? 
							"views/".$_->settings['auth_login_page'] : 
							"views/login"
						)
					);
				break;
		}
	}
	


	// ===================================================================================================
	// Change the user password, if $skip_check is true don't verify the old password
	// ===================================================================================================
	
	// Exit codes :
	//
	// 0	: All ok
	// 1	: No user found
	// 2	: Previous password check failed or previous password is not passed

	// ===================================================================================================
		
	function passwd ( $uid , $password , $skip_check = false , $old_password = null ) {
		global $_;
		
		// Loads the authentication file
		$passwd = file ( 'vars/auth/passwd.php' );

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
		$fp = fopen('vars/auth/passwd.php', 'w');
		$stream = "<?php\n";
				
		foreach ( $db_uid as $record ) {
			$record = implode ( ':' , $record );
			$stream .= "//".$record . "\n";
		}
		
		$stream .= "?>";
		fwrite($fp, $stream);
		fclose($fp);
		
		return false;
	}	


	// ===================================================================================================
	// Destroy session
	// ===================================================================================================
	
	function logout(&$_, $_buf, &$_out)
	{
		unset($_->static['auth']);
		return TRUE;
	}



	// ===================================================================================================
	// Add a new user
	// ===================================================================================================
	
	// Exit codes :
	//
	// 0	: All ok
	// 1	: User already exists
	// 2	: UserId rule check

	// ===================================================================================================
		
	function useradd ( $uid, $password, $uname = '' ) {
		global $_;
		
		// Loads the authentication file
		$passwd = file ( 'vars/auth/passwd.php' );

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
		$fp = fopen('vars/auth/passwd.php', 'w');
		$stream = "<?php\n";
				
		foreach ( $db_uid as $record ) {
			$record = implode ( ':' , $record );
			$stream .= "//".$record . "\n";
		}
		
		$stream .= "?>";
		fwrite($fp, $stream);
		fclose($fp);					
	}
	
	function userdel ( $uid ) {
		global $_;
	}
	
	function usermod ( $uid ) {
		global $_;
	}

		// ===================================================================================================
	// Group based match filter
	// Accept an array of group and returns an array of group matching the ones of the current user
	// ===================================================================================================
			
	// param:groups:array:1:Group name filter

	// ===================================================================================================
	function group_match(&$_, $_buf, &$_out)
	{
		// Check params
		$_out = $this->param_check($_, $_buf, array('groups'));
		if ($_out) return FALSE;
		
		if (count($_->static['auth']['user']['group']) > 0 and count($_buf['groups']) > 0)
			$_out = array_intersect($_->static['auth']['user']['group'], $_buf['groups']);

		return TRUE;
	}


	
	// -----------------------------------------------------------------------------------------------------------
	// Standard param checker function
	
	function param_check (&$_, &$_buf, $params)
	{
		foreach ($params as $param)
			if (!isset($_buf[$param]))
				$_out['error']['desc'] .= "Cannot find required param -> '".$param."' in the buffer.";

		if (isset($_out['error'])) {
			$_out['error']['title'] = "Param check failed!";
			$_out['error']['caller'] = $this->_path.'.'.$this->_subject;
			return $_out;
		}
	}
}
?>