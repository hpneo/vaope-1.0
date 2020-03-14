<?php
	include(dirname(__FILE__)."/config.php");
	include(ruta_sitio."/incluidos/funciones.php");
	$db = conectar_db();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo nombre_sitio; ?></title>
<style type="text/css">
body{
	margin:0;
	padding:0;
	font-size:10px;
	font-family:Tahoma, Verdana;
}
h3 a{
	color:#ffffff;
}
.bloque-local-lista{
	display:block;
	windows:100%;
	border:#333333 solid 1px;
}
.bloque-local-lista h3{
	font-weight:bold;
	margin:1px;
	padding:0;
	border-bottom:#333333 solid 1px;
	background-color:#336699;
}
.bloque-local-lista h4{
	margin:1px;
	padding:0;
}
.bloque-local-lista p{
	margin:1px 1px 1px 5px;
	padding:0px;
}
.bloque-local-lista ul{
	margin:0;
	padding:0;
}
.bloque-local-lista ul li{
	margin:1px 1px 1px 5px;
	padding:0px;
	list-style-type:none;
}
</style>
</head>
<body>
<?php
	echo obtener_locales();
?>
</body>
</html>
