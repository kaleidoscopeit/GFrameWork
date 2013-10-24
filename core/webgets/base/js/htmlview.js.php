<?php
  $user = $_->static['auth']['user'];
  $_->static[$_->CALL_UUID]['js']['raw'][] = 
    '$_.env.user="' . $user['id'] . '";'
  . '$_.env.uname="' . $user['name'] . '";'
  . '$_.env.group="' . implode(',', $user['group']) . '";'
  . '$_.env.domain="' . @$user['domain'] . '";'
  . '$_.env.client=Array();'
  . '$_.env.client.engine="' . $_->static['client']['engine'] . '";';
   unset($user);
?>