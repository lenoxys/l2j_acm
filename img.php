<?php
header ("Content-type: image/jpeg");

session_start();

$im = imagecreate (100,30) or die ("Problme de cration GD");

$black = imagecolorallocate ($im, 0, 0, 0);
$white = imagecolorallocate ($im, 255, 255, 255);
$grey = imagecolorallocate ($im, 128, 128, 128);


for ($i=0;$i<50;$i++)
{
	$color = imagecolorallocate ($im, rand(0,128), rand(0,128), rand(0,128));
	imageline($im, rand(0,100), rand(0,30), rand(0,100), rand(0,30), $color);
}

define ('_ACM_VALID', 1);
require './classes/core.class.php';
CORE::gen_img_cle();
$code = $_SESSION['code'];

for($i=0;$i<strlen($code);$i++) $key[$i] = substr($code, $i, 1);

$i = 5;
foreach ($key as $value)
{
	$color = imagecolorallocate ($im, rand(128,255), rand(128,255), rand(128,255));
	imagestring ($im, rand(9,5), $i, 7, $value, $color);
	$i += 20;
}

imagejpeg($im, null, 60);
?>