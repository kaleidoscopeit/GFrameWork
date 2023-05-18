<?php
class base_codesnippet
{
  public $req_attribs = array(
    'style',
    'class',
    'field',
    'field_format',
    'hilight',
    'code',
    'valign',
    'halign'
  );

  function __define(&$_)
  {

  }

  function __flush(&$_)
  {
    /* builds syles */
    $style  = (isset($this->style) ? $this->style : '');
    $boxing = (isset($this->boxing) ? $this->boxing : '');

    $css_style  = 'class="w0050 '
                . $_->ROOT->boxing($boxing)
                . $_->ROOT->style_registry_add($style)
                . (isset($this->class) ? $this->class : '') . '" ';

    /* hilights */
    switch($this->hilight) {
      case 'xml' :
        $s = preg_replace(
          "|<([^/?])(.*)([\s\n])(.*)>|isU",
          "[1]<[2]\\1\\2[/2]\\3[5]\\4[/5]>[/1]",
          $this->code);

        $s = preg_replace(
          "|</(.*)>|isU",
          "[1]</[2]\\1[/2]>[/1]",
          $s);

        $s = preg_replace(
          "|<\?(.*)\?>|isU",
          "[3]<?\\1?>[/3]",
          $s);

        $s = preg_replace(
          "|\=\"(.*)\"|isU",
          "[6]=[/6][4]\"\\1\"[/4]",
          $s);

        $s = htmlspecialchars($s);

        $s = str_replace("\t","&nbsp;&nbsp;",$s);
        $s = str_replace(" ","&nbsp;",$s);

        $replace = array(
          1=>'0000FF',
          2=>'0000FF',
          3=>'800000',
          4=>'FF00FF',
          5=>'FF0000',
          6=>'0000FF');

        foreach($replace as $k=>$v) {
          $s = preg_replace(
            "|\[".$k."\](.*)\[/".$k."\]|isU",
            "<font color=\"#".$v."\">\\1</font>",
            $s);
        }
        $this->code = nl2br($s);
        break;
    }

    /* builds code */
    $_->buffer[] = '<div wid="0050" '
                 . $css_style
                 . $_->ROOT->format_html_attributes($this)
                 . '> ';

    $_->buffer[] = @$this->code;
    $_->buffer[] = '</div>';
  }

}
?>
