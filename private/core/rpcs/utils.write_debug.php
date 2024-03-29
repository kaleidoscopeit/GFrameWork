<?php
/*
 * call the event stack which belongs to the given task
 */

$rpc = array(array(

/*  message to write */

'message' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
      'variable:$_STDIN["message"]',
)),

/*  debug file  */

'file' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
      'string:"temp/debuglog.txt"',
))



),

/* rpc function */

function(&$_, $_STDIN, &$_STDOUT) use (&$self)
{
  if(!$_->settings['debug']) return TRUE;
  $message = explode("\n", $_STDIN['message']);

  date_default_timezone_set('Europe/Rome');

  foreach($message as $key=>$value) {
    $message[$key] = "[".date("Y-m-d H:i:s")."] ".
                     $_->CALL_OBJECT.":".
                     $_->CALL_URN." - ".
                     $value."\n";
  }

  file_put_contents($_STDIN['file'], implode("",$message), FILE_APPEND);

  return TRUE;
});
?>
