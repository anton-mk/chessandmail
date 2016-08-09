<?php
	require_once('const_.php');

//инициализация сессии
	session_name(NAME_SESSION_);
	session_start();

#Формирую строку
	$s=''; $_SESSION[SESSION_ANTI_SPAM] ='';
	for($i=1; $i <=4; $i++){
		$a =rand(0,9);
		$s .=$a.' ';
		$_SESSION[SESSION_ANTI_SPAM] .=$a;
	}#for

	header ("Content-type: image/png");
	$im_1 = imagecreatefrompng('Image/back_code.png');
	if ($im_1){
		$c =imagecolorallocate($im_1,0,0,0);
		$y = (imagesy($im_1) -imagefontheight(5)) / 2;
		$x = (imagesx($im_1) -imagefontwidth(5)*7) / 2;
		imagestring($im_1,5,$x,$y,$s,$c);
        $x=imagesx($im_1); $y = imagesy($im_1);
        $im_2 =imagecreatetruecolor($x*2, $y*2);
        imagecopyresized($im_2, $im_1, 0, 0, 0, 0, $x*2, $y*2, $x, $y);

		imagepng($im_2);
		imagedestroy($im_1);
        imagedestroy($im_2);
	}
?>
