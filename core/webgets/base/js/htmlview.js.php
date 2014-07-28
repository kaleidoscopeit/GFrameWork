<?php
  $user = $_->static['auth']['user'];
  $_->static[$_->CALL_URI]['js']['raw'][] = 
    '$$.env.user="' . $user['id'] . '";'
  . '$$.env.uname="' . $user['name'] . '";'
  . '$$.env.group="' . @implode(',', $user['group']) . '";'
  . '$$.env.domain="' . @$user['domain'] . '";'
  . '$$.env.client=Array();'
  . '$$.env.client.engine="' . $_->static['client']['engine'] . '";';
  unset($user);
?>