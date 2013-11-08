<?php
if(!$SG)die;
// Obtains informations fron datasources

// Check url against dangerous calls
$exturl=explode('/',$_GET['url']);
foreach($exturl as $value){if($value=='..'){die('BAD REQUEST(U)');}}
// Set location of the settings file
$dbc='../../'.$_SESSION['bld']['root'].'/dbc/'.$_GET['dbc'].'.php';
// Verify that file exists
is_file($dbc) or die ("WRONG PARAM");
// include settings file
include($dbc);
// checks table against dangerous calls
if(strstr($_GET['tbl'],"'")||strstr($_GET['tbl'],";")) die('BAD REQUEST(J)');

switch($db_type){
case 'mysql':
	$ds=@mysql_connect($db_host,$db_user,$db_password) or die("CANNOT CONNECT");
	// query database
	switch($_GET['req']){
		case 'tables' : // writes table list
			$qry=@mysql_list_tables($db_name,$ds);	
			while ($l=@mysql_fetch_row($qry)){$tbls[]=$l[0];}
			echo '["'.implode('","',$tbls).'"]';
		break;
		case 'fields' : // writes field list
			if(!$_GET['tbl']) die("WRONG PARAM");
			$qry=@mysql_list_fields($db_name,$_GET['tbl'],$ds) or die("WRONG PARAM");
			while($l=@mysql_fetch_field($qry)){$tbls[]=$l->name;};
			echo '["'.implode('","',$tbls).'"]';
			break;
		case 'tinfo' : // writes table info
			if(!$_GET['tbl']) die("WRONG PARAM");
			mysql_select_db($db_name,$ds);
			$qry="SHOW TABLE STATUS LIKE '".$_GET['tbl']."';";    
    		$rs=@mysql_query($qry) or die (mysql_error());
    		$rs=@mysql_fetch_assoc($rs);
    		$dim=explode('.',$rs['Data_length']/1024);
    		$dim[1]=($dim[1]!=''?substr($dim[1],0,2):'00');
    		echo '['.
    			'["'.$lc_msg['dns2js_4'].'","'.$rs['Name'].'"],'.
				'["'.$lc_msg['dns2js_5'].'","'.$rs['Rows'].'"],'.
				'["'.$lc_msg['dns2js_6'].'","'.implode('.',$dim).'K"]'.    			
    			']';
    		break;
	}
	mysql_close ($ds);
break;
case 'mssql':
	$ds=mssql_connect($db_host,$db_user,$db_password) or die("CANNOT CONNECT"); 
	mssql_select_db($db_name,$ds) or die ("CANNOT SELECT SCHEMA");;
	switch($_GET['req']){
		case 'tables' : // writes table list
			$qry=mssql_query("SELECT TABLE_SCHEMA,TABLE_NAME, OBJECTPROPERTY(object_id(TABLE_NAME), N'IsUserTable') AS type FROM INFORMATION_SCHEMA.TABLES",$ds);
			while($l=mssql_fetch_row($qry)){$tbls[]=$l[1];};
			echo '["'.implode('","',$tbls).'"]';
			break;
		break;
		case 'fields' : // writes field list
			if(!$_GET['tbl']) die("WRONG PARAM");
			$qry="select COLUMN_NAME from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME='".$_GET['tbl']."';";
			$rs=@mssql_query($qry) or die (mssql_error());
			while($m=mssql_fetch_row($rs)){$l[]=$m[0];};			
			echo '["'.implode('","',$l).'"]';
			break;
		case 'tinfo' : // writes table info
			if(!$_GET['tbl']) die("WRONG PARAM");
			$qry="EXEC sp_spaceused N'".$_GET['tbl']."';";    
    		$rs=@mssql_query($qry) or die (mssql_error());
    		$rs=@mssql_fetch_assoc($rs);
    		echo '['.
    			'["'.$lc_msg['dns2js_4'].'","'.$rs['name'].'"],'.
				'["'.$lc_msg['dns2js_5'].'","'.$rs['rows'].'"],'.
				'["'.$lc_msg['dns2js_6'].'","'.$rs['data'].'"],'.
				'["'.$lc_msg['dns2js_7'].'","'.$rs['index_size'].'"],'.
				'["'.$lc_msg['dns2js_8'].'","'.$rs['reserved'].'"]'.
    			']';
    		break;

	}
	mssql_close ($ds);
break;
}
?>