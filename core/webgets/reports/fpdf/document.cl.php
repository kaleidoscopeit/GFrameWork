<?php
class reports_fpdf_document
{
  public $req_attribs = array(
    'geometry',
    'offset',
    'author',
    'creator',
    'title',
    'fonts',
    'fontpath'
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
    
    /* default offset if not specified */
    if(isset($this->offset)) {
      $this->offset = explode(',', $this->offset);

      if(!is_numeric($this->offset[0]))$this->offset[0] = '10';
      if(!is_numeric($this->offset[1]))$this->offset[1] = '10';
    }

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
      'draw_color',
      'font_family',
      'font_style',
      'font_size',
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
      'font_family',
      'font_style',
      'font_size',
    );

    if(is_array($styles)) $inheritables = array_merge($styles, $inheritables);     // allow custom inheritable styles    
    foreach($inheritables as $style) {
      if($style == "" || $style == null) continue;                                 // void cycle if style name is not given
      array_shift($this->style[$style]);
    }

    $this->update_styles();    
  }  
}
?>
