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
    $this->attributes['class'] = 'w02C0 ' 
                               . $_->ROOT->boxing($this->boxing)
                               . $_->ROOT->style_registry_add($this->style)
                               . $this->class;

    /* make the request id */
    $rqid = md5($this->workdir);
    $_->static[$rqid] = array(
      'workdir'   => $this->workdir,
      'mime'      => ($this->mime != '' ? $this->mime : null),
      'size'      => ($this->size != '' ? $this->size : null),
      'overwrite' => ($this->overwrite != '' ? true : null),
      'onrequest' => ($this->onrequest != '' ? $this->onrequest : null),
      'onload'    => ($this->onload != '' ? $this->onload : null),
      'onerror'   => ($this->onerror != '' ? $this->onerror : null),
      'onsuccess' => ($this->onsuccess != '' ? $this->onsuccess : null)
    );
    
    /* builds code */
    $_->buffer[] = '<input type="file" wid="02C0" rqid="' . $rqid . '"'
                 . $_->ROOT->format_html_attributes($this)
                 .'/>';
  }
}
?>