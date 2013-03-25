<?php
// Configuration engine
//
// Manage the cool configuration database used by G-Framework
//
// The configuration database is a cool feautures of G-Framework, it is built with a static part
// and a dinamic part. The element of the database consisting of tree parts : name, value, permissions
// For now no complex permissions inheritance are applied but for the future will be
// Every object in the G-Framework zoo (views, report, webgets, ecc...) can get and set values,
// all values are dinamically stored in a file (or directly in a data base in the future) and will be
// available for every users of different role or positions.
//
// The configuration database is applied all over a signle application and as the intentions of the
// developer the database will be as a stack. i.e. one user can or cannot set their preferred value in a
// item of the database depending their role in the application

// Another cool idea is to load a further configurations file wich stores the specifics per-user preference 
class base_gideconf {
	function __construct () {
		$this->db = getcwd()."/vars/gideconf.php";
		// If the configuration DB exists will load it else will creates a new one 
		if ( is_file( $this->db ) )  {
			$this->config = file ( $this->db );
			$this->config = unserialize ( trim ( $this->config[1], '//' ) );
		}
	}

	function set ( $key, $value, $perm ) {
		$this->config[$key]->value = $value;
	}
	
	function get ( $key ) {
		return $this->config[$key]->value;
	}

	// Retrieve all sub keys starting from the specified key
	function get_subs ( $key ) {
		$keys = array_keys ( $this->config ) ;
		
		foreach ( $keys as $value ) {
			if ( substr ( $value , 0, strlen ( $key ) + 1 ) == $key . '.' );
			$temp = substr ( $value , strlen ( $key ) + 1 );
			$temp = explode ( '`.`' , trim ( $temp , '`' ) );
			$output[] = $temp[0];
		}
		
		return $output;
	}
	
	function del ( $key ) {
	}
		
	function __destruct () {
		$this->config = serialize ( $this->config );
		file_put_contents ( $this->db, "<?php\n//".$this->config."\n?>");
		
	}
}

$this->config = new base_gideconf;

// Load default configuration
include "gideconf.php";
?>
