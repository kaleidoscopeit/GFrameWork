<?php
/* helper : get the value of a nested array by passing a path */
function array_get_nested(&$arr, $path, $separator = '.')
{
  if(isset($arr)) if (!is_array($arr)) return false;

  $cursor = &$arr;
  $keys   = explode($separator, $path);

  foreach ($keys as $key) {
    if (isset($cursor[$key]))
    $cursor = &$cursor[$key];
    else
    return false;
  }

  return $cursor;
}

/* helper : substitute special chars in XML */
function clean_xml ($strin)
{
  $strout = null;

  for ($i = 0; $i < strlen($strin); $i++) {
    $ord = ord($strin[$i]);

    if (($ord > 0 && $ord < 32) || ($ord >= 127))
    $strout .= "&amp;#{$ord};";

    else {
      switch ($strin[$i]) {
        case '<': $strout .= '&lt;';   break;
        case '>': $strout .= '&gt;';   break;
        case '&': $strout .= '&amp;';  break;
        case '"': $strout .= '&quot;'; break;
        default: $strout  .= $strin[$i];
      }
    }
  }

  return $strout;
}

/* helper : generate an uuid v4 string */
function uuidv4_gen(){
  $data = openssl_random_pseudo_bytes(16);

  $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0010
  $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

  return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
?>
