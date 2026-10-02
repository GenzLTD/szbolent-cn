<?php
if (!session_id()) session_start();
header ('Content-Type: image/png');
$image=imagecreatetruecolor(80, 38);
$color=imagecolorallocate($image, 255, 255, 255);
imagefill($image, 20, 20, $color);
$black=imagecolorallocate($image, 0, 0, 0);
$line_color=imagecolorallocate($image, 255, 0, 0);
imageline($image, 0, 10, 100,20, $line_color);//画线
for($i=0;$i<80;$i++) //加入80个干扰的黑点
{
    imagesetpixel($image, rand(0,80), rand(0,38),$black);
}
$code='';
for($i=0;$i<4;$i++){
    $fontSize=5;
    $x=rand(5,10)+$i*60/4;
    $y=rand(5, 10);
    $data='abcdefghjkmnpqrstuvwxyz123456789';
    $string=substr($data,rand(0, strlen($data)),1);
    $code.=$string;
    $color=imagecolorallocate($image,0, 0, 0);
    imagestring($image, $fontSize, $x, $y, $string, $color);
}
$_SESSION['MBT_modown_captcha']=$code;
imagepng($image);
imagedestroy($image);