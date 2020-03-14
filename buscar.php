<?php
include(dirname(__FILE__)."/config.php");
include(ruta_sitio."/incluidos/funciones.php");
include(ruta_sitio."/incluidos/cabecera.php");
$db = conectar_db();
$plantilla = file_get_contents(ruta_sitio."/incluidos/index.html");
$plantilla_bloque = file_get_contents(ruta_sitio."/incluidos/plantilla.bloque.html");
$s = $_GET['s'];
if($s!=""){
	$opcion = $_GET['opcion'];
	switch($opcion){
		case 'titulo':$consulta = "SELECT * FROM locales WHERE local_titulo LIKE '%$s%'";break;
		case 'descripcion':$consulta = "SELECT * FROM locales WHERE local_descripcion LIKE '%$s%'";break;
		case 'etiquetas':$consulta = "SELECT * FROM locales WHERE local_tags LIKE '%,".htmlentities($s,ENT_QUOTES,"utf-8").",%'";break;
	}
?>
<h2>Resultados de '<?php echo htmlentities($s,ENT_QUOTES,"utf-8"); ?>'</h2>
	<div id="cuerpo">
		<div class="bloque" id="resultados">
			<h2>Resultados</h2>
			<?php
				$n_registros = 15;
				$pagina = $_GET['pagina'];
				if(!$pagina){
					$pagina = 1;
					$inicio = 0;
				}
				else{
					$inicio = ($pagina-1)*$n_registros;
				}
				$db = conectar_db();
				$rs = mysql_query($consulta);
				if(mysql_num_rows($rs)==0){
					$lista = "<div class=\"mensaje\">A&uacute;n no hay locales.</div>";
				}
				else{
					$total_resultados = mysql_num_rows($rs);
					$total_paginas = ceil($total_resultados/$n_registros);
					if($total_paginas>1){
						$lista = "<ul class=\"paginacion\">";
						for($i=1;$i<=$total_paginas;$i++){
							if($pagina==$i){
								$lista .= "<li>".$i."</li>";
							}
							else{
								$lista .= "<li><a href=\"".url_sitio."/?s=$s&amp;opcion=$opcion&amp;pagina=$i\">$i</a></li>";
							}
						}
						$lista .= "</ul>";
					}
					$rs = mysql_query($consulta." LIMIT $inicio,$n_registros");
					while($local = mysql_fetch_array($rs)){
						$lista .= llenar_plantilla_local("lista", $local['local_id'], $local['local_titulo'], $local['local_nombre'], $local['local_descripcion'], $local['local_direccion'], $local['local_telefono'], $local['local_puntuacion'], obtener_nombre_ciudad($local['ciudad_id']), obtener_nombre_categoria($local['categoria_id']), $local['local_usuario']);
					}
				}
				echo $lista;
			}
			?>
		</div>
	</div>
<?php
include(ruta_sitio."/incluidos/pie.php");
?>