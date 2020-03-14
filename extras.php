<?php
	include(dirname(__FILE__)."/config.php");
	include(ruta_sitio."/incluidos/funciones.php");
	$db = conectar_db();
	if($_GET['servicio']=="twitter"){
		if($_GET['id']==""){
			die();
		}
		if($_COOKIE['nick']=="" || $_COOKIE['clave']==""){
			die();
		}
		include(ruta_sitio."/incluidos/class.twitter.php");
		$rs = mysql_query("SELECT * FROM usuarios WHERE usuario_nick='".$_COOKIE['nick']."' AND usuario_clave='".$_COOKIE['clave']."'");
		$usuario = mysql_fetch_array($rs);
		$datos_web20 = $usuario['usuario_web20'];
		if($datos_web20=="" || strstr($datos_web20, "twitter")==""){
			echo "No tienes una cuenta de Twitter.";
			die();
		}
		preg_match_all('/\[twitter:(.*?)\|(.*?)]/i', $datos_web20, $resultados);
		$twitter_nick = $resultados[1][0];
		$twitter_clave = $resultados[2][0];
		$twitter = new Twitter($twitter_nick, $twitter_clave);
		$rs = mysql_query("SELECT * FROM locales WHERE local_id=".$_GET['id']);
		$local = mysql_fetch_array($rs);
		$url = file_get_contents("http://tinyurl.com/api-create.php?url=".url_sitio."/local/".$local['local_nombre']);
		$status = "Local en ".obtener_titulo_ciudad(obtener_nombre_ciudad($local['ciudad_id'])).": '".$local['local_titulo']."'.\n$url";
		echo $status;
		if(!$twitter->updateStatus($status)){
			echo "Error al enviar twitt.";
		}
		else{
			echo "Local twitteado.";
		}
	}
	elseif($_GET['servicio']=="recomendar"){
		if($_GET['id']==""){
			die();
		}
		$rs = mysql_query("SELECT * FROM usuarios WHERE usuario_nick='".$_COOKIE['nick']."' AND usuario_clave='".$_COOKIE['clave']."'");
		if(mysql_num_rows($rs)==0){
			$nombre = "";
			$email = "";
		}
		else{
			$usuario = mysql_fetch_array($rs);
			$nombre = $usuario['usuario_nick'];
			$email = $usuario['usuario_email'];
		}
		echo "
			<form action=\"".url_sitio."/extras.php?servicio=recomendar&amp;id=".$_GET['id']."\" method=\"post\">
				<label>Nombre:</label>
				<input type=\"text\" name=\"nombre\" value=\"$nombre\" />
				<label>E-mail:</label>
				<input type=\"text\" name=\"email\" value=\"$email\" />
				<label>Nombre de tu amigo:</label>
				<input type=\"text\" name=\"nombre-amigo\" />
				<label>E-mail de tu amigo:</label>
				<input type=\"text\" name=\"email-amigo\" />
				<textarea></textarea>
				<input type=\"submit\" value=\"Recomendar a amigo\" />
			</form>
		";
	}
	elseif($_GET['servicio']=="favorito"){
		if($_GET['id']==""){
			die();
		}
		else{
			if($_COOKIE['nick']=="" || $_COOKIE['clave']==""){
				echo "<p>Necesitas estar registrado para poder marcar un local como favorito.</p>";
			}
			else{
				if(!es_favorito($_GET['id'], $_COOKIE['nick'])){
					agregar_favorito($_GET['id']);
					header("Location: ".$_SERVER['HTTP_REFERER']);
				}
			}
		}
	}
	elseif($_GET['servicio']=="quitar-favorito"){
		if($_GET['id']==""){
			die();
		}
		else{
			if($_COOKIE['nick']=="" || $_COOKIE['clave']==""){
				echo "<p>Necesitas estar registrado para poder desmarcar un local como favorito.</p>";
			}
			else{
				if(es_favorito($_GET['id'], $_COOKIE['nick'])){
					quitar_favorito($_GET['id']);
					header("Location: ".$_SERVER['HTTP_REFERER']);
				}
			}
		}
	}
?>