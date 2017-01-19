<?php
  if(isset($_->static['auth']['user'])) $user = $_->static['auth']['user'];
  else $user = array(
    'id' => 'guest',
    'name' => 'Guest',
    'group' => 'nogroup',
    'domain' => 'nodomain'
  );

  $_->static[$_->CALL_URI]['js']['raw'][] =
    '$$.env.user="' . $user['id'] . '";'
  . '$$.env.uname="' . $user['name'] . '";'
  . '$$.env.group="' . @implode(',', $user['group']) . '";'
  . '$$.env.domain="' . @$user['domain'] . '";'
  . '$$.env.client={};'
  . '$$.env.client.engine="' . $_->static['client']['engine'] . '";'
  . '$$.env.settings={};'
  . '$$.env.settings.cs_debug="' . $_->settings['cs_debug'] . '";';

  unset($user);
?>
