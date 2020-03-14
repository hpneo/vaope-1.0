<?php
	if(!$_GET['ruta'] || $_GET['ruta']==""){
		$ruta = "http://localhost/aplicaciones/guia/fotos/default.jpg";
	}
	else{
		$ruta = $_GET['ruta'];
	}
	$anchura=200;
	$hmax=200;
	$datos = getimagesize($ruta);
	if($datos[2]==1){
		$img = @imagecreatefromgif($ruta);
	}
	if($datos[2]==2){
		$img = @imagecreatefromjpeg($ruta);
	}
	if($datos[2]==3){
		$img = @imagecreatefrompng($ruta);
	}
	$ratio = ($datos[0] / $anchura);
	$altura = ($datos[1] / $ratio);
	if($altura>$hmax){$anchura2=$hmax*$anchura/$altura;$altura=$hmax;$anchura=$anchura2;}
	$thumb = imagecreatetruecolor($anchura,$altura);
	imagecopyresampled($thumb, $img, 0, 0, 0, 0, $anchura, $altura, $datos[0], $datos[1]);
	if($datos[2]==1){
		header("Content-type: image/gif");
		imagegif($thumb);
	}
	if($datos[2]==2){
		header("Content-type: image/jpeg");
		imagejpeg($thumb);
	}
	if($datos[2]==3){
		header("Content-type: image/png");
		imagepng($thumb);
	}
	imagedestroy($thumb);
?>