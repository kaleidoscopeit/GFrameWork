<?php
/*
 * Various checks in order to determine if the access to the current object is 
 * allowed by the current user
 */

$rpc = array(array(

),

/* rpc function */
  
function(&$_, $_STDIN, &$_STDOUT) use (&$self)
{
  // Temporary escape
  if ($_->settings['auth_login_page'] &&
	    !isset($_->static['auth']['user']['id'])) {
	     
    $_STDOUT['STDERR'] = array(
      'signal'    => 'AUTH_USER_NOT_AUTHENTICATED',
      'call'      => array($self['name']));

    return FALSE;

	} 


	else return TRUE;	
		
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
});
?>
