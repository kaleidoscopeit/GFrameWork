<?php
//ini_set('display_errors',1);


class reports_csv_document
{
  public $req_attribs = array(
    'field_separator',
    'text_separator'
  );

  function __define(&$_)
  {
    /* sets ROOT placeholder */
    $_->ROOT = $this;

    $lib_path    = $_->CORE_PATH . "/webgets/reports/fpdf/lib/";
    $plugin_path = $lib_path . "/plugins/";

    /* setup fpdf libray and its plugins */
    require($lib_path . "fpdf.php");

    /* scans for plugins and load them (see plugin register rules) */
    $plugins = array_diff(scandir($plugin_path),
    array("..", ".", "readme.txt"));

    if(count($plugins) == 0)
    $last_plugin = 'FPDF';

    else {
      foreach($plugins as $plugin)
      require($plugin_path . $plugin);
      $last_plugin = array_shift(explode('_', array_pop($plugins)));
    }

    /* make the instance of the library */
    $this->fpdf = new $last_plugin();

    /* if defined set font path relative to the application path */
    if(isset($this->fontpath)) {
      $this->fpdf->fontpath = getcwd() . "/" . $this->fontpath . "/";
    }

    /* declares available fonts */
    $this->fonts = explode(',', $this->fonts);

    /* declares default styles by initializine 'style' database */
    $this->style = array();
    $this->style['text_color'][0]  = '0,0,0';
    $this->style['draw_color'][0]  = '0,0,0';
    $this->style['fill_color'][0]  = '255,255,255';
    $this->style['font_family'][0] = 'arial';
    $this->style['font_style'][0]  = '';
    $this->style['font_size'][0]   = '10';
    $this->style['line_width'][0]  = '0';

    /* set styles */
    $this->update_styles();
    $this->fpdf->SetMargins(0,0);
  }


  function __flush (&$_)
  {
    @$this->fpdf->setAuthor($this->author,1);
    @$this->fpdf->setTitle($this->title,1);
    @$this->fpdf->setCreator($this->creator,1);

    /* import fonts */
    foreach($this->fonts as $font){
      $font = explode(' ', $font);
      $this->fpdf->AddFont($font[0],@$font[1]);
    }

    $this->fpdf->SetAutoPageBreak('',5);

    /* flushes only 'reports_fpd:chapter' childrens */
    gfwk_flush_children($this, 'reports_fpdf_chapter');

    /* trigger the pdf document creation */
    $this->fpdf->Output();
  }
}
?>
