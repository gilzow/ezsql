<?php
$img_src = 'ELI-16x16.gif';
if (file_exists($img_src)) {
	$imageInfo = getimagesize($img_src);
	header("Content-type: ".$imageInfo['mime']);
	$img = @imagecreatefromgif($img_src);
	imagegif($img);
	imagedestroy($img);
} else echo $img_src.' not found!';
?>
