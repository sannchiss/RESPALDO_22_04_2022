<?php 
	$text = ucwords($_GET['name']??'S/I');
	$type = $_GET['type']??'gps';
	$condition = $_GET['condition']??'danger';
	$im = imagecreatefrompng("33x45/{$type}-{$condition}.png");
	imageAlphaBlending($im, true);
	imageSaveAlpha($im, true);

	switch ($condition) {
		case 'success':
			$fontColor = imagecolorallocate($im, 33, 120, 68);
			break;
		case 'danger':
			$fontColor = imagecolorallocate($im, 212, 0, 0);
			break;
		case 'warning':
			$fontColor = imagecolorallocate($im, 255, 102, 0);
			break;
		default:
			$fontColor = imagecolorallocate($im, 0, 0,0);
			break;
	}
	header('Content-Type: image/png');
	$fontSize = 2;
	$xPosition = ((33/2)-((imagefontwidth($fontSize)*strlen($text))/2));

	imagestring($im,$fontSize,$xPosition,-2,$text,$fontColor); 
	imagepng($im, null, 0,PNG_NO_FILTER); 
	imagedestroy($im); 
?>