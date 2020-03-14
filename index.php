<?php
include(dirname(__FILE__)."/config.php");
include(ruta_sitio."/incluidos/funciones.php");
$js=1;
$editor=1;
if(!$_GET['usuario'] || $_GET['usuario']==""){
	$toggle_categorias_ciudades=1;
}
include(ruta_sitio."/incluidos/cabecera.php");
$db = conectar_db();
$plantilla = file_get_contents(ruta_sitio."/incluidos/index.html");
$plantilla_bloque = file_get_contents(ruta_sitio."/incluidos/plantilla.bloque.html");
?>
<?php
if(!$_GET){
	$bloque = array(
		'izquierda'	=>	llenar_plantilla_bloque("Categor&iacute;as", obtener_categorias(0, 0), "categorias"),
		'centro'	=>	llenar_plantilla_bloque("&Uacute;ltimos locales agregados", obtener_locales("", "", 12), "ultimos-locales"),
		'derecha'	=>	llenar_plantilla_bloque("Zonas", obtener_ciudades(0, 0), "ciudades")
	);
	$plantilla = str_replace("[bloque_izquierda]", $bloque['izquierda'], $plantilla);
	$plantilla = str_replace("{subtitulo}", "", $plantilla);
	$plantilla = str_replace("[bloque_centro]", $bloque['centro'], $plantilla);
	$plantilla = str_replace("[bloque_derecha]", $bloque['derecha'], $plantilla);
	echo $plantilla;
}
else{
if($_GET['usuario']!=""){
	if(obtener_id_usuario($_GET['usuario'])==""){
		echo "El usuario no existe.";
		die();
	}
	if($_GET['seccion']=="" || !$_GET['seccion']){
	$rs = mysql_query("SELECT * FROM usuarios WHERE usuario_nick='".mysql_real_escape_string($_GET['usuario'])."'");
	$usuario = mysql_fetch_array($rs);
?>
	<div id="cuerpo">
		<h2>Perfil de <?php echo $usuario['usuario_nick']; ?></h2>
			<div class="bloque" id="pagina">
				<h2>Datos</h2>
				<h3>Descripci&oacute;n:</h3>
				<p><img src="<?php echo gravatar($usuario['usuario_email'], 80); ?>" class="gravatar" /><?php echo html_entity_decode($usuario['usuario_descripcion'],ENT_QUOTES, "utf-8"); ?></p>
				<?php if($usuario['usuario_url']!=""){ ?>
				<h3>Direcci&oacute;n web:</h3>
				<p><a href="<?php echo $usuario['usuario_url']; ?>"><?php echo $usuario['usuario_url']; ?></a></p>
				<?php } ?>
				<?php if($usuario['usuario_mostrar_email']=='si'){ ?>
				<h3>E-mail:</h3>
				<p><?php echo $usuario['usuario_email']; ?></p>
				<?php } ?>
				<h3>&Uacute;ltimos locales agregados</h3>
				<?php echo obtener_locales_usuario($usuario['usuario_id'], 5); ?>
				<h3>&Uacute;ltimos comentarios en locales</h3>
				<?php echo obtener_comentarios_usuario($usuario['usuario_id'], 5); ?>
				<h3><a href="<?php echo url_sitio."/usuario/".$usuario['usuario_nick']; ?>/favoritos"><img src="<?php echo url_sitio; ?>/imagenes/favorito.png" /> Ver locales favoritos</a></h3>
			</div>
	</div>
<?php
	}
	elseif($_GET['seccion']=="favoritos"){
?>	
	<div id="cuerpo">
		<h2><a href="<?php echo url_sitio."/usuario/".$_GET['usuario']; ?>"><?php echo $_GET['usuario']; ?></a> &raquo; Favoritos</h2>
			<div class="bloque" id="pagina">
				<h2>Favoritos de <?php echo $_GET['usuario']; ?></h2>
				<?php echo obtener_favoritos(obtener_id_usuario(mysql_real_escape_string($_GET['usuario']))); ?>
			</div>
	</div>
<?php
	}
	elseif($_GET['seccion']=="locales"){
?>	
	<div id="cuerpo">
		<h2><a href="<?php echo url_sitio."/usuario/".$_GET['usuario']; ?>"><?php echo $_GET['usuario']; ?></a> &raquo; Locales</h2>
			<div class="bloque" id="pagina">
				<h2>Locales agregados por <?php echo $_GET['usuario']; ?></h2>
			</div>
	</div>
<?php
	}
}
elseif($_GET['local']!=""){
$rs = mysql_query("SELECT * FROM locales WHERE local_nombre='".mysql_real_escape_string($_GET['local'])."'");
$local = mysql_fetch_array($rs);
$ciudad = obtener_datos_ciudad($local['ciudad_id']);
?>
	<div id="cuerpo">
		<div class="columna">
			<div class="bloque" id="categorias">
				<h2>Categor&iacute;as</h2>
				<?php
					echo obtener_categorias(0, 0, obtener_nombre_ciudad($local['ciudad_id']));
				?>
			</div>
			<div class="globo">
				Mira todos los locales que hay en <?php echo obtener_titulo_ciudad(obtener_nombre_ciudad($local['ciudad_id'])); ?>
			</div>
		</div>
		<div class="columna" id="centro">
			<div class="bloque" id="local">
				<h2><?php echo stripslashes($local['local_titulo']); ?></h2>
				<h3>Descripci&oacute;n:</h3>
				<p><?php echo str_replace("\n", "<br />", $local['local_descripcion']); ?></p>
				<h3>Direcci&oacute;n:</h3>
				<p><?php echo $local['local_direccion']." - "."<a href=\"".url_sitio."/ciudad/".obtener_nombre_ciudad($local['ciudad_id'])."\">".obtener_titulo_ciudad(obtener_nombre_ciudad($local['ciudad_id']))."</a>"; ?></p>
				<h3>Tel&eacute;fono:</h3>
					<?php
					if($local['local_telefono']!=""){
						echo formatear_telefonos($local['local_telefono'], $ciudad['prefijotel']);
					}
					else{
						echo "<p>Este local no tiene tel&eacute;fono.</p>";
					}
					?>
				<h3>Etiquetas:</h3>
					<?php echo formatear_tags($local['local_tags']); ?>
				<h3>Puntuaci&oacute;n:</h3>
					<?php echo mostrar_estrellas($local['local_puntuacion']); ?>
				<h3>Vota:</h3>
					<?php echo mostrar_formulario_votos($local['local_id']); ?>
				<h3>Otros locales parecidos:</h3>
					<?php echo obtener_locales_relacionados($local['local_id'], $local['categoria_id'], $local['ciudad_id']); ?>
				<h3>Comparte:</h3>
				<ul class="lineal">
					<li><a href="http://del.icio.us/post?url=<?php echo urlencode(url_sitio."/local/".$local['local_nombre']); ?>&amp;title=<?php echo str_replace(" ", "+", stripslashes($local['local_titulo']));?>" title="del.icio.us"><img src="<?php echo url_sitio; ?>/imagenes/del.icio.us.png" alt="del.icio.us" />Agregar a del.icio.us</a></li>
					<?php /*if($_COOKIE['nick']!=""){ ?>
					<li><a href="<?php echo url_sitio; ?>/extras.php?servicio=twitter&amp;id=<?php echo $local['local_id'];?>" title="twitter"><img src="<?php echo url_sitio; ?>/imagenes/twitter.png" alt="twitter" /></a></li>
					<?php } */?>
					<?php
						if($_COOKIE['nick']!="" && !es_favorito($local['local_id'], mysql_real_escape_string($_COOKIE['nick']))){
					?>
					<li><a href="<?php echo url_sitio; ?>/extras.php?servicio=favorito&amp;id=<?php echo $local['local_id'];?>"><img src="<?php echo url_sitio; ?>/imagenes/favorito.png" alt="favorito" />Marcar como favorito</a></li>
					<?php
						}
						elseif($_COOKIE['nick']!="" && es_favorito($local['local_id'], mysql_real_escape_string($_COOKIE['nick']))){
					?>
					<li><a href="<?php echo url_sitio; ?>/extras.php?servicio=quitar-favorito&amp;id=<?php echo $local['local_id'];?>"><img src="<?php echo url_sitio; ?>/imagenes/quitar-favorito.png" alt="favorito" />Desmarcar como favorito</a></li>
					<?php
						}
					?>
				</ul>
				<?php
					if(obtener_nick_usuario($local['local_usuario'])==$_COOKIE['nick'] || es_admin(mysql_real_escape_string($_COOKIE['nick']))){
						echo "<p style=\"background:url('".url_sitio."/imagenes/editar.png') no-repeat top left;padding-left:20px;width:90%;margin:0 auto;border:#336699 solid 1px;\"><a href=\"".url_sitio."/panel/editar-local/id/".$local['local_id']."\">Editar datos de este local</a></p>";
					}
				?>
				<a name="comentarios"></a><h3>Comentarios:</h3>
				<div class="comentarios">
					<?php
						mostrar_comentarios($local['local_id']);
					?>
					<h4>Comenta:</h4>
					<?php if($_COOKIE['nick']!="" && $_COOKIE['clave']!=""){ ?>
					<script type="text/javascript">
						  bkLib.onDomLoaded(function() {
								 new nicEditor({iconsPath : '<?php echo url_sitio; ?>/incluidos/niceditor/nicEditorIcons.gif',buttonList : ['bold','italic','strikeThrough','undo','redo','indent','unindent','ul','ol','image','link']}).panelInstance('comentario');
						  });
					</script>				
					<form action="<?php echo url_sitio; ?>/comentario.php" method="post" id="enviar-comentario">
						<textarea name="comentario" id="comentario"></textarea>
						<input type="hidden" name="local_id" value="<?php echo $local['local_id']; ?>" />
						<input type="hidden" name="usuario_id" value="<?php echo obtener_id_usuario($_COOKIE['nick']); ?>" />
						<input type="submit" value="Env&iacute;a tu comentario" />
					</form>
					<?php } ?>
					<p class="info">Debes estar <a href="<?php echo url_sitio; ?>/registro">registrado</a> para comentar.</p>
				</div>
			</div>
		</div>
		<div class="columna">
			<div class="bloque" id="ciudades">
				<h2>Zonas</h2>
				<?php
					echo obtener_ciudades(0, 0, obtener_nombre_categoria($local['categoria_id']));
				?>
			</div>
			<div class="globo">
				Mira todos los locales de la categor&iacute;a "<?php echo obtener_titulo_categoria(obtener_nombre_categoria($local['categoria_id'])); ?>" en otras zonas.
			</div>
		</div>
	</div>
<?php
}
elseif($_GET['categoria']!="" && !$_GET['ciudad']){
	$bloque = array(
		'izquierda'	=>	llenar_plantilla_bloque("Categor&iacute;as", obtener_categorias(0, 0), "categorias"),
		'centro'	=>	llenar_plantilla_bloque("Locales", obtener_locales(mysql_real_escape_string($_GET['categoria']), ""), "ultimos-locales"),
		'derecha'	=>	llenar_plantilla_bloque("Zonas", obtener_ciudades(0, 0, mysql_real_escape_string($_GET['categoria'])), "ciudades")
	);
	$plantilla = str_replace("[bloque_izquierda]", $bloque['izquierda'], $plantilla);
	$plantilla = str_replace("{subtitulo}", "<h2>Locales en la categor&iacute;a ".obtener_titulo_categoria(mysql_real_escape_string($_GET['categoria']))."</h2>", $plantilla);
	$plantilla = str_replace("[bloque_centro]", $bloque['centro'], $plantilla);
	$plantilla = str_replace("[bloque_derecha]", $bloque['derecha'], $plantilla);
	echo $plantilla;
}
elseif($_GET['ciudad']!="" && !$_GET['categoria']){
	$bloque = array(
		'izquierda'	=>	llenar_plantilla_bloque("Categor&iacute;as", obtener_categorias(0, 0, mysql_real_escape_string($_GET['ciudad'])), "categorias"),
		'centro'	=>	llenar_plantilla_bloque("Locales", obtener_locales("", mysql_real_escape_string($_GET['ciudad'])), "ultimos-locales"),
		'derecha'	=>	llenar_plantilla_bloque("Zonas", obtener_ciudades(0, 0), "ciudades")
	);
	$plantilla = str_replace("[bloque_izquierda]", $bloque['izquierda'], $plantilla);
	$plantilla = str_replace("{subtitulo}", "<h2>Locales en ".obtener_titulo_ciudad(mysql_real_escape_string($_GET['ciudad']))."</h2>", $plantilla);
	$plantilla = str_replace("[bloque_centro]", $bloque['centro'], $plantilla);
	$plantilla = str_replace("[bloque_derecha]", $bloque['derecha'], $plantilla);
	echo $plantilla;
}
elseif($_GET['ciudad']!="" && $_GET['categoria']!=""){
	$bloque = array(
		'izquierda'	=>	llenar_plantilla_bloque("Categor&iacute;as", obtener_categorias(0, 0, mysql_real_escape_string($_GET['ciudad'])), "categorias"),
		'centro'	=>	llenar_plantilla_bloque("Locales", obtener_locales(mysql_real_escape_string($_GET['categoria']), mysql_real_escape_string($_GET['ciudad'])), "ultimos-locales"),
		'derecha'	=>	llenar_plantilla_bloque("Zonas", obtener_ciudades(0, 0), "ciudades")
	);
	$op = '
		<ul id="todos-locales">
			<li>&laquo;<a href="'.url_sitio.'/categoria/'.$_GET['categoria'].'">Ver todos los locales en la categor&iacute;a '.obtener_titulo_categoria(mysql_real_escape_string($_GET['categoria'])).' </a></li>
			<li><a href="'.url_sitio.'/ciudad/'.$_GET['ciudad'].'">Ver todos los locales en '.obtener_titulo_ciudad(mysql_real_escape_string($_GET['ciudad'])).' </a>&raquo;</li>
		</ul>
	';
	$plantilla = str_replace("[bloque_izquierda]", $bloque['izquierda'], $plantilla);
	$plantilla = str_replace("{subtitulo}", "<h2><a href=\"".url_sitio."/ciudad/".$_GET['ciudad']."\">".obtener_titulo_ciudad(mysql_real_escape_string($_GET['ciudad']))."</a> &raquo; <a href=\"".url_sitio."/categoria/".$_GET['categoria']."\">".obtener_titulo_categoria(mysql_real_escape_string($_GET['categoria']))."</a></h2>", $plantilla);
	$plantilla = str_replace("[bloque_centro]", $bloque['centro'].$op, $plantilla);
	$plantilla = str_replace("[bloque_derecha]", $bloque['derecha'], $plantilla);
	echo $plantilla;
}
elseif($_GET['tag']!=""){
	$bloque = array(
		'izquierda'	=>	llenar_plantilla_bloque("Categor&iacute;as", obtener_categorias(0, 0), "categorias"),
		'centro'	=>	llenar_plantilla_bloque("Locales", obtener_locales("", "", "", mysql_real_escape_string($_GET['tag'])), "ultimos-locales"),
		'derecha'	=>	llenar_plantilla_bloque("Zonas", obtener_ciudades(0, 0), "ciudades")
	);
	$plantilla = str_replace("[bloque_izquierda]", $bloque['izquierda'], $plantilla);
	$plantilla = str_replace("{subtitulo}", "<h2>Locales con la etiqueta '".$_GET['tag']."'</h2>", $plantilla);
	$plantilla = str_replace("[bloque_centro]", $bloque['centro'], $plantilla);
	$plantilla = str_replace("[bloque_derecha]", $bloque['derecha'], $plantilla);
	echo $plantilla;
}
}
include(ruta_sitio."/incluidos/pie.php");
?>