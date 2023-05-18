<?php
class base_dummydoc
{
  public $req_attribs = array();
  function __define(&$_) {$_->ROOT = $this;}
  function __flush(&$_) {
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=file.csv");
    header("Pragma: no-cache");
    header("Expires: 0");
  }
}
?>
