<?php
  if(isset($_->static['auth']['user'])) $user = $_->static['auth']['user'];
  else $user = array(
    'id'     => 'guest',
    'name'   => 'Guest',
    'group'  => 'nogroup',
    'domain' => 'nodomain'
  );

  $_->static[$_->CALL_URN]['js']['raw'][] =
    '$_.env.user="' . $user['id'] . '";'
  . '$_.env.uname="' . $user['name'] . '";'
  . '$_.env.group="' . @implode(',', (array)$user['group']) . '";'
  . '$_.env.teams="' . @implode(',', (array)$_->static['auth']['user']['teams']) . '";'
  . '$_.env.domain="' . @$user['domain'] . '";'
  . '$_.env.client={};'
  . '$_.env.client.engine="' . @$_->static['client']['engine'] . '";'
  . '$_.env.settings={};'
  . '$_.env.settings.cs_debug="' . @$_->settings['cs_debug'] . '";';

  unset($user);
?>
