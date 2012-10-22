<?php
class auth {

	// ===================================================================================================
	// Register user
	// ===================================================================================================
	
	// param:user:string:1:User name
	// param:pass:string:1:User password 

	// ===================================================================================================

	function login(&$_, $_buf, &$_out)
	{
		// Import authentication files
		$passwd = file('vars/auth/passwd.php');
		$groups  = file('vars/auth/group.php');

		// Cheks if the user exists
		foreach ($passwd as $record) {
			// Splits the database record
			$record = explode(':', trim ($record));
			
			// If at least in one record the user name exists try to authenticate
			if ('//'.$_buf['user'] == $record[0])
				
				// If also the md5 hash match the given password hash proceed with the authentication
				if (md5($_buf['pass']) == $record[2]) {
					$_->static['auth']['user']['id'] = 	substr($record[0], 2);
					$_->static['auth']['user']['name'] =	$record[1];

					// Imports group where the user seats
					foreach ($groups as $group) {
						$group = explode(':', $group);
						$users = explode(',', trim($group[1]));
						$group = substr($group[0],2);

						if (in_array($uid, $users)) $_->static['auth']['user']['group'][] = $group;						
					}

					// Executes the configured login function
					$_out = $this->_executes_login_function('accepted');
					if ($_out) return FALSE;					
					
					return TRUE;
				}
				
				else {
					// Executes the configured login function
					$_out = $this->_executes_login_function('wrong password');
					if ($_out) return FALSE;		
					
					// >>>>>>>>> ERROR BREAK POINT <<<<<<<<<<<		
					$error['title'] = "Authentication failed.";
					$error['caller'] = $this->_path.'.'.$this->_subject;
					$error['desc'][] = "Wrong password";
					$_out['error'] = $error;
					return FALSE;		
				}; 
		}

		// Executes the configured login function
		$_out = $this->_executes_login_function('wrong user');
		if ($_out) return FALSE;	
					
		// >>>>>>>>> ERROR BREAK POINT <<<<<<<<<<<		
		$error['title'] = "Authentication failed.";
		$error['caller'] = $this->_path.'.'.$this->_subject;
		$error['desc'][] = "Wrong user";
		$_out['error'] = $error;
		return FALSE;
	}

	
	// ===================================================================================================
	// Login function helper
	// ===================================================================================================
		
	function _executes_login_function($cause){
		global $_;
		
		if (!is_callable($_->settings['auth_login_event'])) return;

		// >>>>>>>>> ERROR BREAK POINT <<<<<<<<<<<
		if (!$_->settings['auth_login_event']($cause)){
			$_out['title'] = "Authentication failed.";
			$_out['caller'] = $this->_path.'.'.$this->_subject;
			$_out['desc'][] = "Authentication stack error!!!! Contact your sys admin.";
			return $_out;
		}
	}
	
	
	
	// ===================================================================================================
	// Various checks in order to determine if the access to the current object is allowed
	// ===================================================================================================
		
	function check(&$_, $_buf, &$_out)
	{
		// Temporary escape
		if (!isset($_->static['auth']['user']['id'])) {
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
	// Returns an array with the user name and the real name of the existing users 
	//	It's possible to apply a group based filter
	// ===================================================================================================
	
	// param:filter:array:0:Group name filter

	// ===================================================================================================

	function get_users_list(&$_, $_buf, &$_out)
	{
		// Loads the authentication files
		$passwd = file('vars/auth/passwd.php');
		$groups = file('vars/auth/group.php');
		$ulist = array();

		// Prepare the default result with all users
		foreach ($passwd as $record) {
			$record = explode(':', $record);
			if (substr($record[0], 0, 2) == '//') {
				$ulist[substr($record[0], 2)]['uid'] = substr($record[0], 2);
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
		if ($_buf['filter']){
			foreach ($ulist as $key => $user) {
				if (!array_intersect($_buf['filter'], $user['group'])) unset($ulist[$key]);
			}  
		}

		$_out = $ulist;
		return TRUE;
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


	// -----------------------------------------------------------------------------------------
	// Fetch user data
	//
	// param:user_id:string:1:User id

 	function get_user_info(&$_, $_buf, &$_out)
 	{
		// Check params
		$_out = $this->param_check($_, $_buf, array('user_id'));
		if ($_out) return FALSE;
		
		// Loads the authentication files
		$passwd = file('vars/auth/passwd.php');
		$groups = file('vars/auth/group.php');
		$ulist = array();
		
		// Prepare the default result with all users
		foreach ($passwd as $record) {
			$record = explode(':', $record);			
			if (substr($record[0], 2)==$_buf['user_id']) {
				$_out['uid'] = substr($record[0], 2);
				$_out['uname'] = $record[1];
				break;
			}
		}

		// >>>>>>>>> ERROR BREAK POINT <<<<<<<<<<<
		if (!isset($_out['uid'])){
			$error['title'] = "User not found.";
			$error['caller'] = $this->_path.'.'.$this->_subject;
			$error['desc'][] = "User not found.";
			$_out['error'] = $error;
			return FALSE;		
		}
		
		foreach ($groups as $group) {
			$group = explode (':', $group);
			if (substr($group[0], 0, 2) == '//' && trim($group[1]) != '') {
				$users = explode(',', $group[1]);
				if (in_array($_buf['user_id'],$users))
					$_out['group'] = substr($group[0], 2);
			}
		}					

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