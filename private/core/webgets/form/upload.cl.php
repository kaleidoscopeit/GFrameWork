<?php
class form_upload
{
  public $req_attribs = array(
    'workdir',
    'mime',
    'size',
    'overwrite',
    'onrequest',
    'onload',
    'onerror',
    'onsuccess'
  );

  function __define(&$_)
  {
    if(isset($this->attributes['id']))
      $this->attributes['name'] = $this->attributes['id'];
  }

  function __flush(&$_)
  {
    /* builds syles */
    $style  = (isset($this->style) ? $this->style : '');
    $boxing = (isset($this->boxing) ? $this->boxing : '');
    $class  = (isset($this->class) ? $this->class : '');

    $this->attributes['class'] = 'w02C0 '
                               . $_->ROOT->boxing($boxing)
                               . $_->ROOT->style_registry_add($style)
                               . $class;

    /* make the request id */
    $rqid = md5($this->workdir);
    $_->static[$rqid] = array(
      'workdir'   => $this->workdir,
      'mime'      => (isset($this->mime)      ? $this->mime : null),
      'size'      => (isset($this->size)      ? $this->size : null),
      'overwrite' => (isset($this->overwrite) ? true : null),
      'onrequest' => (isset($this->onrequest) ? $this->onrequest : null),
      'onload'    => (isset($this->onload)    ? $this->onload : null),
      'onerror'   => (isset($this->onerror)   ? $this->onerror : null),
      'onsuccess' => (isset($this->onsuccess) ? $this->onsuccess : null)
    );

    /* builds code */
    $_->buffer[] = '<input type="file" wid="02C0" rqid="' . $rqid . '"'
                 . $_->ROOT->format_html_attributes($this)
                 .'/>';
  }
}
?>
