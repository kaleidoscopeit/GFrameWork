<?php
  $user = $_->static['auth']['user'];
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
