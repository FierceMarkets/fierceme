<?php
$pub = trim($_GET['pub']);
$font = 'eurostilebold.ttf';

/*header('Content-Type: image/png');

$im = imagecreatetruecolor(375, 72);

imagealphablending($im, false);
imagesavealpha($im, true);

$trans = imagecolorallocatealpha($im, 255, 255, 255, 127);
$black = imagecolorallocate($im, 0, 0, 0);
imagefilledrectangle($im, 0, 0, 375, 72, $trans);

imagealphablending($im, true);
imagettftext($im, 29, 0, 0, 72, $black, $font, $text);

imagepng($im);
imagedestroy($im);*/


$STRING = $pub;

// ---- PRESETS
$FONT = $font;
$SCALE = 8;
$FONT_SIZE = 44;
//$WIDTH = 372;
$WIDTH = strlen($STRING) * $FONT_SIZE;
$HEIGHT = 72;
$KERNING = 15;
$BASELINE = 44 * (12/10.5);
$BG_COLOR = array(
    "R"=>0,
    "G"=>0,
    "B"=>0
);
$BLUE = array(
    "R"=>0,
    "G"=>0,
    "B"=>153
);
$ORANGE = array(
    "R"=>255,
    "G"=>102,
    "B"=>0
);

// ---- CREATE CANVAS + PALETTE
$canvas = imageCreateTrueColor($WIDTH* $SCALE,$HEIGHT* $SCALE);

$blue = imageColorAllocate($canvas, $BLUE["R"], $BLUE["G"], $BLUE["B"]);
$temp = imagettftext($canvas, $FONT_SIZE, 0, 0, 0, $blue, $FONT, $pub);
$w = $temp[2] - $KERNING;

$WIDTH = $w;

imagealphablending($canvas, false);
imagesavealpha($canvas, true);

$bg_color = imageColorAllocate($canvas, $BG_COLOR["R"], $BG_COLOR["G"], $BG_COLOR["B"]);

$orange = imageColorAllocate($canvas, $ORANGE["R"], $ORANGE["G"], $ORANGE["B"]);
$trans = imagecolorallocatealpha($canvas, 255, 255, 255, 127);

//imagefill ( $canvas, 0, 0, $bg_color );
imagefilledrectangle($canvas, 0, 0, $WIDTH*$SCALE, $HEIGHT*$SCALE, $trans);
imagealphablending($canvas, true);

// ---- DRAW

$array = str_split($STRING);
$hpos = 0;

for($i=0; $i<count($array); $i++)
{
	if($i<6)
	{
		$bbox = imagettftext( $canvas, $FONT_SIZE* $SCALE, 0, $hpos, $BASELINE* $SCALE, $blue, $FONT, $array[$i] );
	}
	else
	{
  	$bbox = imagettftext( $canvas, $FONT_SIZE* $SCALE, 0, $hpos, $BASELINE* $SCALE, $orange, $FONT, $array[$i] );
  }
   
  $hpos = $bbox[2]+$KERNING;
}

// ---- SAMPLE DOWN & OUTPUT
$final = imageCreateTrueColor($WIDTH,$HEIGHT);

imagecolortransparent($final, $trans);
imagealphablending($final, false);
imagesavealpha($final, true);

imageCopyResampled( $final, $canvas, 0,0,0,0, $WIDTH, $HEIGHT, $WIDTH* $SCALE, $HEIGHT* $SCALE );

header("Pragma: public");
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
header('Content-type: image/png');
header('Content-Disposition: attachment; filename="'.$pub.'.png"');

imagePNG($final);

imageDestroy($canvas);
imageDestroy($final);

