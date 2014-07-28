<?php

/* upload bridge
 *
 * Let to upload a file using the webget 'form:upload'
 *
 */

class _engine_upload {

  function init()
  {
    /* initialization */
    if(!$this->CALL_TARGET) die ('TARGET_NOT_SPECIFIED');
  }
  
  function build()
  {
    $_ = &$this;
    $sets = $this->static[$this->CALL_TARGET];
    $file = $_FILES[$this->CALL_TARGET];

    if(isset($sets['onrequest'])) eval($sets['onrequest']);
    
    // Check for errors
    if($file['error'] > 0) {
      if(isset($sets['onerror'])) eval($sets['onerror']);
      else die('An error ocurred when uploading.');
    }
        
    /* Check file type */
    if(isset($sets['mime']))
      if($file['type'] != $sets['mime'])
        die('Unsupported filetype uploaded.');

    /* Check file size */
    if(isset($sets['size']))
      if($file['size'] > $sets['size'])
        die('File uploaded exceeds maximum upload size.');

    if(isset($sets['onload'])) eval($sets['onload']);

    /* Force overwrite or return an error */
    if(!isset($sets['overwrite']))
      if(file_exists($sets['workdir'] . '/' . $file['name']))
        die('File with that name already exists.');

    if(isset($sets['workdir'])) {
    
      /* obtain the clear paths */
      $dest_dir  = explode('/', $sets['workdir'] . '/' . $file['name']);
      $dest_file = array_pop($dest_dir);
      $dest_dir  = implode('/', $dest_dir);
  
      /* create destination directory if needed */    
      if(!is_dir($dest_dir)) mkdir($dest_dir,0777 ,TRUE);
      
      /* move temporary file to the destination */
  
      if(!move_uploaded_file($file['tmp_name'],
          $sets['workdir'] . '/' . $file['name'])) {
        if(isset($sets['onerror'])) eval($sets['onerror']);
        else {
          echo('Error uploading file - check destination is writeable.');
          print_r($file);
          print_r($sets);
        }
      }

    }
    
    /* success */
    if(isset($sets['onsuccess'])) eval($sets['onsuccess']);
    else echo 'File uploaded successfully to "' 
          . $sets['workdir'] . '/' . $file['name'] . '"';
  }
 
}

?>