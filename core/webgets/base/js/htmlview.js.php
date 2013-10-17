<?php
  $_->static[$_->CALL_UUID]['js']['raw'][] = 
    '$_.env.user="' . $_->static['auth']['user']['id'] . '";'
  . '$_.env.uname="' . $_->static['auth']['user']['name'] . '";'
  . '$_.env.group="' . implode(',',$_->static['auth']['user']['group']) . '";'
  . '$_.env.domain="' . $_->static['auth']['user']['domain'] . '";'
  . '$_.env.client=Array();'
  . '$_.env.client.engine="' . $_->static['client']['engine'] . '";';
?>