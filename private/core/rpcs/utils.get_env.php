<?php
/*
 * Request the environment variables
 */

$rpc = array(array(

),

/* rpc function */

function(&$_, $_STDIN, &$_STDOUT) use (&$self)
{
  $_STDOUT = '$_.env.user="' . $_->static['auth']['user']['id'] . '";'
           . '$_.env.uname="' . $_->static['auth']['user']['name'] . '";'
           . '$_.env.group="' . @implode(',', $_->static['auth']['user']['group']) . '";'
           . '$_.env.teams="' . @implode(',', $_->static['auth']['user']['teams']) . '";'
           . '$_.env.domain="' . @$_->static['auth']['user']['domain'] . '";'
           . '$_.env.client={};'
           . '$_.env.client.engine="' . @$_->static['client']['engine'] . '";'
           . '$_.env.settings={};'
           . '$_.env.settings.cs_debug="' . @$_->settings['cs_debug'] . '";';

  return TRUE;
});
?>
