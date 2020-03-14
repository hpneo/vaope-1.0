<?php
include("config.php");
if(!$_POST || $_POST['nick']=="" || $_POST['clave']==""){
	include(dirname(__FILE__)."/incluidos/funciones.php");
	echo '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Vao Pe!</title>
<link rel="stylesheet" type="text/css" href="'.url_sitio.'/estilo.css" />
</head>
<body>
<h1>Entrar</h1>
<form action="login.php" method="post">
	<label for="nick">Nick:</label>
	<input type="text" name="nick" id="nick" />
	<label for="clave">Clave:</label>
	<input type="password" name="clave" id="clave" />
	<input type="submit" value="Entrar" />
</form>
	';
	include(dirname(__FILE__)."/incluidos/pie.php");
}
else{
	function conectar_db(){
		$db = mysql_connect(servidor_db, usuario_db, clave_db);
		mysql_select_db(nombre_db, $db);
		return $db;
	}
	$db = conectar_db();
	$nick = $_POST['nick'];
	$clave = $_POST['clave'];
	$nick = mysql_real_escape_string($nick);
	$clave = md5(mysql_real_escape_string($clave));
	$rs = mysql_query("SELECT * FROM usuarios WHERE usuario_nick='$nick'");
	if(mysql_num_rows($rs)==0){
				header("Location: login.php?error=1");
	}
	else{
		$usuario = mysql_fetch_array($rs);
		if($usuario['usuario_clave']!=$clave){
				header("Location: login.php?error=2");
		}
		else{
			setcookie("nick", $nick, time()+3600*24*365);
			setcookie("clave", $clave, time()+3600*24*365);
			if(strstr($_SERVER['HTTP_REFERER'], url_sitio."/login.php")!=""){
				header("Location: ".url_sitio);
			}
			else{
				header("Location: ".$_SERVER['HTTP_REFERER']);
			}
		}
	}
}
?>