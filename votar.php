<?php
	include(dirname(__FILE__)."/config.php");
	function conectar_db(){
		$db = mysql_connect(servidor_db, usuario_db, clave_db);
		mysql_select_db(nombre_db, $db);
		return $db;
	}
	function obtener_id_usuario($usuario){
		$db = conectar_db();
		$rs = mysql_query("SELECT usuario_id FROM usuarios WHERE usuario_nick='$usuario'");
		if(!$rs){
			return -1;
		}
		else{
			$id = mysql_fetch_array($rs);
			return $id['usuario_id'];
		}
	}
	if($_GET['id']!="" && $_GET['voto']!=""){
		$local_id = $_GET['id'];
		$voto = $_GET['voto'];
		$db = conectar_db();
		$rs = mysql_query("SELECT local_puntuacion, local_nvotos FROM locales WHERE local_id=".$local_id, $db);
		if(mysql_num_rows($rs)!=0){
			$local = mysql_fetch_array($rs);
			if($local['local_puntuacion']==0){
				$puntuacion = $local['local_puntuacion']+$voto;
			}
			else{
				$puntuacion = round(($local['local_puntuacion']+$voto)/2, 1);
			}
			$nvotos = $local['local_nvotos']+1;
		}
		$rs = mysql_query("INSERT INTO votos (local_id, voto_autor, voto_puntaje) VALUES ($local_id, ".obtener_id_usuario($_COOKIE['nick']).", $voto)");
		if($rs){
			$rs = mysql_query("UPDATE locales SET local_puntuacion=".$puntuacion.", local_nvotos=".$nvotos." WHERE local_id=".$local_id);
			if(!$rs){
				echo "No se pudo enviar voto.";
			}
			else{
				header("Location: ".$_SERVER['HTTP_REFERER']);
			}
		}
	}
?>