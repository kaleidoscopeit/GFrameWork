<?php
$this->config->set ( '`policy`.`view`.`*`.`lock`', true, '' );
$this->config->set ( '`policy`.`view`.`login`.`allow.groups`', '*', '' );
$this->config->set ( '`policy`.`view`.`admin*`.`allow.groups`', 'administrators', '' );
$this->config->set ( '`policy`.`macro`.`*`.`lock`', true, '' );
$this->config->set ( '`policy`.`macro`.`login`.`allow.groups`', '*', '' );
$this->config->set ( '`policy`.`macro`.`admin*`.`allow.groups`', 'administrators', '' );
?>