<?php

/* upload bridge
 *
 * Let to upload a file using the webget 'form:upload'
 *
 */

class _engine_upload {

  function __construct(&$_)
  {
    /* initialization */
    if(!$_->CALL_TARGET) die ('TARGET_NOT_SPECIFIED');
  }

  function build(&$_)
  {
    $sets = $_->static[$_->CALL_TARGET];
    $file = $_FILES[$_->CALL_TARGET];

    if(isset($sets['onrequest'])) eval($sets['onrequest']);

    // Check for errors
    if($file['error'] > 0) {
      switch ($file['error']) {
        case UPLOAD_ERR_INI_SIZE:
          $code    = "UPLOAD_ERR_INI_SIZE";
          $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
          break;
        case UPLOAD_ERR_FORM_SIZE:
          $code    = "UPLOAD_ERR_FORM_SIZE";
          $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
          break;
        case UPLOAD_ERR_PARTIAL:
          $code    = "UPLOAD_ERR_PARTIAL";
          $message = "The uploaded file was only partially uploaded";
          break;
        case UPLOAD_ERR_NO_FILE:
          $code    = "UPLOAD_ERR_NO_FILE";
          $message = "No file was uploaded";
          break;
        case UPLOAD_ERR_NO_TMP_DIR:
          $code    = "UPLOAD_ERR_NO_TMP_DIR";
          $message = "Missing a temporary folder";
          break;
        case UPLOAD_ERR_CANT_WRITE:
          $code    = "UPLOAD_ERR_CANT_WRITE";
          $message = "Failed to write file to disk";
          break;
        case UPLOAD_ERR_EXTENSION:
          $code    = "UPLOAD_ERR_EXTENSION";
          $message = "File upload stopped by extension";
          break;
        default:
          $message = "Unknown upload error";
          break;
      }

      $file['error'] = Array(
        "number"  => $file['error'],
        "code"    => $code,
        "message" => $message
      );

      if(isset($sets['onerror'])) eval($sets['onerror']);
      else die("An error ocurred when uploading.\nCode : " . $code
              . "\nMessage : " . $message);
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
  }

}

?>
