<?php
include(dirname(__FILE__)."/config.php");
if($_COOKIE['nick']=="" || $_COOKIE['clave']==""){
	header("Location: ../");
}
else{
	include(ruta_sitio."/incluidos/funciones.php");
	$editor = 1;
	include(ruta_sitio."/incluidos/cabecera.php");
	$db = conectar_db();
	$rs = mysql_query("SELECT * FROM usuarios WHERE usuario_nick='".mysql_real_escape_string($_COOKIE['nick'])."' AND usuario_clave='".mysql_real_escape_string($_COOKIE['clave'])."'");
	$usuario = mysql_fetch_array($rs);

?>
	<div id="cuerpo">
		<div class="columna">
			<div class="bloque">
				<h2><?php echo $usuario['usuario_nick']; ?></h2>
				<p class="centrado">
					<img src="<?php echo gravatar($usuario['usuario_email'], 80); ?>" class="gravatar" />
				</p>
			</div>
		</div>
<?php
if(!$_GET || $_GET['accion']==""){
?>
		<div class="columna" id="centro">
			<div class="bloque" id="ultimos-locales">
				<h2>Novedades</h2>
				<h3>&Uacute;ltimos locales agregados</h3>
				<?php echo obtener_locales("", "", 10); ?>
			</div>
		</div>
		<div class="columna">
			<div class="bloque" id="acciones-usuario">
				<h2>Acciones</h2>
				<h3>Locales</h3>
				<ul>
					<li><a href="<?php echo url_sitio; ?>/panel/agregar-local">Agregar local</a></li>
					<li><a href="<?php echo url_sitio; ?>/panel/editar-local">Editar local</a></li>
				</ul>
				<h3>Tu cuenta</h3>
				<ul>
					<li><a href="<?php echo url_sitio; ?>/panel/editar-datos">Editar datos</a></li>
				</ul>
			</div>
		</div>
	</div>
<?php
}
elseif($_GET['accion']=="agregar-local"){
?>
		<div class="columna" id="centro">
			<div class="bloque">
				<h2>Agregar local</h2>
					<?php
			if(!$_POST || $_POST['local_categoria']=="" || $_POST['local_ciudad']=="" || $_POST['local_titulo']=="" || $_POST['local_descripcion']=="" || $_POST['local_direccion']==""){
		?>
				<form action="<?php echo url_sitio; ?>/panel/agregar-local" method="post">
					<label for="local_categoria" class="obligatorio">Categor&iacute;a:</label>
					<select name="local_categoria">
						<option value="">Elige una categor&iacute;a</option>
						<?php
							$rs = mysql_query("SELECT * FROM categorias WHERE categoria_idpadre=0 ORDER BY categoria_titulo ASC", $db);
							while($categoria = mysql_fetch_array($rs)){
								echo "<optgroup label=\"".$categoria['categoria_titulo']."\">";
								$rs2 = mysql_query("SELECT * FROM categorias WHERE categoria_idpadre=".$categoria['categoria_id']." ORDER BY categoria_titulo ASC");
								while($subcategoria = mysql_fetch_array($rs2)){
									echo "<option value=\"".$subcategoria['categoria_id']."\">".$subcategoria['categoria_titulo']."</option>\n";
								}
								echo "</optgroup>";
							}
						?>
					</select>
					<label for="local_ciudad" class="obligatorio">Zona:</label>
					<select name="local_ciudad">
						<option value="">Elige una zona</option>
						<?php
							$rs = mysql_query("SELECT * FROM lugares WHERE lugar_idpadre=0 ORDER BY lugar_titulo ASC", $db);
							while($ciudad = mysql_fetch_array($rs)){
								echo "<optgroup label=\"".$ciudad['lugar_titulo']."\">";
								$rs2 = mysql_query("SELECT * FROM lugares WHERE lugar_idpadre=".$ciudad['lugar_id']." ORDER BY lugar_titulo ASC");
								while($subciudad = mysql_fetch_array($rs2)){
									echo "<option value=\"".$subciudad['lugar_id']."\">".$subciudad['lugar_titulo']."</option>\n";
								}
								echo "</optgroup>";
							}
						?>
					</select>
					<p class="info">Si la zona que deseas no est&aacute; en la lista puedes <a href="<?php echo url_sitio."/pagina/contacto";?>">escribirnos</a> para agregarla.</p>
					<label for="local_titulo" class="obligatorio">Nombre del local:</label>
					<input type="text" name="local_titulo" id="local_titulo" />
					<label for="local_descripcion" class="obligatorio">Descripci&oacute;n:</label>
					<textarea id="local_descripcion" name="local_descripcion"></textarea>
					<p class="info">Una peque&ntilde;a descripci&oacute;n del local. Podr&iacute;as indicar c&oacute;mo es el servicio, qu&eacute; ventajas tiene, la calidad, etc.</p>
					<label for="local_direccion" class="obligatorio">Direcc&oacute;n:</label>
					<input type="text" name="local_direccion" id="local_direccion" />
					<label for="local_telefono">Tel&eacute;fono:</label>
					<input type="text" name="local_telefono" id="local_telefono" />
					<p class="info"><strong>No te preocupes por el prefijo telef&oacute;nico ;)</strong>. <u>Si deseas agregar m&aacute;s de un tel&eacute;fono sep&aacute;ralos por coma</u>.</p>
					<label for="local_tags">Etiquetas:</label>
					<input type="text" name="local_tags" id="local_tags" />
					<p class="info">Puedes ayudar a describir este local o a resaltar aspectos importantes con etiquetas. Sep&aacute;ralas con comas. Por ejemplo: buena atenci&oacute;n, econ&oacute;mico, bonito lugar...</p>
					<label for="local_puntuacion">Puntuaci&oacute;n:</label>
					<select name="local_puntuacion" id="local_puntuacion">
						<option value="0" selected="selected">0</option>
						<option value="1" id="puntuacion-1">1</option>
						<option value="2" id="puntuacion-2">2</option>
						<option value="3" id="puntuacion-3">3</option>
						<option value="4" id="puntuacion-4">4</option>
						<option value="5" id="puntuacion-5">5</option>
					</select>
					<input type="hidden" name="local_usuario" id="local_usuario" value="<?php echo $usuario['usuario_id']; ?>" />
					<input type="submit" value="Agregar local" />
					<p class="info">Los campos marcados con asterisco son obligatorios.</p>
				</form>
				<?php
					}
					else{
						$local = array(
							"categoria_id"	=> $_POST['local_categoria'],
							"ciudad_id"		=> $_POST['local_ciudad'],
							"titulo"		=> $_POST['local_titulo'],
							"descripcion"	=> $_POST['local_descripcion'],
							"direccion"		=> $_POST['local_direccion'],
							"telefono"		=> $_POST['local_telefono'],
							"puntuacion"	=> $_POST['local_puntuacion'],
							"usuario"		=> $_POST['local_usuario']
						);
						$tags = trim($_POST['local_tags']);
						$tag = explode(",",$tags);
						for($i=0;$i<sizeof($tag);$i++){
							if(trim($tag[$i])=="") continue;
							$tag[$i] = trim($tag[$i]);
						}
						$local['tags'] = implode(",", $tag);
						$local['tags'] = ",".$local['tags'].",";
						agregar_local($local['categoria_id'], $local['ciudad_id'], $local['titulo'], $local['descripcion'], $local['direccion'], $local['telefono'], $local['tags'], $local['puntuacion'], $local['usuario']);
					}
				?>
			</div>
		</div>
		<div class="columna">
			<div class="bloque" id="acciones-usuario">
				<h2>Acciones</h2>
				<h3>Locales</h3>
				<ul>
					<li><a href="<?php echo url_sitio; ?>/panel/agregar-local">Agregar local</a></li>
					<li><a href="<?php echo url_sitio; ?>/panel/editar-local">Editar local</a></li>
				</ul>
				<h3>Tu cuenta</h3>
				<ul>
					<li><a href="<?php echo url_sitio; ?>/panel/editar-datos">Editar datos</a></li>
				</ul>
			</div>
		</div>
	</div>
<?php
}
elseif($_GET['accion']=="editar-local"){
?>
		<div class="columna" id="centro">
			<div class="bloque">
				<h2>Editar local</h2>
				<?php
					if($_GET['id']==""){
						$rs = mysql_query("SELECT * FROM locales WHERE local_usuario=".$usuario['usuario_id']." ORDER BY local_fecha DESC");
						if(mysql_num_rows($rs)==0){
							echo "A&uacute;n no has agregado un local.";
						}
						else{
							echo "<ul>";
							while($local = mysql_fetch_array($rs)){
								echo "<li><a href=\"".url_sitio."/panel/editar-local/id/".$local['local_id']."\">".stripslashes($local['local_titulo'])."</a></li>";
							}
							echo "</ul>";
						}
					}
					else{
						$rs = mysql_query("SELECT * FROM locales WHERE local_id=".$_GET['id']);
						$local = mysql_fetch_array($rs);
						if($local['local_usuario']!=obtener_id_usuario($_COOKIE['nick']) && !es_admin($_COOKIE['nick'])){
							echo "S&oacute;lo puedes editar los locales que has agregado.";
						}
						else{
							if(!$_POST || $_POST['local_categoria']=="" || $_POST['local_ciudad']=="" || $_POST['local_titulo']=="" || $_POST['local_descripcion']=="" || $_POST['local_direccion']==""){
						?>
						<form action="<?php echo url_sitio; ?>/panel/editar-local/id/<?php echo $local['local_id']; ?>" method="post">
							<label for="local_categoria">Categor&iacute;a:</label>
							<select name="local_categoria">
								<option value="">Elige una categor&iacute;a</option>
								<?php
									$rs = mysql_query("SELECT * FROM categorias WHERE categoria_idpadre=0 ORDER BY categoria_titulo ASC", $db);
									while($categoria = mysql_fetch_array($rs)){
										echo "<optgroup label=\"".$categoria['categoria_titulo']."\">";
										$rs2 = mysql_query("SELECT * FROM categorias WHERE categoria_idpadre=".$categoria['categoria_id']." ORDER BY categoria_titulo ASC");
										while($subcategoria=mysql_fetch_array($rs2)){
											if($subcategoria['categoria_id']==$local['categoria_id']){
											echo "<option value=\"".$subcategoria['categoria_id']."\" selected=\"selected\">".$subcategoria['categoria_titulo']."</option>\n";
											}
											else{
												echo "<option value=\"".$subcategoria['categoria_id']."\">".$subcategoria['categoria_titulo']."</option>\n";
											}
										}
										echo "</optgroup>";
									}
								?>
							</select>
							<label for="local_ciudad">Zona:</label>
							<select name="local_ciudad">
								<option value="">Elige una zona</option>
								<?php
									$rs = mysql_query("SELECT * FROM lugares WHERE lugar_idpadre=0 ORDER BY lugar_titulo ASC", $db);
									while($ciudad = mysql_fetch_array($rs)){
										echo "<optgroup label=\"".$ciudad['lugar_titulo']."\">";
										$rs2 = mysql_query("SELECT * FROM lugares WHERE lugar_idpadre=".$ciudad['lugar_id']." ORDER BY lugar_titulo ASC");
										while($subciudad = mysql_fetch_array($rs2)){
											if($subciudad['lugar_id']==$local['ciudad_id']){
												echo "<option value=\"".$subciudad['lugar_id']."\" selected=\"selected\">".$subciudad['lugar_titulo']."</option>\n";
											}
											else{
												echo "<option value=\"".$subciudad['lugar_id']."\">".$subciudad['lugar_titulo']."</option>\n";
											}
										}
										echo "</optgroup>";
									}
								?>
							</select>
							<label for="local_titulo">Nombre del local:</label>
							<input type="text" name="local_titulo" id="local_titulo" value="<?php echo stripslashes($local['local_titulo']);?>" />
							<label for="local_descripcion">Descripci&oacute;n:</label>
							<textarea id="local_descripcion" name="local_descripcion"><?php echo stripslashes($local['local_descripcion']);?></textarea>
							<p class="info">Una peque&ntilde;a descripci&oacute;n del local. Podr&iacute;as indicar c&oacute;mo es el servicio, qu&eacute; ventajas tiene, la calidad, etc.</p>
							<label for="local_direccion">Direcci&oacute;n:</label>
							<input type="text" name="local_direccion" id="local_direccion" value="<?php echo $local['local_direccion'];?>" />
							<label for="local_telefono">Tel&eacute;fono:</label>
							<input type="text" name="local_telefono" id="local_telefono" value="<?php echo $local['local_telefono'];?>" />
							<p class="info"><strong>No te preocupes por el prefijo telef&oacute;nico ;)</strong>. <u>Si deseas agregar m&aacute;s de un tel&eacute;fono, sep&aacute;ralos por coma.</u></p>
							<label for="local_tags">Etiquetas:</label>
							<input type="text" name="local_tags" id="local_tags" value="<?php echo substr(substr($local['local_tags'], 1),0,-1);?>" />
							<p class="info">Puedes ayudar a describir este local o a resaltar aspectos importantes con etiquetas. Sep&aacute;ralas con comas. Por ejemplo: buena atenci&oacute;n, econ&oacute;mico, bonito lugar...</p>
							<input type="hidden" name="local_usuario" id="local_usuario" value="<?php echo $usuario['usuario_id']; ?>" />
							<input type="submit" value="Editar local" />
						</form>
						<?php
							}
							else{
								$local = array(
									"categoria_id"	=> $_POST['local_categoria'],
									"ciudad_id"		=> $_POST['local_ciudad'],
									"titulo"			=> $_POST['local_titulo'],
									"descripcion"	=> $_POST['local_descripcion'],
									"direccion"		=> $_POST['local_direccion'],
									"telefono"		=> $_POST['local_telefono'],
								);
								$tags = trim($_POST['local_tags']);
								$tag = explode(",",$tags);
								for($i=0;$i<sizeof($tag);$i++){
									if(trim($tag[$i])=="") continue;
									$tag2[$i] = trim($tag[$i]);
								}
								$local['tags'] = implode(",", $tag2);
								$local['tags'] = ",".$local['tags'].",";
								editar_local($local['categoria_id'], $local['ciudad_id'], $_GET['id'], $local['titulo'], $local['descripcion'], $local['direccion'], $local['telefono'], $local['tags']);
							}
						}
					}
				?>
			</div>
		</div>
		<div class="columna">
			<div class="bloque" id="acciones-usuario">
				<h2>Acciones</h2>
				<h3>Locales</h3>
				<ul>
					<li><a href="<?php echo url_sitio; ?>/panel/agregar-local">Agregar local</a></li>
					<li><a href="<?php echo url_sitio; ?>/panel/editar-local">Editar local</a></li>
				</ul>
				<h3>Tu cuenta</h3>
				<ul>
					<li><a href="<?php echo url_sitio; ?>/panel/editar-datos">Editar datos</a></li>
				</ul>
			</div>
		</div>
	</div>
<?php
}
elseif($_GET['accion']=="editar-datos"){
?>
		<div class="columna" id="centro">
			<div class="bloque">
				<h2>Editar datos</h2>
				<?php
					if(!$_POST){
				?>
				<script type="text/javascript">
					  bkLib.onDomLoaded(function() {
							 new nicEditor({iconsPath : '<?php echo url_sitio; ?>/incluidos/niceditor/nicEditorIcons.gif'}).panelInstance('descripcion');
							 /*new nicEditor({fullPanel : true}).panelInstance('area2');
							 new nicEditor({buttonList : ['fontSize','bold','italic','underline','strikeThrough','undo','redo','subscript','superscript','html','image']}).panelInstance('area4');*/
					  });
				</script>				
				<form action="<?php echo url_sitio; ?>/panel/editar-datos" method="post">
					<label for="clave_antigua">Clave actual:</label>
					<input type="password" name="clave_antigua" id="clave_antigua" />
					<p class="info">Si no deseas cambiar tu clave deja este campo en blanco.</p>
					<label for="clave_nueva">Clave nueva:</label>
					<input type="password" name="clave_nueva" id="clave_nueva" />
					<p class="info">Si no deseas cambiar tu clave deja este campo en blanco.</p>
					<label for="email">Email:</label>
					<input type="text" name="email" id="email" value="<?php echo stripslashes($usuario['usuario_email']); ?>" />
					<label for="mostrar_email" style="display:inline;">Mostrar e-mail:</label>
					<?php $check=($usuario['usuario_mostrar_email']=="si")?"checked=\"checked\"":""; ?>
					<input type="checkbox" name="mostrar_email" id="mostrar_email" <?php echo $check; ?> style="display:inline;" />
					<label for="descripcion">Descripci&oacute;n:</label>
					<textarea name="descripcion" id="descripcion"><?php echo stripslashes($usuario['usuario_descripcion']); ?></textarea>
					<label for="url">P&aacute;gina web:</label>
					<input type="text" name="url" id="url" value="<?php echo stripslashes($usuario['usuario_url']); ?>" />
					<input type="submit" value="Editar datos" />
				</form>
				<?php	
					}
					else{
						$datos = array(
							"clave_antigua"=> mysql_escape_string(htmlentities($_POST['clave_antigua'],ENT_QUOTES, "utf-8")),
							"clave_nueva"	=> mysql_escape_string(htmlentities($_POST['clave_nueva'],ENT_QUOTES, "utf-8")),
							"descripcion"	=>	mysql_real_escape_string(htmlentities(str_replace("<br>", "<br />", strip_tags($_POST['descripcion'])),ENT_QUOTES, "utf-8")),
							"email"			=> mysql_real_escape_string(htmlentities($_POST['email'],ENT_QUOTES, "utf-8")),
							"url"				=> mysql_real_escape_string(htmlentities($_POST['url'],ENT_QUOTES, "utf-8"))
						);
						if($_POST['mostrar_email']==""){
							$datos['mostrar_email']="no";
						}
						else{
							$datos['mostrar_email']="si";
						}
						if($datos['clave_antigua']=="" && $datos['clave_nueva']==""){
							$sql = "UPDATE usuarios SET usuario_descripcion='".$datos['descripcion']."', usuario_email='".$datos['email']."', usuario_mostrar_email='".$datos['mostrar_email']."', usuario_url='".$datos['url']."' WHERE usuario_id=".$usuario['usuario_id'];
						}
						else{
							if($datos['clave_antigua']=="" && $datos['clave_nueva']!=""){
								echo "Debes escribir tu clave antigua para validar el cambio de clave.";
							}
							else{
								if($datos['clave_antigua']!="" && $datos['clave_nueva']!=""){
									if($usuario['usuario_clave']!=md5($datos['clave_antigua'])){
										echo "Tu clave actual no coincide con el registro.";
									}
									else{
										$sql = "UPDATE usuarios SET usuario_clave='".md5($datos['clave_nueva'])."', usuario_descripcion='".$datos['descripcion']."', usuario_email='".$datos['email']."', usuario_mostrar_email='".$datos['mostrar_email']."', usuario_url='".$datos['url']."' WHERE usuario_id=".$usuario['usuario_id'];
									}
								}
							}
						}
						$rs = mysql_query($sql);
						if(!$rs){
							echo "No se pudo editar los datos.";
						}
						else{
							if($datos['clave_antigua']=="" && $datos['clave_nueva']==""){
								echo "Datos editados con éxito.";
							}
							else{
								echo "Datos editados con éxito.<br />Necesitas cerrar sesión y volver a entrar para que los cambios surtan efecto.";
							}
						}
					}
				?>
			</div>
		</div>
		<div class="columna">
			<div class="bloque" id="acciones-usuario">
				<h2>Acciones</h2>
				<h3>Locales</h3>
				<ul>
					<li><a href="<?php echo url_sitio; ?>/panel/agregar-local">Agregar local</a></li>
					<li><a href="<?php echo url_sitio; ?>/panel/editar-local">Editar local</a></li>
				</ul>
				<h3>Tu cuenta</h3>
				<ul>
					<li><a href="<?php echo url_sitio; ?>/panel/editar-datos">Editar datos</a></li>
				</ul>
			</div>
		</div>
	</div>
<?php
}
include(ruta_sitio."/incluidos/pie.php");
}
?>