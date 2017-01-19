<?php
//ini_set('display_errors',1);


class reports_fpdf_document
{
  public $req_attribs = array(
    'author',
    'creator',
    'title',
    'fonts',
    'fontpath',
    /* common document flow attributes */
    'text_color',
    'draw_color',
    'fill_color',
    'font_family',
    'font_style',
    'font_size',
    'line_width'
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
      /* load all plugins found */
      foreach($plugins as $plugin)
        require($plugin_path . $plugin);

      /* get last found plugin in order to instantiate it */
      $last_plugin = explode('_', array_pop($plugins));
      $last_plugin = array_shift($last_plugin);
    }

    /* make the instance of the library */
    $this->fpdf = new $last_plugin();

    /* if defined set font path relative to the application path */
    if(isset($this->fontpath)) {
      $this->fpdf->fontpath = getcwd() . "/" . $this->fontpath . "/";
    }

    /* declares available fonts */
    if(isset($this->fonts)) $this->fonts = explode(',', $this->fonts);

    /* declares default styles by initializine 'style' database */
    $this->style = array();
    $this->style['text_color'][0]  = '0,0,0';
    $this->style['draw_color'][0]  = '0,0,0';
    $this->style['fill_color'][0]  = '';
    $this->style['font_family'][0] = 'helvetica';
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
    if(isset($this->fonts))
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

  /* Update global styles which are applied to all elements in the document flow */
  function update_styles ()
  {
    if(isset($this->style['text_color'][0])){
      $text_color = explode(',', $this->style['text_color'][0]);
      $this->fpdf->SetTextColor($text_color[0], @$text_color[1],
                                @$text_color[2]);
    }

    if(isset($this->style['draw_color'][0])){
      $draw_color = explode(',', $this->style['draw_color'][0]);
      $this->fpdf->SetDrawColor($draw_color[0], @$draw_color[1],
                                @$draw_color[2]);
    }

    if(isset($this->style['fill_color'][0])){
      $fill_color = explode(',', $this->style['fill_color'][0]);
      $this->fpdf->SetFillColor($fill_color[0], @$fill_color[1],
                                @$fill_color[2]);
    }

    if(isset($this->style['line_width'][0])){
      $this->fpdf->SetLineWidth($this->style['line_width'][0]);
    }

    $this->fpdf->SetFont(@$this->style['font_family'][0],
                         @$this->style['font_style'][0],
                         @$this->style['font_size'][0]);

//    $this->fpdf->SetLineWidth(@$this->style['line_width'][0]);
  }


  function set_local_style (&$webget, $styles = null)
  {
    $inheritables = array                                                         // defines common inheritable styles
    (
      'text_color',
      'fill_color',
      'draw_color',
      'font_family',
      'font_style',
      'font_size',
      'line_width'
    );

    if(is_array($styles)) $inheritables = array_merge($styles, $inheritables);     // allow custom inheritable styles
    foreach($inheritables as $style) {                                             // apply inherited style
      if($style == "" || $style == null) continue;                                 // void cycle if style name is not given
      if(!isset($webget->$style))                                                  // if the webget hasn't its own style sets the parent one
        $webget->$style = $this->style[$style][0];
      array_unshift($this->style[$style], $webget->$style);                        // in any case put the style at the top of the stack
    }
    $this->update_styles();                                                        // refresh styles in pdf composition
  }

  function set_local_style_ext ($style_name, &$webget)
  {
    if($style_name == null) return false;                                          // returns if style name is not given
    if(!isset($webget->$style_name))
      $webget->$style_name = $this->style[$style_name][0];
    else
      array_unshift($this->style[$style_name], $webget->$style_name);
    //$this->update_styles();
  }

  function get_local_style ($style_name)
  {
    if($style_name == null) return false;
    return $this->style[$style_name][0];
  }


  function restore_style ($styles = null)
  {
    $inheritables = array                                                         // defines common inheritable styles
    (
      'text_color',
      'draw_color',
      'fill_color',
      'font_family',
      'font_style',
      'font_size',
      'line_width'
    );

    if(is_array($styles)) $inheritables = array_merge($styles, $inheritables);     // allow custom inheritable styles
    foreach($inheritables as $style) {
      if($style == "" || $style == null) continue;                                 // void cycle if style name is not given
      array_shift($this->style[$style]);
    }

    $this->update_styles();
  }

  function calc_real_geometry($obj) {
    $geometry = [
      'left'   => 'pxwidth',
      'top'    => 'pxheight',
      'width'  => 'pxwidth',
      'height' => 'pxheight'
    ];

    foreach($geometry as $param => $refer) {
      $pxparam = "px" . $param;

      $obj->$pxparam   = $obj->$param;

      if(substr($obj->$param, -1) == '%')
        $obj->$pxparam = substr($obj->$param, 0, strlen($obj->$param) -1)
                       * $obj->parent->$refer / 100;
    }
  }
}
?>
