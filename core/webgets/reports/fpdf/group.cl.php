<?php
class reports_fpdf_group
{
  public $req_attribs = array(
    'geometry',
    'rotation',
    'text_color',
    'draw_color',
    'font_family',
    'font_style',
    'font_size',
    'scale'                              // let to scale its nested webgets
  );

  function __define(&$_)
  {
    // webget geometry
    $this->geometry = explode(',',$this->geometry);
    $this->left     = $this->geometry[0];
    $this->top      = $this->geometry[1];
  }

  function __flush (&$_)
  {
    /* apply local styles */
    $_->ROOT->set_local_style($this);

    /* setup local coordinates */
    $this->offsetLeft = $this->left + $this->parent->offsetLeft;
    $this->offsetTop  = $this->top + $this->parent->offsetTop;

    $this->pxleft = $this->left;
    $this->pxtop = $this->top;
    $this->pxwidth = $this->parent->pxwidth;
    $this->pxheight = $this->parent->pxheight;

    /* paint contained webgets */
    gfwk_flush_children($this);

    /* restore parent styles, Void locally set styles */
    $_->ROOT->restore_style();
  }
}
?>
