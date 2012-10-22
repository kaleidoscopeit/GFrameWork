<?php
// return javascript code by request
class locale {
	function __construct() {

		// build the language rank
		array_walk(
			explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']),
			function(&$value, $index, $languages){
				$value=explode(';', $value);
				$languages[$value[0]] = ($value[1] ? str_replace('q=', '', $value[1]) : 1);
			},
			&$this->languages
		);
		
		arsort($this->languages, SORT_NUMERIC);

		
		print_r($this->languages);
	}
	// Gives the requested message by the client locale setting.
	//
	// Requires :
	//
	// 	message_id		-> an existing message id ( format : [context].[message])
	//
	// Optional :
	//
	//		params			->	array where keys are sostituion labels for its connected values
	//
	// Returns :
	//
	//		message	-> translated message
	//		status	-> exit status of the function ( bit/flag composed response )
	//							b0 -> not found
	//							b1 -> default used
	//							b2 -> sobstitution values mismatch
	//
	//	Depends :
	//
	//		one or more paths where locale files are stored sets by lc_add_path() function. 
	//		one or more locale definition files named as the standard locale code plus '.php' at the end.

	function message(&$_, $_buf, &$_out) {
		foreach ($this->lc_path as $path ){
			foreach ($this->languages as $language => $score){
				$lang_symbol = substr($language, 0, 2);
				if ( is_file($path.$lang_symbol.'.php') ) require_once($path.$lang_symbol.'.php');
			}
		}	

		// Message check
		if(!$this->messages[$buffer['message_id']]){
			$out=array(
				'message' => 'Message not found ( id : \''.$buffer['message_id'].'\' )',
				'status'	=> 1
			);
			
			return;
		}
		
		// Message composition
		$message = $this->messages[$buffer['message_id']];
		if($buffer['params']){
			foreach($buffer['params'] as $label => $value){
				str_replace('{$'.$label.'}', $value, $message);
			}
		}
		
		$out = array(
			'message' => $message,
			'status' => 0
		);
	}

	function add_path($path) {
		
	}
		
	function lc_translate(){
		// uses a translation server API...
	}
	
	function lc_date() {
	}
	
	function lc_number() {
	}
	
	function lc_currency() {
		// currency exchange data provider...
	}
}
?>