<?php
// This library is used to manage library. This mean you can get information from libraries, contained calls
// create new libraries, edit libraries, etc...

 class call_tools {
	// ------------------------------------------------------------------------
	// Return an array containing a list of the accessible calls (using embedded function 'call')

	function summary(&$_, $_buf, &$_out)
	{
		// Scans for system libs
		$path = $_->library_path.'srv/';
		$this->summary_sub($path);
		foreach($this->list as $key => $item){
			if(strpos($item, $_->library_path.'srv/')===0)
				$this->list[$key]='system.'.ltrim(substr($item, strlen($path),-8), '.');
				$this->calls($_, array('library'=>$this->list[$key]),$_out);
				
		}
		print_r($this->list);
	}

	// support function

	function summary_sub($path)
	{

		$dh = opendir($path);
		/* This is the correct way to loop over the directory. */
		while (false !== ($file = readdir($dh))) {
			if($file!='.' AND $file!='..'){
				if(is_dir($path.'/'.$file))$this->summary_sub($path.$file);
				else $this->list[]="$path.$file\n";
			}
    }
	}

	// ------------------------------------------------------------------------
	// Return an array containing a list of the accessible calls inside a library
	
	// param:library:string:1:library path
	
	function calls(&$_, $_buf, &$_out)
	{ 
		$context_path['system'] = $_->library_path.'srv/';
		$context_path['user'] = 'lib/';
		
		// convert from virtual path to real path
		$path = explode('.', $_buf['library']);
		$context = array_shift($path);
		$path = $context_path[$context] . implode('/', $path) . ".cl.php";

		// >>>>>>>>> ERROR BREAK POINT <<<<<<<<<<<
		// Simply checks if the library file exists
		if (!is_file($path)){
			$_out['error']['title'] = 'Library doesn\'t exists';
			$_out['error']['desc'] = 'Library : virtual path = "'.$_buf['library'].'" real path = "'.$path.'"';
			$_out['error']['caller'] = $this->_path.'.'.$this->_subject;		
			return false;
		}

		// load file in memory
		$source = file($path, FILE_IGNORE_NEW_LINES);
		
		// Verify if the primary structure is correct
		foreach($source as $key => $line){
			// 
			//

			#if(preg_match('/^(\s*class) +.+(\{.)*$/',$line))echo $line."\n";
			if(preg_match('/^(\s*function)\s*.*\s*\(\s*\Q&$_\E\s*,\s*\Q$_buf\E\s*,\s*\Q&$_out\E\s*\)( *[\{] *)$/',$line))echo $line."\n";
					
			// detect the class opening if not already in class			
			if(!$in_class){
				if(preg_match('/^(\/\/)/',$line))$class_comment[] = $line;
				if(preg_match('/^(\s*class) +((?!\{).)*$/',$line))$wait_class = true;
				if(preg_match('/^(\s*class) +.+(\{.)*$/',$line))$in_class = true;
				if($wait_class AND preg_match('/^(\s*{)/',$line))$in_class = true;
				if($wait_class AND preg_match('/^(^\/\/)/',$line))die( 'error');
				if($wait_class OR $in_class)$class_name .= $line;
				if($in_class)$class_name = preg_replace(array('/^(\s*class) */','/( *[\{]*.*)$/'),'',$class_name);
			}
			
			// if in class scrape the functions			
			else {
				if(!$in_funct){
					if(preg_match('/^(\/\/)/',$line))$function_comment[] = $line;
					if(preg_match('/^(\s*function)\s*.*\s*\(\s*\Q&$_\E\s*,\s*\Q$_buf\E\s*,\s*\Q&$_out\E\s*\)( *[^\{]* *)$/',$line)){
						$wait_class = true;
					//	echo $line;
						}
					if(preg_match('/^(\s*class) *.* *[\{.*]$/',$line))$in_class = true;
					if($wait_class AND preg_match('/^(\s*{)/',$line))$in_class = true;
					if($wait_class AND preg_match('/^(^\/\/)/',$line))die( 'error');
					if($wait_class OR $in_class)$class_name .= $line;
					if($in_class)$class_name = preg_replace(array('/^(\s*class) */','/ *[\{.*]$/'),'',$class_name);

					// detect class closing and do data collecting	
				}
				
				else { 
				}
			}



			//if($in_class AND preg_match('/^(\s*class) *.* *\{/',$line))
		}
			return;	 
		// >>>>>>>>> ERROR BREAK POINT <<<<<<<<<<<		
		if ($source[0]!='<?php') {
			$_out['error']['title'] = 'Library ';
			$_out['error']['desc'] = 'sql query : '.$qs."\nsql error :" . mysql_error($_buf['db_connection']);
			$_out['error']['caller'] = $this->_path.'.'.$this->_subject;		
			return false;
		}
		
		
		if($source[0]!='<?php')return 
		// convert the file into an 'anathomic'		

print_r($source);

	}
	
	// -----------------------------------------------------------------------------------------------------------
	// Checks for the required parameters
	
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