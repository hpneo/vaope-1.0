<?php include("config.php"); ?>
<?php include("incluidos/funciones.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Instalador</title>
</head>
<body>
<h1>Instalador</h1>
<?php
	if(!$_GET){
?>
	<div id="instalacion">
		<div class="mensaje">
			<strong>Advertencia:</strong> Antes de empezar la instalaci&oacute;n debe asegurarse de haber editado todas las variables del archivo config.php. Es importante que configure esos datos para poder realizar una instalaci&oacute;n correcta. Luego, cree la base de datos manualmente con el nombre que le di&oacute; en el archivo config.php.
		</div>
		<p>Si ya configur&oacute; los datos, empiece con la <a href="instalar.php?paso=2">instalaci&oacute;n</a>.</p>
	</div>
<?php
	}
	else{
		if($_GET['paso']=="2"){
			$db = conectar_db();
				if(mysql_select_db(nombre_db)){
					$ssql = file_get_contents("sql.txt");
					$sql = explode(";", $ssql);
					$i=0;
					while($sql[$i]!=""){
						mysql_query($sql[$i]);
						$i++;
					}
					/*$sql = "
						CREATE TABLE categorias (
							categoria_idpadre SMALLINT(11) unsigned NOT NULL,
							categoria_id SMALLINT(11) unsigned NOT NULL AUTO_INCREMENT,
							categoria_nombre VARCHAR(60) NOT NULL,
							categoria_titulo VARCHAR(70) NOT NULL,
							PRIMARY KEY(categoria_id)
						)";
					mysql_query($sql);			
					$sql = "
						CREATE TABLE lugares (
							lugar_idpadre BIGINT(11) unsigned NOT NULL,
							lugar_id BIGINT(11) unsigned NOT NULL AUTO_INCREMENT,
							lugar_nombre VARCHAR(30) NOT NULL,
							lugar_titulo VARCHAR(40) NOT NULL,
							lugar_coordenadas VARCHAR(120) NULL,
							PRIMARY KEY(lugar_id)
						)";
					mysql_query($sql);				
					$sql = "
						CREATE TABLE locales (
							categoria_id SMALLINT(11) unsigned NOT NULL,
							ciudad_id BIGINT(20) unsigned NOT NULL,
							local_id BIGINT(20) unsigned NOT NULL AUTO_INCREMENT,
							local_nombre VARCHAR(100) NOT NULL,
							local_titulo VARCHAR(160) NOT NULL,
							local_direccion VARCHAR(180) NOT NULL,
							local_descripcion TEXT NOT NULL,
							local_telefono VARCHAR(11) NOT NULL,
							local_tags VARCHAR(200) NOT NULL,
							local_nvotos INT(11) NOT NULL DEFAULT '0',
							local_puntuacion FLOAT(10) NULL DEFAULT '0',
							local_fecha INT(11) unsigned NOT NULL,
							local_usuario BIGINT(20) unsigned NOT NULL,
							PRIMARY KEY(local_id)
						)";
					mysql_query($sql);			
					$sql = "
						CREATE TABLE comentarios (
							local_id BIGINT(20) unsigned NOT NULL,
							comentario_id BIGINT(20) unsigned NOT NULL AUTO_INCREMENT,
							comentario_autor BIGINT(20) unsigned NOT NULL,
							comentario_cuerpo TEXT NOT NULL,
							comentario_fecha INT(11) unsigned NOT NULL,
							PRIMARY KEY(comentario_id)
						)";
					mysql_query($sql);			
					$sql = "
						CREATE TABLE votos (
							local_id BIGINT(20) unsigned NOT NULL,
							voto_id BIGINT(20) unsigned NOT NULL AUTO_INCREMENT,
							voto_autor BIGINT(20) unsigned NOT NULL,
							voto_puntaje TINYINT(5) unsigned NOT NULL,
							PRIMARY KEY (voto_id)
						)";
					mysql_query($sql);			
					$sql = "
						CREATE TABLE fotos (
							local_id BIGINT(20) unsigned NOT NULL,
							foto_id BIGINT(20) unsigned NOT NULL AUTO_INCREMENT,
							foto_titulo VARCHAR(100) NOT NULL,
							foto_descripcion TINYTEXT NULL,
							foto_ruta VARCHAR(100) NOT NULL,
							PRIMARY KEY(foto_id)
						)";
					mysql_query($sql);			
					$sql = "
						CREATE TABLE usuarios (
							usuario_id BIGINT(20) unsigned NOT NULL AUTO_INCREMENT,
							usuario_nick VARCHAR(30) NOT NULL,
							usuario_clave VARCHAR(64) NOT NULL,
							usuario_email VARCHAR(100) NOT NULL,
							usuario_mostrar_email ENUM('si', 'no') DEFAULT 'no',
							usuario_descripcion MEDIUMTEXT NULL,
							usuario_web20 VARCHAR(200) NULL,
							usuario_url VARCHAR(100) NULL,
							usuario_fecha INT(11) unsigned NOT NULL,
							PRIMARY KEY(usuario_id)
						)";
					mysql_query($sql);			
					$sql = "
						CREATE TABLE paginas (
							pagina_id INT(10) unsigned NOT NULL AUTO_INCREMENT,
							pagina_titulo VARCHAR(100) NOT NULL,
							pagina_nombre VARCHAR(100) NOT NULL,
							pagina_contenido TEXT NOT NULL,
							PRIMARY KEY(pagina_id)
						)";*/
					$sql = "INSERT INTO usuarios(usuario_nick, usuario_clave, usuario_fecha, usuario_rango) VALUES ('".nick_admin."', '".md5(clave_admin)."', ".time().", 1)";
					if(mysql_query($sql)){
						$sql = "INSERT INTO paginas (pagina_titulo, pagina_nombre, pagina_contenido) VALUES ('Acerca de', '".crear_nombre("Acerca de")."', '&lt;h3&gt;&amp;iquest;Qu&amp;eacute; es &amp;quot;Vao Pe!&amp;quot;?&lt;/h3&gt;&lt;p&gt;Vao Pe! es una gu&amp;iacute;a de locales creado por y para los usuarios. Restaurantes, pubs, tiendas, ferreter&amp;iacute;as, cl&amp;iacute;nicas y dem&amp;aacute;s; todo lo encontrar&amp;aacute;s en Vao Pe!.&lt;/p&gt;&lt;h3&gt;&amp;iquest;Y c&amp;oacute;mo funciona?&lt;/h3&gt;&lt;p&gt;Todo es muy simple: &amp;iquest;Necesitas buscar poller&amp;iacute;as en Trujillo? En la columna izquierda ve a la categor&amp;iacute;a &amp;quot;Poller&amp;iacute;as&amp;quot; y en la columna derecha selecciona &amp;quot;Trujillo&amp;quot;; o si deseas puedes entrar a &amp;quot;Trujillo&amp;quot; y elegir la categor&amp;iacute;a &amp;quot;Poller&amp;iacute;as&amp;quot; en la columna izquierda. As&amp;iacute; de simple.&lt;/p&gt;&lt;p&gt;Si quieres enterarte de los &amp;uacute;ltimos locales agregados en una ciudad puedes agregar el feed de esa ciudad en tu lector de feeds favorito. Igualmente, si deseas saber s&amp;oacute;lo de los locales de cierta categor&amp;iacute;a en determinada ciudad, hay feed para ello.&lt;/p&gt;&lt;h3&gt;&lt;a name=&quot;sugiere&quot; title=&quot;sugiere&quot;&gt;&lt;/a&gt;&amp;iquest;Sugerencias? Claro&lt;/h3&gt;&lt;p&gt;Estamos abiertos a la posibilidad de agregar nuevas cosas, reparar algunos errores y hacer de tu estancia en esta gu&amp;iacute;a una experiencia agradable. Para contactarte con Vao Pe! puedes escribir a vaope@gmail.com. Atenderemos tus dudas o pedidos con rapidez y tomaremos en cuenta tus ideas.&lt;/p&gt;')";
						if(mysql_query($sql)){
							echo "<p class=\"exito\">El proceso de instalaci&oacute;n culmin&oacute; con &eacute;xito. <a href=\"".url_sitio."/login.php\">Logu&eacute;ate</a> para entrar al panel de administraci&oacute;n</a>.</p>";
						}
						else{
							echo "<div class=\"error\">Error: No se pudo agregar la p&aacute;gina de informaci&oacute;n.</div>";
						}
					}
					else{
						echo "<div class=\"error\">Error: No se pudo crear el registro del administrador.</div>";
					}
				}
				else{
					echo "<div class=\"error\">Error: No se pudo seleccionar la base de datos creada.</div>";
				}
		}
	}
?>
</body>
</html>