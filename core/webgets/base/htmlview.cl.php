<?php
class base_htmlview
{
  /* Contruct the ROOT webget and provides a number of variables
   * for the 'webgets define phase'
   */

  public $req_attribs = array(
    'css',
    'title',
    'namespace',
  );

  function __define(&$_)
  {
    /* Gets the client information */
    $_->static['client'] = $this->browser_info();

    /* sets ROOT placeholder */
    $_->ROOT = $this;

    /* setup css rules */
    $this->css_rules = array(
      'prefix' => (isset($_GET['ns']) ? $_GET['ns'] : 'sr'),
      'includes' => array()
    );

    $this->js_includes  =array();
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
      $library_js  = $_->WEBGETS_PATH . implode('/js/', $library);
      $library_css = $_->WEBGETS_PATH . implode('/css/', $library).'.css';



    /**************************************************************************/
    /*                           Javascript stuff                             */
    /**************************************************************************/

      /* includes static js files */
      if(is_file($library_js . '.js')) {
        $this->js_includes[$library_js . '.js'] = true;
      }

      /* includes dynamic js files */
      if(is_file($library_js.'.js.php')) {
        require $library_js . ".js.php";
      }
    }

    /* includes dynamic javascript realted to this view (ie [webget].js.php) */
    /* TODO : may be removed in the future for a better method */
    $this->js_includes['?js/' . $_->CALL_PATH] = true;



    /**************************************************************************/
    /*                                CSS stuff                               */
    /**************************************************************************/

    /* project global CSS */
    if ($_->CALL_OBJECT!='subview')
      $this->css = (!isset($this->css) ? "global" : $this->css .= ' global');

    /* current view attaced CSS plus global */
    if(isset($this->css))
      array_map(function($css) use (&$_){
        $_->ROOT->css_rules['includes']['css/'.$css.'.css'] = 1;
      }, explode(' ', trim($this->css)));

    /* static CSS file of this view */
    $this->css_rules['includes']['views/'.$_->CALL_URI.'/_this.css'] = 1;



    /**************************************************************************/
    /*                              flushes all view                          */
    /**************************************************************************/

    gfwk_flush_children($this);



    /**************************************************************************/
    /*                  Writes the code and flushes children                  */
    /**************************************************************************/

    switch($_->CALL_OBJECT){
      case 'views' :
        $top_code = array();

       // $top_code[] = '<!DOCTYPE html4>';
        $top_code[] = '<html>';
        $top_code[] = '<head>';
        $top_code[] = (isset($this->title) ?
                      '<title>'.$this->title.'</title>' : '');
        $top_code[] = '<meta http-equiv="Content-Type" '
                    . 'content="text/html;charset=UTF-8" />';

        array_map(function($css) use (&$top_code){
          $top_code[] = '<link rel="stylesheet" type="text/css" '
                      . 'href="' . $css .'" />';},
          array_keys($this->css_rules['includes']));

        $top_code[] = '<link rel="stylesheet" type="text/css" '
                    . 'href="' . '?css/webgets" />';

        array_map(function($js) use (&$top_code){
          $top_code[] = '<script type="text/JavaScript" src="'
                      . $js . '"></script>';},
          array_keys($this->js_includes));

        $top_code[] = '<script type="text/JavaScript" src="'
                    . 'views/'.$_->CALL_URI.'/_this.js' . '"></script>';

        $top_code[] = '<style type="text/css">';

        foreach($this->css_rules['registry'] as $index => $value) {
          $top_code[] = '.' . $this->css_rules['prefix']
                      . $index . '{'.$value."}";
        }
        $top_code[] = '</style>';
        $top_code[] = '</head>';
        $top_code[] = '<body wid="0000" '
                    . $_->ROOT->format_html_attributes($this)
                    . '>';

        $bottom_code = array('</body>', '</html>');

        break;

      case 'subview' :
        $top_code[] = "<!--\n"
                    . implode("\n", array_keys($this->css_rules['includes']))
                    . "\n\n"
                    . implode("\n", array_keys((array)$this->js_includes))
                    . "\nviews/".$_->CALL_URI.'/_this.js'
                    . "\n-->";

        /* embed style registry to avoid caching problems (had a problem in
           Firefox loading a subview in a iFrame) */
        $top_code[] = '<style type="text/css">';

        foreach($this->css_rules['registry'] as $index => $value) {
          $top_code[] = '.' . $this->css_rules['prefix']
          . $index . '{'.$value."}";
        }
        $top_code[] = '</style>';

        $top_code[] = '<div wid="0071" '
                     . $this->format_html_attributes($this).'>';
        $bottom_code = array('</div>');

        /* register collected view CSS */
        $_->static[$_->CALL_URI]['css'] = $this->css_rules;

        break;
    }

    $_->buffer = array_merge($top_code, $_->buffer, $bottom_code);
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

  function boxing($boxing,
                  $hsize    = "100%",
                  $vsize    = "100%",
                  $halign   = "center",
                  $valign   = "middle",
                  $hoffset  = "0px",
                  $voffset  = "0px",
                  $refer    = "parent" )
  {

    if($boxing == 'false') return null;

    $boxing = explode(',', $boxing) ;

    /* sets default values */
    //if(isset($boxing[0])) if($boxing[0] != "") $hsize = $boxing[0];
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

      if(!isset($bpc)) $bpc = 0;
      if(!isset($bpx)) $bpx = 0;

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
    Boxing positioning calculation table

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
   * Very very ugly code ...
   *
   */

  function system_error ($message)
  {
    global $_;
    $_->system_error_queue[] =
      '<div style="text-align:center;border:5px solid red;'.
      'background-color:pink;" class="'.$this->boxing('50%,50%').'">'.
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
   * html string   : preformatted html attributes
   *
   */

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
    global $_;

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
     * (e.g. "Firefox/2.0" or "MSIE 6.0" (This only matches the major and minor
     * version numbers.  E.g. "2.0.0.6" is parsed as simply "2.0"
     */

    $agent = strtolower($agent ? $agent : $_SERVER['HTTP_USER_AGENT']);

    $pattern = '#(?<browser>' . join('|', $known)
             . ')[/ ]+(?<version>[0-9]+(?:\.[0-9]+)?)#';

    /* Find all phrases (or return empty array if none found) */
    if (!preg_match_all($pattern, $agent, $matches)) return array();

    /* Since some UAs have more than one phrase (e.g Firefox has a Gecko phrase,
     *  Opera 7,8 have a MSIE phrase), use the last one found (the right-most one
     *  in the UA).  That's usually the most correct.
     */
    $i = count($matches['browser'])-1;

    switch ($matches['browser'][$i])
    {
      case 'firefox' :
      case 'gecko' :
        $engine = 'gecko';
        break;

      case 'msie' :
        $engine = 'trident';
        break;

      case 'safari' :
      case 'webkit' :
      case 'chrome' :
        $engine = 'webkit';
        break;

      case 'opera' :
        $engine = 'presto';
        break;
    }

    return array(
      'browser' => $matches['browser'][$i],
      'version' => $matches['version'][$i],
      $matches['browser'][$i]
                => $matches['version'][$i],
      'engine'  => $engine
    );
  }


  /* run-time database of inline styles (attempt to save code by making
   * dynamic sylesheet for repeated inline syles. Every rendered webget will get
   * a rule in it class proptery then all rules are sent to the client)
   *
   * accepts
   *
   * style     : a css syle string
   *
   * returns
   *
   * css rule  : a corresponding css rule
   *
   */

  function style_registry_add($style)
  {
    global $_;
    if($style == '') return null;
    $css_style = array_search($style, (array) @$this->css_rules['registry']);
    if($css_style!==false) return $this->css_rules['prefix'] . $css_style . ' ';
    $this->css_rules['registry'][] = $style;
    return $this->css_rules['prefix'] . (count($this->css_rules['registry'])-1).' ';
  }
}
?>
