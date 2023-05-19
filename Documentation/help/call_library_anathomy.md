This document describes the srtucture of a call-library file.

There are two places where you can put a call-library :

- 'system' context : /private/core/rpcs
- 'user' context   : /private/[project-root]/rpcs


The name of the file is composed by an URN + .php, the URN is used to call the
RPC. 

Server CALLs are syncronous, Client CALLs are asynchronous

In order to a call an RPC the following syntax is used :

### Server Context (PHP) - Mock-up of a login check: 

$buffer = array(
    'user' => "User Name",
    'pass' => "Secret Password"
);

if (!_call("auth.login", $buffer)) {
	
	"$buffer" <- Contains a response, for example an error message;

}

### Client Context (JS) - Mock-up of a login check: 

var buffer = {
	'user' :  "User Name",
	'pass' : "Secret Password"
};

_call("auth.login", buffer, null, function(r,s) {
	// s contains the error code
    if(!s) {
		Alert ("Login Failed");
		return;
	}

	// Proceed with login operations
});





--

- Every library starts and ends always with the php block tag '<?php' and '?>'
- A call is an array with 2 elements : argument definition and anonymous function
- Inside the function call are available a number of reserved variables :
		$_       : reference to the 'buck'
		$_buffer : array of input arguments
		$_output : custom response
		$self    : similar to this, for now has a limited use
- The function has to return a true/false status
--

Argument definition

Array containing the definition of the input arguments, this data will be used
in order to obtain the source value of each input argument and validate it.

Strutcutre :

array(

	'[variable_name]'  => array (
	  'type'     => '[costraint : variable_type]',
  	'required' => [constraint : true or false],
  	'origin'   => array (
  	    'variable:$_buffer["sequence"]',
  	    '[personalized_origin]',
)),

variable_name  : the argument name, referenced as $_buffer[variable_name]
variable_type  : data type of argument, gives error if not match
required			 : gives error if the argument value is null
origin				 : array of origin sources, the first matched stops the stack.
								 2 type of origin (for now) :
								 - variable
								 - call
