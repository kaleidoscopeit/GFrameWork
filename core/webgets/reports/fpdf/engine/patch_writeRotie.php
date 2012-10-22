<?php
function writeRotie($x,$y,$txt,$text_angle,$font_angle = 0){
	if ($x < 0) {
		$x += $this->w;
	}
	if ($y < 0) {
		$y += $this->h;
	}

	/* Escape text. */
	$text = $this->_escape($txt);
	
	$font_angle += 90 + $text_angle;
	$text_angle *= M_PI / 180;
	$font_angle *= M_PI / 180;
	
	$text_dx = cos($text_angle);
	$text_dy = sin($text_angle);
	$font_dx = cos($font_angle);
	$font_dy = sin($font_angle);
	
	$s= sprintf('BT %.2f %.2f %.2f %.2f %.2f %.2f Tm (%s) Tj ET', $text_dx, $text_dy, $font_dx, $font_dy,$x * $this->k, ($this->h-$y) * $this->k, $text);
	if($this->underline && $txt!='')	$s.=' '.$this->_dounderline($x,$y,$txt);
	if($this->ColorFlag)	$s='q '.$this->TextColor.' '.$s.' Q';
	$this->_out($s);
}
?>