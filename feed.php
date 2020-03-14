<?php
include(dirname(__FILE__)."/config.php");
if(!$_GET['ciudad'] && !$_GET['categoria']){
	//header("Location: ".url_sitio);
}
include(ruta_sitio."/incluidos/funciones.php");
$db = conectar_db();
if($_GET['seccion']!="" && $_GET['usuario']!=""){
	$rs = mysql_query("SELECT favoritos.*, locales.* FROM favoritos LEFT JOIN locales ON favoritos.local_id=locales.local_id WHERE favoritos.favorito_usuario=".obtener_id_usuario($_GET['usuario'])." ORDER BY locales.local_puntuacion DESC");
	if(!$rs){
		die();
	}
	$titulo_rss = "Locales favoritos de ".$_GET['usuario'];
}
if($_GET['ciudad']!="" && (!$_GET['categoria'] || $_GET['categoria']=="")){
	$rs = mysql_query("SELECT * FROM locales WHERE ciudad_id=".obtener_id_ciudad($_GET['ciudad'])." ORDER BY local_fecha DESC");
	if(!$rs){
		die();
	}
	$titulo_rss = "Locales en ".obtener_titulo_ciudad($_GET['ciudad']);
}
if($_GET['ciudad']!="" && $_GET['categoria']!=""){
	$rs = mysql_query("SELECT * FROM locales WHERE ciudad_id=".obtener_id_ciudad($_GET['ciudad'])." AND categoria_id=".obtener_id_categoria($_GET['categoria'])." ORDER BY local_fecha DESC");
	if(!$rs){
		die();
	}
	$titulo_rss = obtener_titulo_categoria($_GET['categoria'])." en ".obtener_titulo_ciudad($_GET['ciudad']);
}
header('Content-Type:text/xml; charset="utf-8"', true);
echo '<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	>
	<channel>
		<title>'.$titulo_rss.'</title>
		<link>'.url_sitio.'</link>
		<description>'.$titulo_rss.'</description>
		<language>es</language>
		<pubDate>'.gmdate("D, j M Y H:i:s", time()).' -0500</pubDate>
		<generator>'.url_sitio.'</generator>
';
if(mysql_num_rows($rs)!=0){
	while($local = mysql_fetch_array($rs)){
		echo "<item>\n";
		echo "<title>".stripslashes(html_entity_decode($local['local_titulo'], ENT_QUOTES, "utf-8"))."</title>\n";
		echo "<description>".stripslashes(html_entity_decode($local['local_descripcion'], ENT_QUOTES, "utf-8"))."\n Dirección: ".stripslashes(html_entity_decode($local['local_direccion'], ENT_QUOTES, "utf-8"))."</description>\n";
		echo "<link>".url_sitio."/local/".$local['local_nombre']."</link>\n";
		echo "<guid>".url_sitio."/local/".$local['local_nombre']."</guid>\n";
		echo "<category>".html_entity_decode(obtener_titulo_categoria(obtener_nombre_categoria($local['categoria_id'])), ENT_QUOTES, "utf-8")."</category>\n";
		echo "</item>\n";
	}
}
echo "</channel>\n";
echo "</rss>";
?>