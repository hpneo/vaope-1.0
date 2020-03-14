<?php
	if($_POST['comentario']!=""){
		include(dirname(__FILE__)."/config.php");
		function conectar_db(){
			$db = mysql_connect(servidor_db, usuario_db, clave_db);
			mysql_select_db(nombre_db, $db);
			return $db;
		}
		$db = conectar_db();
		$rs = mysql_query("INSERT INTO comentarios (local_id, comentario_autor, comentario_cuerpo, comentario_fecha) VALUES (".$_POST['local_id'].", ".$_POST['usuario_id'].", '".htmlentities(str_replace("<br>", "<br />", mysql_real_escape_string(strip_tags($_POST['comentario'], '<bold><strong><i><em><u><s><ol><ul><li><img><a>'))),ENT_QUOTES, "utf-8")."', ".time().")");
		if(!$rs){
			echo "Ops! No se pudo agregar el comentario. Prueba a intentarlo m&aacute;s tarde.";
		}
		else{
			header("Location: ".$_SERVER['HTTP_REFERER']);
		}
	}
?>