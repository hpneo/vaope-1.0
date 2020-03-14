<?php
	require_once("./config.php");
	include("incluidos/funciones.php");
	$resultado = ereg("^[^@ ]+@[^@ ]+\.[^@ \.]+$",$_POST['usuario_email']);
	if($_POST['usuario_nick']==""){
		header("Location: ".url_sitio."/registro?error=1&clave=".$_POST['usuario_clave']."&email=".$_POST['usuario_email']);
	}
	elseif($_POST['usuario_clave']==""){
		header("Location: ".url_sitio."/registro?error=2&nick=".$_POST['usuario_nick']."&email=".$_POST['usuario_email']);
	}
	elseif($_POST['usuario_email']=="" || !$resultado){
		header("Location: ".url_sitio."/registro?error=3&nick=".$_POST['usuario_nick']."&clave=".$_POST['usuario_clave']);
	}
	else{
		$db = conectar_db();
		$usuario = array(
			"nick" => mysql_real_escape_string(htmlentities($_POST['usuario_nick'],ENT_QUOTES, "utf-8")),
			"clave" => mysql_real_escape_string(htmlentities($_POST['usuario_clave'],ENT_QUOTES, "utf-8")),
			"email" => mysql_real_escape_string($_POST['usuario_email']),
			"descripcion" => mysql_real_escape_string(htmlentities($_POST['usuario_descripcion'],ENT_QUOTES, "utf-8")),
			"url" => mysql_real_escape_string($_POST['usuario_url']),
			"fecha" => time()
		);
		$rs = mysql_query("SELECT * FROM usuarios WHERE usuario_nick='".$usuario['nick']."'");
		if(mysql_num_rows($rs)!=0){
			echo "El nick que intentas registrar ya est&aacute; siendo usado.";
			die();
		}
		else{
			$rs = mysql_query("INSERT INTO usuarios (usuario_nick, usuario_clave, usuario_email, usuario_descripcion, usuario_url, usuario_fecha) VALUES ('".$usuario['nick']."', '".md5($usuario['clave'])."', '".$usuario['email']."', '".$usuario['descripcion']."', '".$usuario['url']."', ".$usuario['fecha'].")");
			if(!$rs){
				echo "Error: No se pudo completar registro.";
				die();
			}
			else{
				setcookie("nick", $usuario['nick'], time()+3600*24*365);
				setcookie("clave", md5($usuario['clave']), time()+3600*24*365);
				include(ruta_sitio."/incluidos/cabecera.php");
				echo '
					<div class="bloque" id="pagina" style="text-align:center;">
						<h2>Registro completado</h2>
						<h3 style="padding:5px;">El registro ha sido completado con éxito.</h3>
						<h3 style="padding:5px;">¿Deseas empezar a <a href="'.url_sitio.'/panel/agregar-local">agregar locales</a>?, o ¿<a href="'.url_sitio.'/panel/editar-datos">editar tu perfil</a>?</h3>
					</div>
				';
				include(ruta_sitio."/incluidos/pie.php");
			}
		}
	}
?>