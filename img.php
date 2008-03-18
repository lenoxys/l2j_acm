<?php
header ("Content-type: image/jpeg");

session_start();

$im = imagecreate (60,21) or die ("You must activate GD library on your web server");

$black = imagecolorallocate ($im, 0, 0, 0);
$white = imagecolorallocate ($im, 255, 255, 255);
$grey = imagecolorallocate ($im, 128, 128, 128);


for ($i=0;$i<30;$i++)
{
	$color = imagecolorallocate ($im, rand(0,128), rand(0,128), rand(0,128));
	imageline($im, rand(0,60), rand(0,21), rand(0,60), rand(0,21), $color);
	imageellipse($im, rand(0,60), rand(0,21), rand(0,60), rand(0,21), $color);
}

function gen_img_cle($num = 5) {
	$key = '';
	$chaine = "A1B2C3D4E5F6G7H8X9KMN";
	for ($i=0;$i<$num;$i++) $key.= $chaine[rand()%strlen($chaine)];
	return $key;
}

$code = gen_img_cle();
$_SESSION['code'] = $code;

for($i=0;$i<strlen($code);$i++) $key[$i] = substr($code, $i, 1);

$i = 5;
foreach ($key as $value)
{
	$color = imagecolorallocate ($im, rand(128,255), rand(128,255), rand(128,255));
	$font = 'arial.ttf';
	imagestring ($im, 5, $i, 3, $value, $color);
	$i += 10;
}

imagejpeg($im, null, 60);
?>