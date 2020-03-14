<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo crear_titulo_sitio();?></title>
<meta name="description" content="<?php echo descripcion_sitio; ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo url_sitio; ?>/incluidos/estilo.css" />
<?php
	if($_GET['ciudad']!="" && $_GET['categoria']!=""){
?>
<link rel="alternate" type="application/rss+xml" title="Feed de <?php echo obtener_titulo_categoria($_GET['categoria']); ?> en <?php echo obtener_titulo_ciudad($_GET['ciudad']); ?>" href="<?php echo url_sitio."/feed/".$_GET['ciudad']."/".$_GET['categoria'] ;?>" />
<?php
	}
	elseif($_GET['ciudad']!="" && !$_GET['categoria']){
?>
<link rel="alternate" type="application/rss+xml" title="Feed de locales en <?php echo obtener_titulo_ciudad($_GET['ciudad']); ?>" href="<?php echo url_sitio."/feed/".$_GET['ciudad'] ;?>/" />
<?php
	}
	if($_GET['seccion']=="favoritos"){
?>
<link rel="alternate" type="application/rss+xml" title="Feed de locales favoritos de <?php echo $_GET['usuario']; ?>" href="<?php echo url_sitio."/feed/favoritos/".$_GET['usuario'] ;?>/" />
<?php
	}
?>
<?php
if($js==1){
?>
<script type="text/javascript" src="<?php echo url_sitio; ?>/incluidos/mootools.js"></script>
<?php
}
if($editor==1){
?>
<script type="text/javascript" src="<?php echo url_sitio; ?>/incluidos/niceditor/nicEdit.js"></script>
<?php
}
?>
<?php
if($toggle_categorias_ciudades==1){
?>
<script type="text/javascript">
	window.addEvent('domready', function(){
		<?php
			$db = conectar_db();
			$rs = mysql_query("SELECT * FROM lugares WHERE lugar_idpadre=0");
			while($lugar = mysql_fetch_array($rs)){
		?>
		var slidel<?php echo $lugar['lugar_id']; ?> = new Fx.Slide('lista-<?php echo $lugar['lugar_nombre']; ?>');
		$('titulo-<?php echo $lugar['lugar_nombre']; ?>').addEvent('click', function(e){
			e = new Event(e);
			slidel<?php echo $lugar['lugar_id']; ?>.toggle();
			e.stop();
		});
		<?php
			}
		?>
		
		<?php
			$rs = mysql_query("SELECT * FROM categorias WHERE categoria_idpadre=0");
			while($categoria = mysql_fetch_array($rs)){
		?>
		var slidec<?php echo $categoria['categoria_id']; ?> = new Fx.Slide('lista-<?php echo $categoria['categoria_nombre']; ?>');
		$('titulo-<?php echo $categoria['categoria_nombre']; ?>').addEvent('click', function(e){
			e = new Event(e);
			slidec<?php echo $categoria['categoria_id']; ?>.toggle();
			e.stop();
		});
		<?php
			}
		?>
	});
</script>
<?php
}
?>
</head>
<body>
<div id="cabecera">
	<h1><a href="<?php echo url_sitio; ?>/">Vao Pe!</a></h1>
	<div id="contenedor-cabecera">
    	<div id="extras">
        	<h2><a href="http://blog.vaope.com">Blog</a></h2>
        	<h2><a href="http://blog.vaope.com">Fotos</a></h2>
        </div>
		<div id="buscador">
			<form action="<?php echo url_sitio; ?>/buscar/" method="get">
				<input type="text" name="s" id="s" value="<?php echo $_GET['s']; ?>" />
				<select name="opcion">
					<option value="etiquetas" selected="selected">buscar por etiquetas</option>
					<option value="titulo">buscar por t&iacute;tulo</option>
					<option value="descripcion">buscar por descripci&oacute;n</option>
				</select>
				<input type="submit" value="Buscar" id="boton-buscar" />
			</form>
		</div>
		<div id="form-login">
			<?php
				if(!$_COOKIE || $_COOKIE['nick']=="" || $_COOKIE['clave']==""){
			?>
				<form action="<?php echo url_sitio; ?>/login.php" method="post">
					<label for="nick">Nick:</label>
					<input type="text" name="nick" id="nick" />
					<label for="clave">Clave:</label>
					<input type="password" name="clave" id="clave" />
					<input type="submit" value="Entrar" />
				</form>
				<p><a href="<?php echo url_sitio; ?>/registro" id="boton-registro">Reg&iacute;strate</a></p>
			<?php
				}
				else{
			?>
				<ul id="opciones-login">
					<li><a href="<?php echo url_sitio; ?>/usuario/<?php echo $_COOKIE['nick']; ?>"><strong><?php echo $_COOKIE['nick']; ?></strong></a></li>
					<li id="tu-cuenta"><a href="<?php echo url_sitio; ?>/panel/">Tu cuenta</a></li>
					<li id="favoritos"><a href="<?php echo url_sitio; ?>/usuario/<?php echo $_COOKIE['nick']; ?>/favoritos">Favoritos</a></li>
					<li id="agregar-local"><a href="<?php echo url_sitio; ?>/panel/agregar-local">Agregar local</a></li>
					<li id="cerrar-sesion"><a href="<?php echo url_sitio; ?>/logout.php">Cerrar Sesi&oacute;n</a></li>
				</ul>
			<?php
				}
			?>
		</div>
	</div>
</div>
