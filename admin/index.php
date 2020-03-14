<?php
	include(dirname(dirname(__FILE__))."/config.php");
	if(!$_COOKIE['nick'] || !$_COOKIE['clave']){
		header("Location: ../");
	}
	else{
		include(ruta_sitio."/incluidos/funciones.php");
		$db = conectar_db();
		$rs = mysql_query("SELECT * FROM usuarios WHERE usuario_nick='".$_COOKIE['nick']."' AND usuario_clave='".$_COOKIE['clave']."'");
		$usuario = mysql_fetch_array($rs);
		if($usuario['usuario_rango']!=1){
			header("Location: ../");
		}
		else{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Panel de Control</title>
<style type="text/css">
	body{
		font-family:Calibri, Verdana;
	}
	h1, h2, h3, h4{
		margin:0px;
	}
	p{
		margin:4px;
	}
	a{
		color:#333333;
		text-decoration:none;
	}
	a:hover{
		color:#336699;
		text-decoration:underline;
	}
	textarea{
		display:block;
		font-family:Calibri, Verdana;
		border:none;
		width:75%;
		min-height:200px;
		border:#333333 solid 1px;
	}
	#contenido h2{
		text-align:center;
		border-bottom:#333333 solid 1px;
	}
	#contenido, .bloque{
		border:#333333 solid 1px;
	}
	#cuerpo{
		display:block;
		overflow:hidden;
	}
	#sidebar{
		width:20%;
		float:left;
	}
	.bloque{
		margin-bottom:5px;
		padding-bottom:5px;
	}
	#sidebar ul{
		margin:0px;
		padding:1px 0px;
	}
	#sidebar ul li{
		margin:0px;
		padding:1px 10px;
		list-style-type:none;
	}
	#contenido{
		width:76%;
		float:left;
		margin-left:8px;
		padding:5px;
	}
	label, input, select{
		display:block;
	}
	input[type=text]{
		width:40%;
	}
	input[type=submit]{
		margin:0 auto 0 auto;
		padding:5px;
		font-weight:bold;
	}
	.mensaje{
		display:block;
		width:95%;
		margin:5px;
		padding:5px;
		border:#336699 solid 1px;
	}
	table{
		width:85%;
		margin:5px auto;
	}
	tbody tr{
		margin:2px 0px;
	}
	thead, tbody tr{
		display:block;
		border:#222222 solid 1px;
	}
	tr.registro-tabla:hover{
		background-color:#f1f5fa;
	}
	td img{
		margin:0px;
		padding:0px;
	}
	td a img{
		border:none;
	}
	input[type=button]{
		border:#222222 solid 1px;		
	}
	.mceStatusbarResize{
		display:inline; !important
	}
	.mceStatusbarBottom{
		display:block;
		width:100%;
		overflow:hidden;
	}
	.mceStatusbarBottom{
		display:inline;	!important
	}
	.mceSelectList{
		border:#222222 solid 1px;
	}
	#ultimos-locales, #ultimos-comentarios, #estadisticas{
		display:block;
		width:30%;
		border:#666666 solid 1px;
		float:left;
		margin:2px;
		padding:2px;
	}
	#ultimos-locales ul, #ultimos-comentarios ul{
		margin:2px;
		padding:0 0 0 20px;
	}
	#pie{
		margin-top:10px;
		display:block;
		clear:both;
		border-top:#222222 solid 1px;
		text-align:center;
	}
	.graficos{
		list-style-type:none;
		font-size:12px;
		padding:0;
		margin:0 10px;
	}
	.graficos li{
		display:block;
	}
</style>
<script language="javascript" type="text/javascript" src="../incluidos/js/tiny_mce/tiny_mce_gzip.js"></script>
<script type="text/javascript">
tinyMCE_GZ.init({
	plugins : 'inlinepopups',
	themes : 'advanced',
	languages : 'en',
	disk_cache : true,
	debug : false
});
</script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
	mode : "exact",
	elements : "pagina_contenido",
	theme: "advanced",
	language: "es",
	plugins: "inlinepopups",
	theme_advanced_buttons1: "bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright, justifyfull,separator,formatselect",
	theme_advanced_buttons2: "cut,copy,paste,separator,bullist,numlist,separator,undo,redo,separator,outdent,indent,blockquote,separator,link,unlink,anchor,separator,image,cleanup,separator,sub,sup,separator,acronym,charmap",
	theme_advanced_buttons3: "",
	theme_advanced_toolbar_location: "top",
	theme_advanced_toolbar_align: "left",
	theme_advanced_statusbar_location: "bottom",
	theme_advanced_resizing : true,

	theme_advanced_resize_horizontal : false,
});
</script>
</head>
<body>
<h1>Panel de Control</h1>
<div id="cuerpo">
	<div id="sidebar">
		<div class="bloque">
			<h2>P&aacute;ginas</h2>
			<ul>
				<li><a href="?opcion=agregar-pagina">Agregar P&aacute;gina est&aacute;tica</a></li>
				<li><a href="?opcion=administrar-paginas">Administrar P&aacute;ginas est&aacute;ticas</a></li>

			</ul>
		</div>
		<div class="bloque">
			<h2>Categor&iacute;as</h2>
			<ul>
				<li><a href="?opcion=agregar-categoria">Agregar categor&iacute;a</a></li>
				<li><a href="?opcion=administrar-categorias">Administrar categor&iacute;as</a></li>
			</ul>
		</div>
		<div class="bloque">
			<h2>Zonas</h2>
			<ul>
				<li><a href="?opcion=agregar-ciudad">Agregar zona</a></li>
				<li><a href="?opcion=administrar-ciudades">Administrar zonas</a></li>
			</ul>
		</div>
	</div>
	<div id="contenido">
		<?php
			if(!$_GET){
		?>
		<h2>Inicio</h2>
		<div id="ultimos-locales">
			<h3>&Uacute;ltimos Locales</h3>
			<?php
				$rs = mysql_query("SELECT * FROM locales ORDER BY local_id DESC LIMIT 5");
				if(mysql_num_rows($rs)==0){
					echo "A&uacute;n no hay locales.";
				}
				else{
					echo '<ul>';
					while($local = mysql_fetch_array($rs)){
						echo '
							<li>
								<h4><a href="'.url_sitio.'/local/'.$local['local_nombre'].'">'.stripslashes($local['local_titulo']).'</a></h4>
								<p><strong>Descripci&oacute;n:</strong> '.stripslashes($local['local_descripcion']).'</p>
							</li>
						';
					}
					echo '</ul>';
				}
			?>
		</div>
		<div id="ultimos-comentarios">
			<h3>&Uacute;ltimos Comentarios</h3>
			<?php
				$rs = mysql_query("SELECT * FROM comentarios ORDER BY comentario_id DESC LIMIT 5");
				if(mysql_num_rows($rs)==0){
					echo "A&uacute;n no hay comentarios.";
				}
				else{
					echo '<ul>';
					while($comentario = mysql_fetch_array($rs)){
						echo '
							<li>
								<a href="../local/'.obtener_nombre_local($comentario['local_id']).'#c-'.$comentario['comentario_fecha'].'"><strong>'.obtener_nick_usuario($comentario['comentario_autor']).'</strong> en <strong>'.stripslashes(obtener_titulo_local(obtener_nombre_local($comentario['local_id']))).'</strong></a>
							</li>
						';
					}
					echo '</ul>';
				}
			?>
		</div>
		<div id="estadisticas">
			<h3>Estad&iacute;sticas</h3>
			<?php
					echo '<p>Existen '.obtener_n_usuarios().' usuarios registrados, '.obtener_n_locales("", "").' locales, '.obtener_n_comentarios().', '.obtener_n_categorias().' categor&iacute;as y '.obtener_n_ciudades().' zonas.</p>';
			?>
        	<h4>Categor&iacute;as</h4>
            <?php
				$rs = mysql_query("SELECT * FROM categorias");
				echo '<ul class="graficos">
				';
				$i=0;
				while($categorias = mysql_fetch_array($rs)){
					$categoria[$i] = array(
						'id'		=>	$categorias['categoria_id'],
						'titulo'	=>	$categorias['categoria_titulo']
					);
					$i++;
				}
				for($i=0;$i<mysql_num_rows($rs);$i++){
					$n[$i]=obtener_n_locales("categoria", $categoria[$i]['id']);
				}
				rsort($n);
				$rs = mysql_query("SELECT * FROM categorias");
				while($categorias = mysql_fetch_array($rs)){
					$porcentaje = (obtener_n_locales("categoria", $categorias['categoria_id'])*100)/$n[0];
					echo '<li>
							'.$categorias['categoria_titulo'].'-'.obtener_n_locales("categoria", $categorias['categoria_id']).' ('.round($porcentaje, 1).'%)<br />
							<div style="display:block; height:5px; background-color:#4477aa;width:'.round($porcentaje, 1).'%"></div>
						</li>';
				}
				echo '
				</ul>';
			?>
		</div>
		<?php
			}
			else{
				if($_GET['opcion']=="opciones"){
		?>
		<h2>Opciones de Configuraci&oacute;n</h2>
		<?php
				}
				elseif($_GET['opcion']=="agregar-categoria"){
		?>
		<h2>Agregar Categoría</h2>
			<?php
					if($_POST || $_POST['categoria_nombre']!=""){
						$datos = array(
							"id_padre"		=>	$_POST['categoria_padre'],
							"titulo"		=>	htmlentities(strip_tags($_POST['categoria_nombre']), ENT_QUOTES, "utf-8"),
						);
						if(obtener_id_categoria(crear_nombre($datos['titulo']))!=-1){
							if(mysql_query("INSERT categorias (categoria_idpadre, categoria_titulo, categoria_nombre) VALUES (".$datos['id_padre'].", '".$datos['titulo']."', '".crear_nombre($datos['titulo'])."')")){
								echo "<div class=\"mensaje\">Categoría creada con éxito</div>";
								$_POST=NULL;
							}
						}
					}
			?>
			<form action="" method="post">
				<label>Categoría padre:</label>
				<select name="categoria_padre">
					<option value="0">Agregar como categoría padre</option>
					<?php
						$rs = mysql_query("SELECT * FROM categorias WHERE categoria_idpadre=0");
						while($categoria = mysql_fetch_array($rs)){
							echo "<option value=\"".$categoria['categoria_id']."\">".stripslashes($categoria['categoria_titulo'])."</option>";
						}
					?>
				</select>
				<label>Nombre:</label>
				<input type="text" name="categoria_nombre" />
				<input type="submit" value="Agregar categoría" onclick="javascript:this.disabled=true;" />
			</form>
		<?php
				}
				elseif($_GET['opcion']=="administrar-categorias"){
		?>
		<h2>Administrar Categorías</h2>
			<?php
				$rs = mysql_query("SELECT * FROM categorias WHERE categoria_idpadre=0");
				echo "<table>
							<thead>
								<tr>
									<th width=\"85%\">Nombre</th>
									<th width=\"5%\" colspan=\"3\">Acciones</th>
								</tr>
							</thead>
							<tbody>
				";
				while($categoria = mysql_fetch_array($rs)){
					echo "<tr class=\"registro-tabla\">
								<td width=\"100%\" colspan=\"2\"><strong>".$categoria['categoria_titulo']."</strong></td>
								<td width=\"5%\"><a href=\"?opcion=editar-categoria&amp;id=".$categoria['categoria_id']."\"><img src=\"../imagenes/editar.png\" /></a></td>
								<td width=\"5%\"><a href=\"?opcion=eliminar-categoria&amp;id=".$categoria['categoria_id']."\"><img src=\"../imagenes/eliminar.png\" /></a></td>
							</tr>
							";
					$subrs = mysql_query("SELECT * FROM categorias WHERE categoria_idpadre=".$categoria['categoria_id']);
					while($subcategoria = mysql_fetch_array($subrs)){
						echo "<tr class=\"registro-tabla\">
									<td width=\"100%\" style=\"padding-left:10px;\">".$subcategoria['categoria_titulo']."</td>
									<td width=\"5%\"><a href=\"../categoria/".$subcategoria['categoria_nombre']."\"><img src=\"../imagenes/ir2.png\" /></a></td>
									<td width=\"5%\"><a href=\"?opcion=editar-categoria&amp;id=".$subcategoria['categoria_id']."\"><img src=\"../imagenes/editar.png\" /></a></td>
									<td width=\"5%\"><a href=\"?opcion=eliminar-categoria&amp;id=".$subcategoria['categoria_id']."\"><img src=\"../imagenes/eliminar.png\" /></a></td>
								</tr>
								";
					}
				}
				echo "</tbody>
				</table>";
			?>
		<?php
				}
				elseif($_GET['opcion']=="editar-categoria"){
		?>
		<h2>Editar Categoría: <?php echo obtener_titulo_categoria(obtener_nombre_categoria($_GET['id']));?></h2>
		<?php
					if($_GET['id']!=""){
						if(!$_POST){
						}
						else{
							$titulo = htmlentities(strip_tags($_POST['categoria_nombre']), ENT_QUOTES, "utf-8");
							$rs = mysql_query("UPDATE categorias SET categoria_idpadre=".$_POST['categoria_padre'].", categoria_titulo='".$titulo."', categoria_nombre='".crear_nombre($titulo)."' WHERE categoria_id=".$_GET['id']);
							if(!$rs){
								echo "<div class=\"mensaje\">Error al editar el registro.</div>";
							}
							else{
								echo "<div class=\"mensaje\">Registro editado con éxito.</div>";
							}
						}
						$rs = mysql_query("SELECT * FROM categorias WHERE categoria_id=".$_GET['id']);
						$categoria = mysql_fetch_array($rs);
		?>
		<form action="" method="post">
				<label>Categoría padre:</label>
				<select name="categoria_padre">
					<option value="0">Agregar como categoría padre</option>
					<?php
						$rs2 = mysql_query("SELECT * FROM categorias WHERE categoria_idpadre=0");
						while($listacategoria = mysql_fetch_array($rs2)){
							if($categoria['categoria_idpadre']!=0 && $listacategoria['categoria_id']==$categoria['categoria_idpadre']){
								$selected = " selected=\"selected\"";
							}
							else{
								$selected="";
							}
							echo "<option value=\"".$listacategoria['categoria_id']."\"$selected>".$listacategoria['categoria_titulo']."</option>\n";
						}
					?>
				</select>
				<label>Nombre:</label>
				<input type="text" name="categoria_nombre" value="<?php echo stripslashes($categoria['categoria_titulo']); ?>" />
				<input type="submit" value="Editar categoría" onclick="javascript:this.disabled=true;" />
		</form>
		<?php
					}
				}
				elseif($_GET['opcion']=="eliminar-categoria"){
		?>
		<h2>Eliminar Categoría</h2>
			<?php
					if($_GET['id']!=""){
						$rs = mysql_query("DELETE FROM categorias WHERE categoria_id=".$_GET['id']);
					}
					$rs = mysql_query("SELECT * FROM categorias WHERE categoria_idpadre=0");
					if(!$rs){
						echo "<div class=\"mensaje error\">No se pudo eliminar la categoría.</div>";
					}
					else{
						echo "<div class=\"mensaje exito\">Categoría eliminada con éxito.</div>";
					}
					echo "<table>
								<thead>
									<tr>
										<th width=\"85%\">Nombre</th>
										<th width=\"5%\" colspan=\"3\">Acciones</th>
									</tr>
								</thead>
								<tbody>
					";
					while($categoria = mysql_fetch_array($rs)){
						echo "<tr class=\"registro-tabla\">
									<td width=\"100%\" colspan=\"2\"><strong>".$categoria['categoria_titulo']."</strong></td>
									<td width=\"5%\"><a href=\"?opcion=editar-categoria&amp;id=".$categoria['categoria_id']."\"><img src=\"../imagenes/editar.png\" /></a></td>
									<td width=\"5%\"><a href=\"?opcion=eliminar-categoria&amp;id=".$categoria['categoria_id']."\"><img src=\"../imagenes/eliminar.png\" /></a></td>
								</tr>
								";
						$subrs = mysql_query("SELECT * FROM categorias WHERE categoria_idpadre=".$categoria['categoria_id']);
						while($subcategoria = mysql_fetch_array($subrs)){
							echo "<tr class=\"registro-tabla\">
										<td width=\"100%\" style=\"padding-left:10px;\">".$subcategoria['categoria_titulo']."</td>
										<td width=\"5%\"><a href=\"../categoria/".$subcategoria['categoria_nombre']."\"><img src=\"../imagenes/ir2.png\" /></a></td>
										<td width=\"5%\"><a href=\"?opcion=editar-categoria&amp;id=".$subcategoria['categoria_id']."\"><img src=\"../imagenes/editar.png\" /></a></td>
										<td width=\"5%\"><a href=\"?opcion=eliminar-categoria&amp;id=".$subcategoria['categoria_id']."\"><img src=\"../imagenes/eliminar.png\" /></a></td>
									</tr>
									";
						}
					}
					echo "</tbody>
					</table>";
				}
				elseif($_GET['opcion']=="agregar-ciudad"){
		?>
		<h2>Agregar Zona</h2>
			<?php
				if(!$_POST || ($_POST['ciudad_padre']==0 && $_POST['ciudad_prefijotel']=="")){
				}
				else{
					$datos = array(
						"id_padre"		=>	$_POST['ciudad_padre'],
						"titulo"		=>	htmlentities(strip_tags($_POST['ciudad_nombre']), ENT_QUOTES, "utf-8"),
						"prefijotel"	=>	$_POST['ciudad_prefijotel']
					);
					if($datos['id_padre']!=0){
						$datos_ciudad = obtener_datos_ciudad($_POST['ciudad_padre']);
						$pref = $datos_ciudad['prefijotel'];
						$datos['prefijotel']=$pref;
					}
					$rs = mysql_query("INSERT INTO lugares (lugar_idpadre, lugar_titulo, lugar_nombre, lugar_prefijotel) VALUES (".$datos['id_padre'].", '".$datos['titulo']."', '".crear_nombre($datos['titulo'])."', '".$datos['prefijotel']."')");
					if(!$rs){
						echo "<div class=\"mensaje error\">Error al crear zona.</div>";
					}
					else{
						echo "<div class=\"mensaje exito\">Zona creada con &eacute;xito.</div>";
					}
				}
			?>
			<form action="" method="post">
				<label>Ciudad padre:</label>
				<select name="ciudad_padre" onclick="javascript:document.getElementById('ciudad_prefijotel').value='';">
					<option value="0">Agregar como ciudad padre</option>
					<?php
						$rs = mysql_query("SELECT * FROM lugares WHERE lugar_idpadre=0");
						while($ciudad = mysql_fetch_array($rs)){
							echo '<option value="'.$ciudad['lugar_id'].'" onclick="javascript:document.getElementById(\'ciudad_prefijotel\').value=\''.$ciudad['lugar_prefijotel'].'\';" >'.stripslashes($ciudad['lugar_titulo']).'</option>
							';
						}
					?>
				</select>
				<label>Nombre:</label>
				<input type="text" name="ciudad_nombre" />
				<label>Prefijo Telefónico:</label>
				<input type="text" name="ciudad_prefijotel" id="ciudad_prefijotel" />
				<input type="submit" value="Agregar zona" onclick="javascript:this.disabled=true;" />
			</form>
		<?php
				}
				elseif($_GET['opcion']=="administrar-ciudades"){
		?>
		<h2>Administrar Zonas</h2>
			<?php
					$rs = mysql_query("SELECT * FROM lugares WHERE lugar_idpadre=0");
					echo "<table>
								<thead>
									<tr>
										<th width=\"85%\">Nombre</th>
										<th width=\"5%\" colspan=\"3\">Acciones</th>
									</tr>
								</thead>
								<tbody>
					";
					while($ciudad = mysql_fetch_array($rs)){
						echo "<tr class=\"registro-tabla\"><td width=\"100%\" colspan=\"2\"><strong>".$ciudad['lugar_titulo']."</strong></td><td width=\"5%\"><a href=\"?opcion=editar-ciudad&amp;id=".$ciudad['lugar_id']."\"><img src=\"../imagenes/editar.png\" /></a></td><td width=\"5%\"><a href=\"?opcion=eliminar-ciudad&amp;id=".$ciudad['lugar_id']."\"><img src=\"../imagenes/eliminar.png\" /></a></td></tr>";
						$subrs = mysql_query("SELECT * FROM lugares WHERE lugar_idpadre=".$ciudad['lugar_id']);
						while($subciudad = mysql_fetch_array($subrs)){
							echo "<tr class=\"registro-tabla\"><td width=\"100%\" style=\"padding-left:10px;\">".$subciudad['lugar_titulo']."</td><td width=\"5%\"><a href=\"../ciudad/".$subciudad['lugar_nombre']."\"><img src=\"../imagenes/ir2.png\" /></a></td><td width=\"5%\"><a href=\"?opcion=editar-ciudad&amp;id=".$subciudad['lugar_id']."\"><img src=\"../imagenes/editar.png\" /></a></td><td width=\"5%\"><a href=\"?opcion=eliminar-ciudad&amp;id=".$subciudad['lugar_id']."\"><img src=\"../imagenes/eliminar.png\" /></a></td></tr>";
						}
					}
					echo "
								</tbody>
					</table>";
			?>
		<?php
				}
				elseif($_GET['opcion']=="editar-ciudad"){
		?>
		<h2>Editar Zona: <?php echo obtener_titulo_ciudad(obtener_nombre_ciudad($_GET['id']));?></h2>
			<?php
					if($_GET['id']!=""){
						if(!$_POST){
						}
						else{
							$datos_ciudad = obtener_datos_ciudad($_POST['ciudad_padre']);
							$pref = $datos_ciudad['prefijotel'];
							$titulo = htmlentities(strip_tags($_POST['ciudad_nombre']), ENT_QUOTES, "utf-8");
							$rs = mysql_query("UPDATE lugares SET lugar_idpadre=".$_POST['ciudad_padre'].", lugar_titulo='".$titulo."', lugar_nombre='".crear_nombre($titulo)."', lugar_prefijotel='".$pref."' WHERE lugar_id=".$_GET['id']);
							if(!$rs){
								echo "<div class=\"mensaje\">Error al editar el registro.</div>";
							}
							else{
								echo "<div class=\"mensaje\">Registro editado con &eacute;xito.</div>";
							}
						}
						$rs = mysql_query("SELECT * FROM lugares WHERE lugar_id=".$_GET['id']);
						$ciudad = mysql_fetch_array($rs);
		?>
		<form action="" method="post">
				<label>Ciudad padre:</label>
				<select name="ciudad_padre">
					<option value="0">Agregar como ciudad padre</option>
					<?php
						$rs2 = mysql_query("SELECT * FROM lugares WHERE lugar_idpadre=0 ORDER BY lugar_titulo ASC");
						while($listaciudad = mysql_fetch_array($rs2)){
							if($ciudad['lugar_idpadre']!=0 && $listaciudad['lugar_id']==$ciudad['lugar_idpadre']){
								$selected = " selected=\"selected\"";
							}
							else{
								$selected="";
							}
							echo "<option value=\"".$listaciudad['lugar_id']."\"$selected>".$listaciudad['lugar_titulo']."</option>\n";
						}
					?>
				</select>
				<label>Nombre:</label>
				<input type="text" name="ciudad_nombre" value="<?php echo stripslashes($ciudad['lugar_titulo']); ?>" />
				<input type="submit" value="Editar zona" onclick="javascript:this.disabled=true;" />
		</form>
		<?php
					}
				}
				elseif($_GET['opcion']=="eliminar-ciudad"){
		?>
		<h2>Eliminar Zona</h2>
			<?php
					if($_GET['id']!=""){
						$rs = mysql_query("DELETE FROM lugares WHERE lugar_id=".$_GET['id']);
					}
					if(!$rs){
						echo "<div class=\"mensaje error\">No se pudo eliminar la zona.</div>";
					}
					else{
						echo "<div class=\"mensaje exito\">Zona eliminada con &eacute;xito.</div>";
					}
					$rs = mysql_query("SELECT * FROM lugares WHERE lugar_idpadre=0");
					echo "<table>
								<thead>
									<tr>
										<th width=\"85%\">Nombre</th>
										<th width=\"5%\" colspan=\"3\">Acciones</th>
									</tr>
								</thead>
								<tbody>
					";
					while($ciudad = mysql_fetch_array($rs)){
						echo "<tr class=\"registro-tabla\"><td width=\"100%\" colspan=\"2\"><strong>".$ciudad['lugar_titulo']."</strong></td><td width=\"5%\"><a href=\"?opcion=editar-ciudad&amp;id=".$ciudad['lugar_id']."\"><img src=\"../imagenes/editar.png\" /></a></td><td width=\"5%\"><a href=\"?opcion=eliminar-ciudad&amp;id=".$ciudad['lugar_id']."\"><img src=\"../imagenes/eliminar.png\" /></a></td></tr>";
						$subrs = mysql_query("SELECT * FROM lugares WHERE lugar_idpadre=".$ciudad['lugar_id']);
						while($subciudad = mysql_fetch_array($subrs)){
							echo "<tr class=\"registro-tabla\"><td width=\"100%\" style=\"padding-left:10px;\">".$subciudad['lugar_titulo']."</td><td width=\"5%\"><a href=\"../ciudad/".$subciudad['lugar_nombre']."\"><img src=\"../imagenes/ir2.png\" /></a></td><td width=\"5%\"><a href=\"?opcion=editar-ciudad&amp;id=".$subciudad['lugar_id']."\"><img src=\"../imagenes/editar.png\" /></a></td><td width=\"5%\"><a href=\"?opcion=eliminar-ciudad&amp;id=".$subciudad['lugar_id']."\"><img src=\"../imagenes/eliminar.png\" /></a></td></tr>";
						}
					}
					echo "
								</tbody>
					</table>";
				}
				elseif($_GET['opcion']=="agregar-pagina"){
		?>
		<h2>Agregar P&aacute;gina est&aacute;tica</h2>
			<?php
					if(!$_POST){
					}
					else{
						$datos = array(
							"titulo"		=>	htmlentities(strip_tags($_POST['pagina_titulo']), ENT_QUOTES, "utf-8"),
							"contenido"	=>	htmlentities(stripslashes($_POST['pagina_contenido']), ENT_QUOTES, "utf-8")
						);
						$rs = mysql_query("INSERT INTO paginas (pagina_titulo, pagina_nombre, pagina_contenido) VALUES ('".$datos['titulo']."', '".crear_nombre($datos['titulo'])."', '".$datos['contenido']."')");
						if(!$rs){
							echo "<div class=\"mensaje error\">La p&aacute;gina no se pudo crear.</div>";
						}
						else{
							echo "<div class=\"mensaje exito\">La p&aacute;gina se cre&oacute; con &eacute;xito.</div>";
						}
					}
			?>
			<form action="" method="post">
				<label>Título:</label>
				<input type="text" name="pagina_titulo" />
				<label>Contenido:</label>
				<textarea name="pagina_contenido"></textarea>
				<input type="button" onclick="javascript:tinyMCE.execCommand('mceToggleEditor',false,'pagina_contenido');return false;" value="HTML" />
				<input type="submit" value="Agregar p&aacute;gina" onclick="javascript:this.disabled=true;" />
			</form>
			<?php
				}
				elseif($_GET['opcion']=="administrar-paginas"){
		?>
		<h2>Administrar P&aacute;ginas</h2>
			<?php
					$rs = mysql_query("SELECT * FROM paginas");
					echo "<table>
								<thead>
									<tr>
										<th width=\"85%\">Nombre</th>
										<th width=\"5%\" colspan=\"3\">Acciones</th>
									</tr>
								</thead>
								<tbody>
					";
					while($pagina = mysql_fetch_array($rs)){
						echo "<tr class=\"registro-tabla\">
							<td width=\"100%\" style=\"padding-left:10px;\">".$pagina['pagina_titulo']."</td>
							<td width=\"5%\"><a href=\"../pagina/".$pagina['pagina_nombre']."\"><img src=\"../imagenes/ir2.png\" /></a></td>
							<td width=\"5%\"><a href=\"?opcion=editar-pagina&amp;id=".$pagina['pagina_id']."\"><img src=\"../imagenes/editar.png\" /></a></td>
							<td width=\"5%\"><a href=\"?opcion=eliminar-pagina&amp;id=".$pagina['pagina_id']."\"><img src=\"../imagenes/eliminar.png\" /></a></td>
						</tr>";
					}
					echo "
								</tbody>
					</table>";
			?>
			<?php
				}
				elseif($_GET['opcion']=="editar-pagina"){
		?>
		<h2>Editar Página</h2>
			<?php
					if($_GET['id']!=""){
						if(!$_POST){
						}
						else{
							$titulo = htmlentities(strip_tags($_POST['pagina_titulo']), ENT_QUOTES, "utf-8");
							$contenido = htmlentities(stripslashes($_POST['pagina_contenido']), ENT_QUOTES, "utf-8");
							$rs = mysql_query("UPDATE paginas SET pagina_titulo='".$titulo."', pagina_nombre='".crear_nombre($titulo)."', pagina_contenido='$contenido' WHERE pagina_id=".$_GET['id']);
							if(!$rs){
								echo "<div class=\"mensaje\">Error al editar la p&aacute;gina.</div>";
							}
							else{
								echo "<div class=\"mensaje\">P&aacute;gina editada con &eacute;xito.</div>";
							}
						}
						$rs = mysql_query("SELECT * FROM paginas WHERE pagina_id=".$_GET['id']);
						$pagina = mysql_fetch_array($rs);
			?>
			<form action="" method="post">
					<label>Título:</label>
					<input type="text" name="pagina_titulo" value="<?php echo $pagina['pagina_titulo']; ?>" />
					<label>Contenido:</label>
					<textarea name="pagina_contenido"><?php echo $pagina['pagina_contenido']; ?></textarea>
					<input type="button" onclick="javascript:tinyMCE.execCommand('mceToggleEditor',false,'pagina_contenido');return false;" value="HTML" /><p style="display:inline;"><a href="<?php echo "../pagina/".$pagina['pagina_nombre']; ?>">&raquo;Ver Página</a></p>
					<input type="submit" value="Editar p&aacute;gina" onclick="javascript:this.disabled=true;" />
			</form>
			<?php
					}
				}
				elseif($_GET['opcion']=="eliminar-pagina"){
					if($_GET['id']!=""){
						$rs = mysql_query("DELETE FROM paginas WHERE pagina_id=".$_GET['id']);
					}
					if(!$rs){
						echo "<div class=\"mensaje error\">No se pudo eliminar la p&aacute;gina.</div>";
					}
					else{
						echo "<div class=\"mensaje exito\">P&aacute;gina eliminada con &eacute;xito.</div>";
					}
					$rs = mysql_query("SELECT * FROM paginas");
					echo "<table>
								<thead>
									<tr>
										<th width=\"85%\">Nombre</th>
										<th width=\"5%\" colspan=\"3\">Acciones</th>
									</tr>
								</thead>
								<tbody>
					";
					while($pagina = mysql_fetch_array($rs)){
						echo "<tr class=\"registro-tabla\">
							<td width=\"100%\" style=\"padding-left:10px;\">".$pagina['pagina_titulo']."</td>
							<td width=\"5%\"><a href=\"../pagina/".$pagina['pagina_nombre']."\"><img src=\"../imagenes/ir2.png\" /></a></td>
							<td width=\"5%\"><a href=\"?opcion=editar-pagina&amp;id=".$pagina['pagina_id']."\"><img src=\"../imagenes/editar.png\" /></a></td>
							<td width=\"5%\"><a href=\"?opcion=eliminar-pagina&amp;id=".$pagina['pagina_id']."\"><img src=\"../imagenes/eliminar.png\" /></a></td>
						</tr>";
					}
					echo "
								</tbody>
					</table>";
				}
				//elseif
			}
		?>
	</div>
</div>
<div id="pie">
Ir a <?php echo "<a href=\"../\">".nombre_sitio."</a>"; ?>
</div>
</body>
</html>
<?php
		}
	}
?>