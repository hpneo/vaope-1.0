<?php
	include("./config.php");
	include("./incluidos/funciones.php");
	include(ruta_sitio."/incluidos/cabecera.php");
	echo '
	<div class="bloque" id="registro">
		<h2>Regístrate</h2>
	';
	if($_GET['error']=="1"){
		echo '<div class="mensaje error">No has escrito un nick.</div>';
	}
	if($_GET['error']=="2"){
		echo '<div class="mensaje error">No has escrito una clave.</div>';
	}
	if($_GET['error']=="3"){
		echo '<div class="mensaje error">No has escrito un e-mail o no es v&aacute;lido.</div>';
	}
	echo'
		<form action="'.url_sitio.'/registro2.php" method="post">
			<label class="obligatorio">Nick:</label>
			<input type="text" name="usuario_nick" id="usuario_nick" value="'.$_GET['nick'].'" />
			<label class="obligatorio">Clave:</label>
			<input type="password" name="usuario_clave" id="usuario_clave" value="'.$_GET['clave'].'" />
			<label class="obligatorio">Email:</label>
			<input type="text" name="usuario_email" id="usuario_email" value="'.$_GET['email'].'" />
			<label style="text-align:justify;">Descr&iacute;bete:</label>
			<textarea name="usuario_descripcion" id="usuario_descripcion"></textarea>
			<label style="text-align:justify;">P&aacute;gina web:</label>
			<input type="text" name="usuario_url" id="usuario_url" />
			<input type="submit" id="enviar" value="Reg&iacute;strate" />
			<p class="info">Los campos marcados con asteriscos son obligatorios.</p>
		</form>
	</div>
	';
	include(ruta_sitio."/incluidos/pie.php");
?>