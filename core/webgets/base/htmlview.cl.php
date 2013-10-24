<?php
class base_htmlview
{
  /* Contruct the ROOT webget and provides a number of variables 
   * for the 'webgets define phase' 
   */

  public $req_attribs = array(
    'style',
    'class',
    'css',
    'title'
  );
   
  function __define(&$_)
  {
    /* Gets the client information */ 
    $_->static['client'] = $this->browser_info();

    switch ($_->static['client']['browser'])
    {
      case 'firefox' : case 'gecko' :
        $_->static['client']['engine'] = 'gecko';
        break;
      case 'msie' :
        $_->static['client']['engine'] = 'trident';
        break;
      case 'safari' : case 'webkit' : case 'chrome' :
        $_->static['client']['engine'] = 'webkit';
        break;
      case 'opera' :
        $_->static['client']['engine'] = 'presto';
        break;
    }

    /* sets ROOT placeholder */
    $_->ROOT = $this;
    
    /* style registry prefix */
    $_->static[$_->CALL_UUID]['css']['prefix'] = ($this->id ? $this->id:'sr');
  }


  /* ROOT flush phase - responsible to trigger the 'flushing chain reaction' */

  function __flush(&$_)
  {
    /**************************************************************************/
    /*                         Sets-up inclusions                             */
    /**************************************************************************/
    
    /* static and dynamic webgets specific CSS and JS inclusions */
    foreach ($_->libraries as $library) {
      
      $library = explode('/', $library);
      $library_js = $_->WEBGETS_PATH.implode('/js/', $library).'.js';
      $library_css = $_->WEBGETS_PATH.implode('/css/', $library).'.js';

    /**************************************************************************/
    /*                           Javascript stuff                             */
    /**************************************************************************/
    
      if($_->CALL_OBJECT!='subview'){       
        /* includes static js files */
        if(is_file($library_js)) $js_includes[$library_js] = true;
        
        /* includes dynamic js files */
        if(is_file($library_js.'.php')) require $library_js . ".php";
      }
    }

    $js_includes['?js/view' . '&' . $_->CALL_UUID] = true;

    /**************************************************************************/
    /*                                CSS stuff                               */
    /**************************************************************************/
    
    /* project global CSS */
    if(is_file('css/global.css')) $css_includes['css/global.css'] = 1;

    /* project selected CSS by this view */
    if($this->css != '') {
      $css_enabled = explode(' ', $this->css);
      foreach($css_enabled as $css)
        if(is_file('css/'.$css.'.css')) $css_includes['css/'.$css.'.css'] = 1;
    }

    /* static CSS file of this view */
    if(is_file('views/'.$_->CALL_SOURCE.'/_this.css'))
      $css_includes['views/'.$_->CALL_SOURCE.'/_this.css'] = 1;

    /* register collected view CSS */
    $_->static[$_->CALL_UUID]['css']['files'] = $css_includes;
    unset($css_includes);

    /**************************************************************************/
    /*                  Writes the code and flushes children                  */
    /**************************************************************************/

    switch($_->CALL_OBJECT){
      case 'view' :
      case 'views' :
    $_->buffer = array(); 
        $_->buffer[] = '<!DOCTYPE HTML5>';
        $_->buffer[] = '<html>';
        $_->buffer[] = '<head>';
        $_->buffer[] = (isset($this->title) ? 
                       '<title>'.$this->title.'</title>' : '');
        $_->buffer[] = '<meta http-equiv="Content-Type" '
                     . 'content="text/html;charset=UTF-8">';

        $_->buffer[] = '<link rel="stylesheet" type="text/css" '
                     . 'href="?css/webgets" />';
        $_->buffer[] = '<link rel="stylesheet" type="text/css" '
                     . 'href="?css/view&' . $_->CALL_UUID . '" />';
                     
        foreach((array) @$js_includes as $js => $status)
          $_->buffer[] = '<script type="text/JavaScript" src="'
                       . $js . '"></script>';

        $_->buffer[] = '<body wid="0000" class="'.$this->class.'" '
                     . $_->ROOT->format_html_attributes($this)
                     .'>';
 
        $bottom_code = array('</body>', '</html>');

        break;

      case 'subview' :
           $_->buffer[] = "<!--\n"
                        . "?css/view&" . $_->CALL_UUID
                        . "\n\n"
                        . implode("\n", array_keys((array)$js_includes))
                        . "\n-->";

        $_->buffer[] = '<div wid="0071" '
                     . $this->format_html_events($this).'>';

        $bottom_code = array('</div>');
        
        break;
    }
 
    gfwk_flush_children($this);

    $_->buffer = array_merge($_->buffer, $bottom_code);

  }
  


  /* Returns a preformatted style following the "Global Positioning Rules"
   *
   * accepts
   *
   * boxing :
   *
   *  'null'     means default positioning
   *  'false'    means no "Global Positioning Rules" has to be applied
   *
   */

  function boxing( 
    $boxing,
    $hsize    = "100%",
    $vsize    = "100%",
    $halign   = "center", 
    $valign   = "middle",   
    $hoffset  = "0px", 
    $voffset  = "0px",
    $refer    = "parent"
  ) {

    if($boxing == 'false') return null;
    
    $boxing = explode(',', $boxing) ;
    
    /* sets default values */
    $hsize    = (@($boxing[0] == "") ? $hsize   : $boxing[0]);
    $vsize    = (@($boxing[1] == "") ? $vsize   : $boxing[1]);
    $halign   = (@($boxing[2] == "") ? $halign  : $boxing[2]);
    $valign   = (@($boxing[3] == "") ? $valign  : $boxing[3]);
    $hoffset  = (@($boxing[4] == "") ? $hoffset : $boxing[4]);
    $voffset  = (@($boxing[5] == "") ? $voffset : $boxing[5]);
    $refer    = (@($boxing[6] == "") ? $refer   : $boxing[6]);
    
    /* sets to zero the internal variables */
    $lpc = $lpx = $tpc = $tpx = $wpc = $wpx = $hpc = $hpx = 0;

    /* determine if positional value is passed as pixel or percent */    
    strpos($hsize, '%')   ? $wpc = str_replace("%", null, $hsize)    
      : $wpx = str_replace("px", null, $hsize);
    strpos($vsize, '%')   ? $hpc = str_replace("%", null, $vsize)    
      : $hpx = str_replace("px", null, $vsize);
    strpos($hoffset, '%') ? $lpc = str_replace("%", null, $hoffset)  
      : $lpx = str_replace("px", null, $hoffset);
    strpos($voffset, '%') ? $tpc = str_replace("%", null, $voffset)  
      : $tpx = str_replace("px", null, $voffset);

    if (strpos($halign, '%') || strpos($halign, 'px'))
      strpos($halign, '%') ? $rpc = str_replace("%", null, $halign) 
       : $rpx = str_replace("px", null, $halign);

    if (strpos($valign, '%') || strpos($valign, 'px'))
      strpos($valign, '%') ? $bpc = str_replace("%", null, $valign) 
        : $bpx = str_replace("px", null, $valign);


    $position['root']   = "position:fixed;";
    $position['parent'] = "position:absolute;";

    
    $hpos['left']   = $hoffset != 0 ? "left:".$hoffset.";" : null;
    $hpos['center'] = "left:".round(0+$lpx-$wpx/2,2)."px;margin-left:".
                      round(50-$wpc/2+$lpc,2)."%;";
    $hpos['right']  = "right:".-($lpc + $lpx).($lpx == "0" ? "%" : "px").";";
    
    $vpos['top']    = "top:".$voffset.";";
    $vpos['middle'] = 'top:'.round(50-$hpc/2+$tpc,2).'%;margin-top:'.
                      round(0-$hpx/2+$tpx,2).'px;';
    $vpos['bottom'] = "bottom:".-($tpc + $tpx).($tpx == "0" ? "%" : "px").";";

    if (isset($rpc) || isset($rpx)) {
      $lef = $wpx+$lpx;$mlef
           = $wpc+$lpc;$rig
           = $rpx-$lpx;$mrig
           = (isset($rpc) ? $rpc : 0 )-$lpc;
      
      $hparams = 'left:'.$lef.'px;'.
                 ($mlef != 0 ? 'margin-left:'.$mlef.'%;' : '').
                 'right:'.$rig.'px;'.
                 ($mrig != 0 ? 'margin-right:'.$mrig.'%;' : '');
    }
    
    else $hparams = 
      "width:".$hsize.";".$hpos[$halign].';';

    if (isset($bpc) || isset($bpx)) {

      $top  = $hpx+$tpx;
      $mtop = $hpc+$tpc;
      $bot  = $bpx-$tpx;
      $mbot = $bpc-$tpc;

      $vparams = 'top:'.$top.'px;'.
                 ($mtop != 0 ? 'margin-top:'.$mtop.'%;' : '').
                 'bottom:'.$bot.'px;'.
                 ($mbot != 0 ? 'margin-bottom:'.$mbot.'%;' : '');
    }
    
    else $vparams = 
      ($vsize != 0 ? "height:".$vsize.";" : null).$vpos[$valign].';';

    return 
      $this->style_registry_add($position[$refer].$hparams.$vparams);
  }
  
  
  /*
    Tavola per il calcolo  del posizionamento nella funzione boxing
    
    root    lt  height(px|%)  width(px|%)  left=0+left(px|%)                                top=0+top(px|%)      
    root    ct  height(px|%)  width(px|%)  left=0+left(px)-width/2(px)                      top=0+top(px|%)                                                                    margin-left=50-width/2(%)+left(%)      
    root    rt  height(px|%)  width(px|%)                                right=0-left(px|%) top=0+top(px|%)    
    root    lm  height(px|%)  width(px|%)  left=0+left(px|%)                                top=50%-height(%)/2?+top(%)?                      margin-top=-height(px)+top(px)?
    root    cm  height(px|%)  width(px|%)  left=0+left(px)-width/2(px)?                     top=50%-height(%)/2?+top(%)?                      margin-top=-height(px)+top(px)?  margin-left=50-width/2(%)+left(%)
    root    rm  height(px|%)  width(px|%)                                right=0-left(px|%) top=50%-height(%)/2?+top(%)?                      margin-top=-height(px)+top(px)?
    
    root    lb  height(px|%)  width(px|%)  left=0+left(px|%)                                                              bottom=0-top(px|%)
    root    cb  height(px|%)  width(px|%)  left=0+left(px)-width/2(px)?                                                   bottom=0-top(px|%)                                   margin-left=50-width/2(%)+left(%)
    root    rb  height(px|%)  width(px|%)                                right=0-left(px|%)                               bottom=0-top(px|%)
    
    parent  lt  height(px|%)  width(px|%)  left=0+left(px|%)                                top=0+top(px|%)
    parent  ct  height(px|%)  width(px|%)  left=0+left(px)-width/2(px)?                     top=0+top(px|%)                                                                    margin-left=50-width/2(%)+left(%)
    parent  rt  height(px|%)  width(px|%)                                right=0-left(px|%) top=0+top(px|%)
    
    parent  lm  height(px|%)  width(px|%)  left=0+left(px|%)                                top=50%-height(%)/2?+top(%)?                      margin-top=-height(px)+top(px)?
    parent  cm  height(px|%)  width(px|%)  left=0+left(px)-width/2(px)?                     top=50%-height(%)/2?+top(%)?                      margin-top=-height(px)+top(px)?  margin-left=50-width/2(%)+left(%)
    parent  rm  height(px|%)  width(px|%)                                right=0-left(px|%) top=50%-height(%)/2?+top(%)?                      margin-top=-height(px)+top(px)?
    
    parent  lb  height(px|%)  width(px|%)  left=0+left(px|%)                                                              bottom=0-top(px|%)
    parent  cb  height(px|%)  width(px|%)  left=0+left(px)-width/2(px)?                                                   bottom=0-top(px|%)                                   margin-left=50-width/2(%)+left(%)
    parent  rb  height(px|%)  width(px|%)                                right=0-left(px|%)                               bottom=0-top(px|%)
    
    $halign =
    l  ->  left=0+left(px|%)
    c  ->  left=0+left(px)-width/2(px)  margin-left=50-width/2(%)+left(%)
    r  ->  right=0-left(px|%)
    
    $valign =
    t  ->  top=0+top(px|%)
    m  ->  top=50%-height(%)/2?+top(%)? margin-top=-height(px)+top(px)?
    b  ->  bottom=0-top(px|%)
  */



  /* forces one or more error messages on top of the view
   *
   * Very very bad code ...
   *  
   */
  
  function system_error ($message)
  {
    global $_;
    $_->system_error_queue[] = 
      '<div style="text-align:center;border:5px solid red;'.
      'background-color:pink;'.$this->boxing('50%,50%,center,middle,root').'">'.
      '<div align="center" valign="top" style="position:absolute;top:0;'.
      'right:0;left:0;bottom:50px;overflow:auto;"><br>ERROR!!<br><br>'.
      $message.'</div>'.
      '<div style="position:absolute;bottom:10px;width:100%;">'.
      '<button onclick="parentNode.parentNode.parentNode.'.
      'removeChild(parentNode.parentNode);">OK</button></div>'.
      '</div>';
  }


  /* Shorcut for the webget builder classes.
   *
   * Returns a preformatted html-style properties which a webget class can 
   * insert into their html tag
   *
   * accepts
   *
   * webget        :the webget itself as a subjet
   *
   * returns
   *
   * empty string  : no event matched
   * html string   : preformatted html events
   *
   */
   
  function format_html_events ($webget)
  {
    //    return;
    return 
      (isset($webget->onclick) ?
        'onclick="'.$webget->onclick.'" ' : '').
      (isset($webget->onmousedown) ? 
        'onmousedown="'.$webget->onmousedown.'"' : '').
      (isset($webget->onmouseup) ?
        'onmouseup="'.$webget->onmouseup.'" ' : '').
      (isset($webget->onmouseover) ?
        'onmouseover="'.$webget->onmouseover.'" ' : '').
      (isset($webget->onmouseout) ?
        'onmouseout="'.$webget->onmouseout.'" ' : '').
      (isset($webget->onkeydown) ?
        'onkeydown="'.$webget->onkeydown.'" ' : '').
      (isset($webget->onkeyup) ?
        'onkeyup="'.$webget->onkeyup.'"' : '').
      (isset($webget->onkeypress) ?
        'onkeypress="'.$webget->onkeypress.'" ' : '').
      (isset($webget->ready) ?
        'ready="'.$webget->ready.'" ' : '').
      (isset($webget->onchange) ?
        'onchange="'.$webget->onchange.'" ' : '').
      (isset($webget->onscroll) ?
        'onscroll="'.$webget->onscroll.'" ' : '').
      (isset($webget->onblur) ?
        'onblur="'.$webget->onblur.'" ' : '').
      (isset($webget->onfocus) ?
        'onfocus="'.$webget->onfocus.'" ' : '');
  }
  
  
  function format_html_attributes ($webget)
  {
    unset($webget->attributes['wid']);
    foreach($webget->attributes as $key => $value)
      if(is_string($value))
        $out[] = $key . '="' . $value . '"';

    return implode(' ', (array) @$out)." ";
  }
  
  /* Makes an array with the browser information 
   *
   * returns (model)
   *
   * array (
   *   [firefox]  => 3.5
   *   [browser] => firefox
   *   [version] => 3.5
   * )
   *
   */
   
  function browser_info ($agent=null)
  {
    /* Declare known browsers to look for */
    $known = array(
      'msie',
      'firefox',
      'safari',
      'webkit',
      'opera',
      'netscape',
      'konqueror',
      'gecko',
      'chrome'
    );
  
    /* Clean up agent and build regex that matches phrases for known browsers
       (e.g. "Firefox/2.0" or "MSIE 6.0" (This only matches the major and minor
       version numbers.  E.g. "2.0.0.6" is parsed as simply "2.0" */
    $agent = strtolower($agent ? $agent : $_SERVER['HTTP_USER_AGENT']);
    
    $pattern = '#(?<browser>'.
               join('|', $known).
               ')[/ ]+(?<version>[0-9]+(?:\.[0-9]+)?)#';
  
    /* Find all phrases (or return empty array if none found) */
    if (!preg_match_all($pattern, $agent, $matches)) return array();
  
    /* Since some UAs have more than one phrase (e.g Firefox has a Gecko phrase,
       Opera 7,8 have a MSIE phrase), use the last one found (the right-most one
       in the UA).  That's usually the most correct. */
    $i = count($matches['browser'])-1;
    
    return array(
      'browser' => $matches['browser'][$i],
      'version' => $matches['version'][$i],
      $matches['browser'][$i] => $matches['version'][$i]);
  }



  /* inline style registry (attempt to save code by making
     dynamic sylesheet for repeated inline syles) */ 
    
  function style_registry_add($style)
  {
    global $_;
    $css_static = &$_->static[$_->CALL_UUID]['css'];

    if($style == '') return null;
    
    $css_style = array_search($style, (array) @$css_static['registry']);
      
    if($css_style!==false) return $css_static['prefix'] . $css_style . ' ';

    $css_static['registry'][] = $style;
    
    return $css_static['prefix'] . (count($css_static['registry'])-1).' ';
  }
}
?>
