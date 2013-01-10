<?php
$this->config->set ( '`policy`.`views`.`*`.`lock`', false, '' );
$this->config->set ( '`policy`.`views`.`login`.`allow.groups`', '*', '' );
$this->config->set ( '`policy`.`views`.`admin*`.`allow.groups`', 'administrators', '' );
$this->config->set ( '`policy`.`macros`.`*`.`lock`', false, '' );
$this->config->set ( '`policy`.`macros`.`login`.`allow.groups`', '*', '' );
$this->config->set ( '`policy`.`macros`.`admin*`.`allow.groups`', 'administrators', '' );
?>