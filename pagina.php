<?php
include(dirname(__FILE__)."/config.php");
include(ruta_sitio."/incluidos/funciones.php");
include(ruta_sitio."/incluidos/cabecera.php");

if($_GET['pagina']!="" && $_GET['pagina']!="contacto"){
	$db = conectar_db();
	$rs = mysql_query("SELECT * FROM paginas WHERE pagina_nombre='".$_GET['pagina']."'");
	if(mysql_num_rows($rs)==0){
?>
	<div id="cuerpo">
		<div class="bloque" id="pagina">
			<h2>P&aacute;gina no encontrada</h2>
			<p>Al parecer llegaste aqu&iacute; por error. Puedes volver a la <a href="<?php echo url_sitio; ?>">p&aacute;gina principal</a>.</p>
		</div>
	</div>
<?php
	}
	else{
		$pagina = mysql_fetch_array($rs);
?>
	<div id="cuerpo">
		<div class="bloque" id="pagina">
			<h2><?php echo $pagina['pagina_titulo']; ?></h2>
			<?php echo html_entity_decode($pagina['pagina_contenido'], ENT_QUOTES, "utf-8"); ?>
		</div>
	</div>
<?php
	}
	include(ruta_sitio."/incluidos/pie.php");
}
elseif($_GET['pagina']=="contacto"){
?>
	<div id="cuerpo">
		<div class="bloque" id="pagina">
			<h2>Contacto</h2>
			<p>Puedes contactarnos envi&aacute;ndonos un mail a contacto@vaope.com, o si deseas usando el formulario de contacto que est&aacute; a continuaci&oacute;n:</p>
			<div id="formulario-contacto">
			<?php
				if($_POST['nombre']=="" || $_POST['mensaje']==""){
			?>
			<form action="<?php echo url_sitio; ?>/pagina/contacto" method="post">
				<label>Nombre:</label>
				<input type="text" name="nombre" />
				<label>E-mail:</label>
				<input type="text" name="email" />
				<p class="info">Por si deseas que te demos una respuesta.</p>
				<label>Mensaje:</label>
				<textarea name="mensaje"></textarea>
				<input type="submit" value="Enviar" />
			</form>
			<?php
				}
				else{
					if($_POST['email']==""){
						$email="anonimo@vaope.com";
					}
					else{
						$email=$_POST['email'];
					}
					$mensaje='
						'.$_POST['nombre'].' ('.$email.') escribi&oacute;:
						'.htmlentities($_POST['mensaje'], ENT_QUOTES, "utf-8").'
					';
					$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
					$cabeceras .= 'Content-type: text/html; charset=uft-8' . "\r\n";
					
					$cabeceras .= 'To: Contacto Vao Pe! <contacto@vaope.com>' . "\r\n";
					$cabeceras .= 'From: '.$_POST['nombre'].' <'.$email.'>' . "\r\n";
					if(mail("contacto@vaope.com", "Contacto", $mensaje, $cabeceras)){
						echo "<p>El mensaje ha sido enviado. Recibir&aacute;s una respuesta muy muy pronto ;).</p>";
					}
					else{
						echo "<p>Ops! al parecer hay problemas. Prueba a enviar un correo a contacto@vaope.com.</p>";
					}
				}
			?>
			</div>
		</div>
	</div>
<?php
	include(ruta_sitio."/incluidos/pie.php");
}
?>