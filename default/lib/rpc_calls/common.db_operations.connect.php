<?php

$rpc = array (array (

'data_source' => array (
  'type'     => 'array',
  'required' => true,
  'origin'   => array (
    'variable:$_->settings["rdbmsc"][$_STDIN["data_source"]]',
    'variable:$_->settings["rdbmsc"]["default"]'
))

),

/* rpc function */
 
function(&$_, $_STDIN, &$_STDOUT) use (&$self)
{
  $_STDOUT['connection'] = 
    mysql_connect (
      $_STDIN['data_source']['db_host'],
      $_STDIN['data_source']['db_user'],
      $_STDIN['data_source']['db_pass']
    ) or die ("Connessione non riuscita"); 
    
  mysql_select_db ($_STDIN['data_source']['db_name'])
    or die ("Selezione del database non riuscita");
    
  mysql_query("SET NAMES utf8;", $_STDOUT['connection']);

  return TRUE;
});  

?>