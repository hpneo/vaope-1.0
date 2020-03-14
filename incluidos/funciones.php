<?php
	//------------------------------------------------------------------------------------------------------------
	function conectar_db(){
		$db = mysql_connect(servidor_db, usuario_db, clave_db);
		mysql_select_db(nombre_db, $db);
		return $db;
	}
	//------------------------------------------------------------------------------------------------------------
	function gravatar($email, $size){
		$default = url_sitio."/imagenes/gravatar.jpg";
		$url = "http://www.gravatar.com/avatar.php?gravatar_id=".md5($email)."&amp;default=".urlencode($default)."&amp;size=".$size;
		return $url;
	}
	//------------------------------------------------------------------------------------------------------------
	//Crea la url para las categorias o ciudades de acuerdo a la sección en la que se encuentra.
	function crear_url($tipo, $id, $ciudad=NULL, $categoria=NULL){
		$db = conectar_db();
		if($tipo=="categoria"){
			$rs = mysql_query("SELECT * FROM categorias WHERE categoria_id=".$id);
		}
		elseif($tipo=="lugar"){
			$rs = mysql_query("SELECT * FROM lugares WHERE lugar_id=".$id);
		}
		$nombre = mysql_fetch_array($rs);
		if($tipo=="categoria"){
			if($ciudad==""){
				$url = url_sitio."/categoria/".$nombre[$tipo.'_nombre']."";
			}
			else{
				$url = url_sitio."/locales/$ciudad/".$nombre[$tipo.'_nombre']."/";
			}
		}
		elseif($tipo=="lugar"){
			if($categoria==""){
				$url = url_sitio."/ciudad/".$nombre[$tipo.'_nombre']."";
			}
			else{
				$url = url_sitio."/locales/".$nombre[$tipo.'_nombre']."/$categoria/";
			}
		}
		mysql_free_result($rs);
		return $url;
	}
	//------------------------------------------------------------------------------------------------------------
	//Obtiene lista de categorias para el index.
	function obtener_categorias($padre, $nivel, $ciudad=NULL){
		$db = conectar_db();
		$nivel++;
		$rs = mysql_query("SELECT * FROM categorias WHERE categoria_idpadre =$padre ORDER BY categoria_titulo", $db);
		while($categoria = mysql_fetch_array($rs)){
			if($padre==0){
				$categorias.="<h3 id=\"titulo-".$categoria["categoria_nombre"]."\">".$categoria["categoria_titulo"]."</h3>\n";
			}
			else{
				if($ciudad==""){
					$lista.="<li><a href=\"".crear_url("categoria", $categoria["categoria_id"], $ciudad)."\">".$categoria["categoria_titulo"]."</a></li>\n";
				}
				else{
					//$lista.="<li><a href=\"".crear_url("categoria", $categoria["categoria_id"], $ciudad)."\">".$categoria["categoria_titulo"]."</a><a class=\"feed\" href=\"".url_sitio."/feed/".$ciudad."/".$categoria["categoria_nombre"]."\" title=\"Feed de la categoría\">(<img src=\"".url_sitio."/imagenes/feed.png\" alt=\"RSS\" />)</a></li>\n";
					$lista.="<li><a href=\"".crear_url("categoria", $categoria["categoria_id"], $ciudad)."\">".$categoria["categoria_titulo"]."</a></li>\n";
				}
			}
			$categorias.=obtener_categorias($categoria['categoria_id'], $nivel, $ciudad);
		}
		if($padre!=0 && $lista!=""){
			$categorias.="<ul id=\"lista-".obtener_nombre_categoria($padre)."\">\n$lista</ul>\n";
		}
		mysql_free_result($rs);
		return $categorias;
	}
	//------------------------------------------------------------------------------------------------------------
	//Obtiene lista de ciudades para el index.
	function obtener_ciudades($padre, $nivel, $categoria=NULL){
		$db = conectar_db();
		$nivel++;
		$rs = mysql_query("SELECT * FROM lugares WHERE lugar_idpadre =$padre ORDER BY lugar_titulo", $db);
		while($lugar = mysql_fetch_array($rs)){
			if($padre==0){
				$ciudades.="<h3 id=\"titulo-".$lugar["lugar_nombre"]."\">".$lugar["lugar_titulo"]."</h3>\n";
			}
			else{
				$lista.="<li><a href=\"".crear_url("lugar", $lugar["lugar_id"], "", $categoria)."\">".$lugar["lugar_titulo"]."</a></li>\n";
			}
			$ciudades.=obtener_ciudades($lugar["lugar_id"], $nivel, $categoria);
		}
		if($padre!=0 && $lista!=""){
			$ciudades.="<ul id=\"lista-".obtener_nombre_ciudad($padre)."\">\n$lista</ul>\n";
		}
		mysql_free_result($rs);
		return $ciudades;
	}
	//------------------------------------------------------------------------------------------------------------
	//Obtiene el id de una categoria dada.
	function obtener_id_categoria($categoria){
		$db = conectar_db();
		$rs = mysql_query("SELECT categoria_id FROM categorias WHERE categoria_nombre='$categoria'");
		if(!$rs){
			return -1;
		}
		else{
			$id = mysql_fetch_array($rs);
			return $id['categoria_id'];
		}
	}
	//------------------------------------------------------------------------------------------------------------
	function obtener_datos_categoria($id){
		$db = conectar_db();
		$rs = mysql_query("SELECT * FROM categorias WHERE categoria_id=".$id);
		$datos = mysql_fetch_array($rs);
		$categoria = array(
			'idpadre'		=>	$datos['categoria_idpadre'],
			'titulo'		=>	$datos['categoria_titulo'],
			'nombre'		=>	$datos['categoria_nombre'],
		);
		return $categoria;
		mysql_free_result($rs);
	}
	//------------------------------------------------------------------------------------------------------------
	function obtener_nombre_categoria($id){
		$categoria = obtener_datos_categoria($id);
		return $categoria['nombre'];
	}
	//------------------------------------------------------------------------------------------------------------
	//Obtiene el titulo de una categoria dada.
	function obtener_titulo_categoria($categoria){
		$categoria = obtener_datos_categoria(obtener_id_categoria($categoria));
		return $categoria['titulo'];
	}
	//------------------------------------------------------------------------------------------------------------
	//Obtiene el id de un lugar dado.
	function obtener_id_ciudad($ciudad){
		$db = conectar_db();
		$rs = mysql_query("SELECT lugar_id FROM lugares WHERE lugar_nombre='$ciudad'");
		if(!$rs){
			return -1;
		}
		else{
			$id = mysql_fetch_array($rs);
			return $id['lugar_id'];
		}
	}
	//------------------------------------------------------------------------------------------------------------
	function obtener_datos_ciudad($id){
		$db = conectar_db();
		$rs = mysql_query("SELECT * FROM lugares WHERE lugar_id=".$id);
		$datos = mysql_fetch_array($rs);
		$ciudad = array(
			'idpadre'		=>	$datos['lugar_idpadre'],
			'titulo'		=>	$datos['lugar_titulo'],
			'nombre'		=>	$datos['lugar_nombre'],
			'prefijotel'	=>	$datos['lugar_prefijotel']
		);
		return $ciudad;
		mysql_free_result($rs);
	}
	//------------------------------------------------------------------------------------------------------------
	function obtener_nombre_ciudad($id){
		$ciudad = obtener_datos_ciudad($id);
		return $ciudad['nombre'];
	}
	//------------------------------------------------------------------------------------------------------------
	//Obtiene el titulo de una ciudad dada.
	function obtener_titulo_ciudad($ciudad){
		$ciudad = obtener_datos_ciudad(obtener_id_ciudad($ciudad));
		return $ciudad['titulo'];
	}
	//------------------------------------------------------------------------------------------------------------
	function obtener_id_local($local){
		$db = conectar_db();
		$rs = mysql_query("SELECT local_id FROM locales WHERE local_nombre='$local'");
		if(!$rs){
			return -1;
		}
		else{
			$id = mysql_fetch_array($rs);
			return $id['local_id'];
		}
	}
	//------------------------------------------------------------------------------------------------------------
	function obtener_datos_local($local_id){
		$db = conectar_db();
		$rs = mysql_query("SELECT * FROM locales WHERE local_id=$local_id");
		if(mysql_num_rows($rs)==0){
			return NULL;
		}
		else{
			return mysql_fetch_array($rs);
		}
		mysql_free_result($rs);
	}
	//------------------------------------------------------------------------------------------------------------
	function obtener_nombre_local($id){
		$local = obtener_datos_local($id);
		return $local['local_nombre'];
	}
	//------------------------------------------------------------------------------------------------------------
	function obtener_titulo_local($local){
		$local = obtener_datos_local(obtener_id_local($local));
		return stripslashes($local['local_titulo']);
	}
	//------------------------------------------------------------------------------------------------------------
	//Obtiene el id de un usuario dado.
	function obtener_id_usuario($usuario){
		$db = conectar_db();
		$rs = mysql_query("SELECT usuario_id FROM usuarios WHERE usuario_nick='$usuario'");
		if(!$rs){
			return -1;
		}
		else{
			$id = mysql_fetch_array($rs);
			return $id['usuario_id'];
		}
	}
	//------------------------------------------------------------------------------------------------------------
	function obtener_datos_usuario($id){
		$db = conectar_db();
		$rs = mysql_query("SELECT * FROM usuarios WHERE usuario_id=".$id);
		$datos = mysql_fetch_array($rs);
		$usuario = array(
			'nick'			=>	$datos['usuario_nick'],
			'email'			=>	$datos['usuario_email'],
			'descripcion'	=>	$datos['usuario_descripcion'],
			'url'				=>	$datos['usuario_url']
		);
		return $usuario;
	}
	//------------------------------------------------------------------------------------------------------------
	function obtener_nick_usuario($id_usuario){
		$usuario = obtener_datos_usuario($id_usuario);
		return $usuario['nick'];
	}
	//------------------------------------------------------------------------------------------------------------
	function obtener_titulo_pagina($pagina){
		$db = conectar_db();
		$rs = mysql_query("SELECT pagina_titulo FROM paginas WHERE pagina_nombre='$pagina'");
		if(!$rs){
			return "";
		}
		else{
			$titulo = mysql_fetch_array($rs);
			return stripslashes($titulo['pagina_titulo']);
		}
		mysql_free_result($rs);
	}
	//------------------------------------------------------------------------------------------------------------
	function obtener_n_comentarios($id_local=NULL){
		$db = conectar_db();
		if($id_local!=""){
			$rs = mysql_query("SELECT * FROM comentarios WHERE local_id=".$id_local);
		}
		else{
			$rs = mysql_query("SELECT * FROM comentarios");
		}
		if(mysql_num_rows($rs)==0){
			return "A&uacute;n no hay comentarios";
		}
		elseif(mysql_num_rows($rs)==1){
			return "1 comentario";
		}
		else{
			return mysql_num_rows($rs)." comentarios";
		}
		mysql_free_result($rs);
	}
	//------------------------------------------------------------------------------------------------------------
	//Obtiene una lista de locales según categorías y/o ciudades
	function obtener_locales($categoria=NULL, $ciudad=NULL, $numero=NULL, $tag=NULL, $usuario=NULL){
		$db = conectar_db();
		if($numero!=""){
			$sql = " LIMIT $numero";
		}
		$n_registros = 20;
		$pagina = $_GET['pagina'];
		if(!$pagina){
			$pagina = 1;
			$inicio = 0;
		}
		else{
			$inicio = ($pagina-1)*$n_registros;
		}
		/*else{
			$sql = " LIMIT $inicio,$n_registros";
		}*/
		if($categoria!="" && $ciudad==""){
			$consulta = "SELECT * FROM locales WHERE categoria_id=".obtener_id_categoria($categoria)." ORDER BY local_puntuacion DESC".$sql;
		}
		elseif($ciudad!="" && $categoria==""){
			$consulta = "SELECT * FROM locales WHERE ciudad_id=".obtener_id_ciudad($ciudad)." ORDER BY local_puntuacion DESC".$sql;
		}
		elseif($categoria!="" && $ciudad!=""){
			$consulta = "SELECT * FROM locales WHERE ciudad_id=".obtener_id_ciudad($ciudad)." AND categoria_id=".obtener_id_categoria($categoria)." ORDER BY local_puntuacion DESC".$sql;
		}
		elseif($tag!=""){
			$consulta = "SELECT * FROM locales WHERE local_tags LIKE '%,".htmlentities($tag,ENT_QUOTES,"utf-8").",%' ORDER BY local_puntuacion DESC".$sql;
		}
		elseif($usuario!=""){
			$consulta = "SELECT * FROM locales WHERE local_usuario=".obtener_id_usuario($usuario)." ORDER BY local_fecha DESC".$sql;
		}
		else{
			if($numero==""){
				$consulta = "SELECT * FROM locales ORDER BY local_puntuacion DESC".$sql;
			}
			else{
				$consulta = "SELECT * FROM locales ORDER BY local_id DESC".$sql;
			}
		}
		$rs = mysql_query($consulta);
		if(mysql_num_rows($rs)==0){
			$lista = "<div class=\"mensaje\">A&uacute;n no hay locales.</div>";
		}
		else{
			if($numero==""){
				$total_resultados = mysql_num_rows($rs);
				$total_paginas = ceil($total_resultados/$n_registros);
				if($total_paginas>1){
					$lista = "<ul class=\"paginacion\">";
					for($i=1;$i<=$total_paginas;$i++){
						if($pagina==$i){
							$lista .= '<li id="actual">'.$i.'</li>';
						}
						else{
							if($categoria!="" && $ciudad==""){
								$lista .= "<li><a href=\"".url_sitio."/categoria/$categoria/$i\">$i</a></li>";
							}
							elseif($ciudad!="" && $categoria==""){
								$lista .= "<li><a href=\"".url_sitio."/ciudad/$ciudad/$i\">$i</a></li>";
							}
							elseif($categoria!="" && $ciudad!=""){
								$lista .= "<li><a href=\"".url_sitio."/locales/$ciudad/$categoria/$i\">$i</a></li>";
							}
						}
					}
					$lista .= "</ul>";
				}
			}
			if($numero==""){
				$rs = mysql_query($consulta." LIMIT $inicio,$n_registros");
			}
			else{
				$rs = mysql_query($consulta);
			}
			while($local = mysql_fetch_array($rs)){
				$lista .= llenar_plantilla_local("lista", $local['local_id'], $local['local_titulo'], $local['local_nombre'], $local['local_descripcion'], $local['local_direccion'], $local['local_telefono'], $local['local_puntuacion'], obtener_nombre_ciudad($local['ciudad_id']), obtener_nombre_categoria($local['categoria_id']), $local['local_usuario']);
			}
		}
		return $lista;
	}
	//------------------------------------------------------------------------------------------------------------	
	function obtener_locales_usuario($id_usuario, $n){
		$db = conectar_db();
		$rs = mysql_query("SELECT * FROM locales WHERE local_usuario=$id_usuario ORDER BY local_fecha DESC LIMIT $n");
		$lista = "<ul>";
		while($local=mysql_fetch_array($rs)){
			$lista .= "<li><a href=\"".url_sitio."/local/".$local['local_nombre']."\">".stripslashes($local['local_titulo'])."</a></li>\n";
		}
		$lista .= "</ul>";
		return $lista;
	}
	//------------------------------------------------------------------------------------------------------------
	function obtener_locales_relacionados($id_local, $id_categoria, $id_ciudad){
		$db = conectar_db();
		$rs = mysql_query("SELECT * FROM locales WHERE categoria_id=$id_categoria AND ciudad_id=$id_ciudad AND local_id!=$id_local ORDER BY local_puntuacion DESC");
		if(mysql_num_rows($rs)==0){
			return "<p>No hay locales relacionados.</p>\n";
		}
		else{
			$lista = "<ul id=\"relacionados\">\n";
			while($local = mysql_fetch_array($rs)){
				$lista .= "<li><a href=\"".url_sitio."/local/".$local['local_nombre']."\">".stripslashes($local['local_titulo'])."</a></li>\n";
			}
			$lista .= "</ul>\n";
			return $lista;
		}
	}
	//------------------------------------------------------------------------------------------------------------
	//Llena la plantilla de locales para mostrarlos en lista (por categorias y/o ciudades)
	function llenar_plantilla_local($tipo, $id, $titulo, $nombre, $descripcion, $direccion, $telefono, $puntuacion, $ciudad, $categoria, $usuario){
		$plantilla = file_get_contents(ruta_sitio."/incluidos/local-$tipo.html");
		if($tipo=="lista"){
			$_ciudad = obtener_datos_ciudad(obtener_id_ciudad($ciudad));
			$plantilla = str_replace("{url_sitio}", url_sitio, $plantilla);
			$plantilla = str_replace("{local-titulo}", stripslashes($titulo), $plantilla);
			$plantilla = str_replace("{local-nombre}", $nombre, $plantilla);
			$plantilla = str_replace("{local-descripcion}", str_replace("\n", "<br />", $descripcion), $plantilla);
			$plantilla = str_replace("{local-direccion}", $direccion, $plantilla);
			if($telefono!=""){
				$plantilla = str_replace("{local-telefono}", formatear_telefonos($telefono, $_ciudad['prefijotel']), $plantilla);
			}
			else{
				$plantilla = str_replace("{local-telefono}", "Este local no tiene tel&eacute;fono", $plantilla);
			}
			$plantilla = str_replace("{ciudad-nombre}", $ciudad, $plantilla);
			$plantilla = str_replace("{ciudad-titulo}", obtener_titulo_ciudad($ciudad), $plantilla);
			$plantilla = str_replace("{categoria-nombre}", $categoria, $plantilla);
			$plantilla = str_replace("{categoria-titulo}", obtener_titulo_categoria($categoria), $plantilla);
			$plantilla = str_replace("{n-comentarios}", obtener_n_comentarios($id), $plantilla);
			$plantilla = str_replace("{local-puntuacion}", mostrar_estrellas($puntuacion), $plantilla);
			$plantilla = str_replace("{local-usuario}", obtener_nick_usuario($usuario), $plantilla);
		}
		return $plantilla;
	}
	//------------------------------------------------------------------------------------------------------------
	//Crea nombre para el permalink
	function crear_nombre($titulo){
		$nombre = trim(strtolower($titulo));
		$nombre = str_replace(" ", "-", $nombre);
		$nombre = str_replace("&aacute;", "a", $nombre);
		$nombre = str_replace("&eacute;", "e", $nombre);
		$nombre = str_replace("&iacute;", "i", $nombre);
		$nombre = str_replace("&oacute;", "o", $nombre);
		$nombre = str_replace("&uacute;", "u", $nombre);
		$nombre = str_replace("&ntilde;", "n", $nombre);
		$nombre = str_replace("&#039;", "", $nombre);
		$nombre = str_replace("\\", "", $nombre);
		$nombre = str_replace(".", "", $nombre);
		return $nombre;
	}
	//------------------------------------------------------------------------------------------------------------
	//Agregar un local
	function agregar_local($categoria_id, $ciudad_id, $local_titulo, $local_descripcion, $local_direccion, $local_telefono, $local_tags, $local_puntuacion, $local_usuario){
		$local_titulo = htmlentities(strip_tags($local_titulo), ENT_QUOTES, "utf-8");
		$local_titulo = mysql_real_escape_string($local_titulo);
		$local_nombre = crear_nombre($local_titulo);
		$local_descripcion = htmlentities(strip_tags($local_descripcion), ENT_QUOTES, "utf-8");
		$local_descripcion = mysql_real_escape_string($local_descripcion);
		$local_direccion = htmlentities($local_direccion, ENT_QUOTES, "utf-8");
		$local_direccion = mysql_real_escape_string(strip_tags($local_direccion));
		$local_telefono = mysql_real_escape_string($local_telefono);
		$local_tags = mysql_real_escape_string(htmlentities(trim($local_tags), ENT_QUOTES, "utf-8"));
		$db = conectar_db();
		$rs = mysql_query("SELECT * FROM locales WHERE local_nombre='$local_nombre'");
		if(mysql_num_rows($rs)==0){
			$local_nombre = $local_nombre;
		}
		else{
			$local_nombre = $local_nombre."-".(mysql_num_rows($rs)+1);
		}
		$rs = mysql_query("INSERT INTO locales (categoria_id, ciudad_id, local_nombre, local_titulo, local_descripcion, local_direccion, local_nvotos, local_puntuacion, local_telefono, local_tags, local_fecha, local_usuario) VALUES ($categoria_id, $ciudad_id, '$local_nombre', '$local_titulo', '$local_descripcion', '$local_direccion', 1, $local_puntuacion, '$local_telefono', '$local_tags', ".time().", $local_usuario)");
		if(!$rs){
			echo "<p>Error</p>";
		}
		else{
			echo "<p>Local agregado con &eacute;xito.</p>";
			echo "<p><a href=\"".url_sitio."/local/".$local_nombre."\">&raquo;Ver local</a></p>";
		}
	}
	//------------------------------------------------------------------------------------------------------------
	function editar_local($categoria_id, $ciudad_id, $local_id, $local_titulo, $local_descripcion, $local_direccion, $local_telefono, $local_tags){
		$local_titulo = htmlentities($local_titulo, ENT_QUOTES, "utf-8");
		$local_titulo = mysql_real_escape_string(strip_tags($local_titulo));
		$local_nombre = crear_nombre($local_titulo);
		$local_descripcion = htmlentities($local_descripcion, ENT_QUOTES, "utf-8");
		$local_descripcion = mysql_real_escape_string(strip_tags($local_descripcion));
		$local_direccion = htmlentities($local_direccion, ENT_QUOTES, "utf-8");
		$local_direccion = mysql_real_escape_string($local_direccion);
		$local_telefono = mysql_real_escape_string($local_telefono);
		$local_tags = mysql_real_escape_string(htmlentities(trim($local_tags), ENT_QUOTES, "utf-8"));
		$db = conectar_db();
		$rs = mysql_query("SELECT * FROM locales WHERE local_nombre='$local_nombre'");
		if(mysql_num_rows($rs)!=0){
			$rs = mysql_query("UPDATE locales SET categoria_id=$categoria_id, ciudad_id=$ciudad_id, local_titulo='$local_titulo', local_nombre='$local_nombre', local_descripcion='$local_descripcion', local_direccion='$local_direccion', local_telefono='$local_telefono', local_tags='$local_tags' WHERE local_id=$local_id");
			if(!$rs){
				echo "<p>Error</p>";
			}
			else{
				echo "<p>Local editado con &eacute;xito</p>";
			}
		}
	}
	//------------------------------------------------------------------------------------------------------------
	function mostrar_comentarios($local_id){
		$db = conectar_db();
		$rs = mysql_query("SELECT * FROM comentarios WHERE local_id=".$local_id);
		if(mysql_num_rows($rs)==0){
			echo "<div class=\"comentario\">A&uacute;n no hay comentarios para este local.</div>";
		}
		else{
			$i=0;
			while($comentario = mysql_fetch_array($rs)){
				if($i%2==0){
					$class = "fondo1";
				}
				else{
					$class = "fondo2";
				}
				$autor = obtener_datos_usuario($comentario['comentario_autor']);
				if($autor['url']!=""){
					$url_autor = "<a href=\"".$autor['url']."\">".$autor['nick']."</a>";
				}
				else{
					$url_autor = $autor['nick'];
				}
				echo "
					<div class=\"comentario $class\">
						<h4><a name=\"c-".$comentario['comentario_fecha']."\"></a><a href=\"".url_sitio."/usuario/".$autor['nick']."\" title=\"Ver perfil de ".$autor['nick']."\"><img src=\"".gravatar($autor['email'], 32)."\" class=\"gravatar\" alt=\"Ver perfil de ".$autor['nick']."\" /></a>$url_autor dijo:</h4>
						<div class=\"cuerpo-comentario\">
						".html_entity_decode($comentario['comentario_cuerpo'],ENT_QUOTES, "utf-8")."
						</div>
						<div class=\"fecha-comentario\">
						<strong>Fecha:</strong> <a href=\"".url_sitio."/local/".obtener_nombre_local($local_id)."#c-".$comentario['comentario_fecha']."\">".date("j/m/Y g:i a", $comentario['comentario_fecha'])."</a>
						</div>
					</div>
				";
				$i++;
			}
		}
		mysql_free_result($rs);
	}
	//------------------------------------------------------------------------------------------------------------
	function obtener_comentarios_usuario($id_usuario, $n){
		$db = conectar_db();
		$rs = mysql_query("SELECT * FROM comentarios WHERE comentario_autor=$id_usuario ORDER BY comentario_fecha DESC LIMIT $n");
		$lista = "<ul>";
		while($comentario=mysql_fetch_array($rs)){
			$lista .= "<li><a href=\"".url_sitio."/local/".obtener_nombre_local($comentario['local_id'])."#c-".$comentario['comentario_fecha']."\">'".substr(strip_tags(html_entity_decode($comentario['comentario_cuerpo'],ENT_QUOTES, "utf-8")), 0, 40)."...' en '".obtener_titulo_local(obtener_nombre_local($comentario['local_id']))."'</a></li>";
		}
		$lista .= "</ul>";
		return $lista;
	}
	//------------------------------------------------------------------------------------------------------------
	function mostrar_fotos($local_id){
		$db = conectar_db();
		$rs = mysql_query("SELECT * FROM fotos WHERE local_id=".$local_id);
		if(mysql_num_rows($rs)==0){
			echo "<p>A&uacute;n no hay fotos de este local.</p>";
		}
		else{
			echo "<ul>";
			while($foto = mysql_fetch_array($rs)){
				echo "<li><a href=\"".url_sitio."/fotos/".$foto['foto_ruta']."\">";
				echo "<img src=\"".url.sitio."/miniatura.php?ruta=".urlencode(url_sitio."/fotos/".$foto['foto_ruta'])."\" alt=\"".$foto['foto_descripcion']."\" />";
				echo $foto['foto_titulo']."</a></li>";
			}
			echo "</ul>";
		}
	}
	//------------------------------------------------------------------------------------------------------------
	function mostrar_estrellas($puntuacion){
		$ancho = (80*$puntuacion)/5;
		return "<div class=\"puntuacion-fondo\" title=\"$puntuacion\"><div class=\"puntuacion\"style=\"width:".$ancho."px;\">$puntuacion</div></div>";
	}
	//------------------------------------------------------------------------------------------------------------
	function mostrar_formulario_votos($id){
		if($_COOKIE['nick']=="" || $_COOKIE['clave']==""){
			echo "<p class=\"info\">Debes estar <a href=\"".url_sitio."/registro\">registrado</a> para poder votar.</p>";
		}
		else{
			echo "<p id=\"formulario-votos\">";
			$db = conectar_db();
			$rs = mysql_query("SELECT * FROM votos WHERE local_id=".$id." AND voto_autor=".obtener_id_usuario($_COOKIE['nick']));
			if(mysql_num_rows($rs)==0){
				for($i=1;$i<=5;$i++){
					echo "<a class=\"voto\" id=\"voto-$i\" href=\"".url_sitio."/votar.php?id=$id&amp;voto=$i\" title=\"$i\">$i</a>\n";
				}
			}
			else{
				echo "Ya has realizado tu voto.";
			}
			mysql_free_result($rs);
			echo "</p>";
		}
	}
	//------------------------------------------------------------------------------------------------------------
	function llenar_plantilla_bloque($titulo, $contenido, $id=NULL){
		$plantilla = file_get_contents(ruta_sitio."/incluidos/plantilla.bloque.html");
		$plantilla = str_replace("{titulo}", $titulo, $plantilla);
		$plantilla = str_replace("{contenido}", $contenido, $plantilla);
		$plantilla = str_replace("{id}", $id, $plantilla);
		return $plantilla;
	}
	//------------------------------------------------------------------------------------------------------------
	function formatear_tags($tags){
		$tags = trim($tags);
		if($tags==""){
			return "<p>A&uacute;n no tiene etiquetas.</p>";
		}
		else{
			$tag = explode(",",$tags);
			$lista_tags = "<ul class=\"lineal\">";
			for($i=0;$i<sizeof($tag);$i++){
				if($tag[$i]=="") continue;
				$lista_tags .= "<li><a href=\"".url_sitio."/etiqueta/".urlencode(html_entity_decode(trim($tag[$i]),ENT_QUOTES,"utf-8"))."\">".trim($tag[$i])."</a></li>";
			}
			$lista_tags .= "</ul>";
		}
		return $lista_tags;
	}
	//------------------------------------------------------------------------------------------------------------
	function formatear_telefonos($telefonos, $prefijo){
		$telefonos = trim($telefonos);
		if($telefonos==""){
			return "<p>Este local no tiene tel&eacute;fonos.</p>";
		}
		else{
			$telefono = explode(",",$telefonos);
			$lista_telefonos = "<ul class=\"lineal\">";
			for($i=0;$i<sizeof($telefono);$i++){
				if($telefono[$i]=="") continue;
				$lista_telefonos .= "<li>$prefijo-".trim($telefono[$i])."</li>";
			}
			$lista_telefonos .= "</ul>";
		}
		return $lista_telefonos;
	}
	//------------------------------------------------------------------------------------------------------------
	function crear_titulo_sitio(){
		$titulo = nombre_sitio;
		if(!$_GET){
			$titulo=$titulo;
		}
		else{
			if($_GET['local']!=""){
				$titulo = $titulo." &raquo; ".obtener_titulo_local($_GET['local']);
			}
			elseif($_GET['usuario']!=""){
				$titulo = $titulo." &raquo; Perfil de usuario: ".$_GET['usuario'];
			}
			elseif($_GET['categoria']!="" && !$_GET['ciudad']){
				$titulo = $titulo." &raquo; Locales en la categor&iacute;a '".obtener_titulo_categoria($_GET['categoria'])."'";
			}
			elseif($_GET['ciudad']!="" && !$_GET['categoria']){
				$titulo = $titulo." &raquo; Locales en ".obtener_titulo_ciudad($_GET['ciudad'])."";
			}
			elseif($_GET['ciudad']!="" && $_GET['categoria']!=""){
				$titulo = $titulo." &raquo; ".obtener_titulo_categoria($_GET['categoria'])." en ".obtener_titulo_ciudad($_GET['ciudad']);
			}
			elseif($_GET['tag']!=""){
				$titulo = $titulo." &raquo; Locales con la etiqueta '".htmlentities($_GET['tag'],ENT_QUOTES,"utf-8")."'";
			}
			elseif($_GET['pagina']!="" && $_GET['pagina']!="contacto"){
				$titulo = $titulo."&raquo; ".obtener_titulo_pagina($_GET['pagina']);
			}
			elseif($_GET['pagina']!="" && $_GET['pagina']=="contacto"){
				$titulo = $titulo."&raquo; Contacto";
			}
			elseif($_GET['s']!=""){
				$titulo = $titulo."&raquo; Resultados de '".htmlentities($_GET['s'],ENT_QUOTES,"utf-8")."'";
			}
		}
		return $titulo;
	}
	//------------------------------------------------------------------------------------------------------------
	function agregar_favorito($local_id){
		$db = conectar_db();
		$rs = mysql_query("INSERT INTO favoritos(local_id, favorito_usuario, favorito_fecha) VALUES ($local_id, ".obtener_id_usuario($_COOKIE['nick']).", ".time().")");
		if(!$rs){
			echo "<p>Hubo un error al agregar este local como favorito. Por favor, int&eacute;ntelo m&aacute;s tarde.</p>";
			die();
		}
	}
	//------------------------------------------------------------------------------------------------------------
	function quitar_favorito($local_id){
		$db = conectar_db();
		$rs = mysql_query("DELETE FROM favoritos WHERE local_id=$local_id");
		if(!$rs){
			echo "<p>Hubo un error al desmarcar este local como favorito. Por favor, int&eacute;ntelo m&aacute;s tarde.</p>";
			die();
		}
	}
	//------------------------------------------------------------------------------------------------------------
	function es_favorito($local_id, $autor){
		$db = conectar_db();
		$rs = mysql_query("SELECT * FROM favoritos WHERE local_id=$local_id AND favorito_usuario=".obtener_id_usuario($autor));
		if(mysql_num_rows($rs)==0){
			return false;
		}
		else{
			return true;
		}
		mysql_free_result($rs);
	}
	//------------------------------------------------------------------------------------------------------------
	function obtener_favoritos($autor, $n = NULL){
		$db = conectar_db();
		if($n==""){
		$rs = mysql_query("SELECT favoritos.local_id FROM favoritos LEFT JOIN locales ON favoritos.local_id=locales.local_id WHERE favoritos.favorito_usuario=$autor ORDER BY locales.local_puntuacion DESC");
		}
		else{
		$rs = mysql_query("SELECT favoritos.local_id FROM favoritos LEFT JOIN locales ON favoritos.local_id=locales.local_id WHERE favoritos.favorito_usuario=$autor ORDER BY locales.local_puntuacion DESC LIMIT $n");
		
		}
		if(mysql_num_rows($rs)==0){
			return "<p>A&uacute;n no tienes locales en tu lista de favoritos.</p>";
		}
		else{
			$lista = "<ul>";
			while($favoritos = mysql_fetch_array($rs)){
				$local = obtener_datos_local($favoritos['local_id']);
				$lista.="<li><a href=\"".url_sitio."/local/".$local['local_nombre']."\">".$local['local_titulo']."</a><a href=\"".url_sitio."/extras.php?servicio=quitar-favorito&amp;id=".$favoritos['local_id']."\" title=\"Quitar favorito\"><img src=\"".url_sitio."/imagenes/quitar-favorito.png\" alt=\"Quitar favorito\" /></a></li>";
			}
			$lista .= "</ul>";
		}
		return $lista;
		mysql_free_result($rs);
	}
	//------------------------------------------------------------------------------------------------------------
	function obtener_n_usuarios(){
		$db = conectar_db();
		$rs = mysql_query("SELECT usuario_id FROM usuarios");
		return mysql_num_rows($rs);
	}
	//------------------------------------------------------------------------------------------------------------
	function obtener_n_locales($criterio=NULL, $id=NULL){
		$db = conectar_db();
		switch($criterio){
			case "categoria": $rs = mysql_query("SELECT local_id FROM locales WHERE categoria_id=$id"); break;
			case "zona": $rs = mysql_query("SELECT local_id FROM locales WHERE lugar_id=$id"); break;
			case "usuario": $rs = mysql_query("SELECT local_id FROM locales WHERE local_usuario=$id"); break;
			default: $rs = mysql_query("SELECT local_id FROM locales");
		}
		return mysql_num_rows($rs);
	}
	//------------------------------------------------------------------------------------------------------------
	function obtener_n_categorias(){
		$db = conectar_db();
		$rs = mysql_query("SELECT categoria_id FROM categorias");
		return mysql_num_rows($rs);
	}
	//------------------------------------------------------------------------------------------------------------
	function obtener_n_ciudades(){
		$db = conectar_db();
		$rs = mysql_query("SELECT lugar_id FROM lugares");
		return mysql_num_rows($rs);
	}
	function es_admin($nick){
		$db = conectar_db();
		$rs = mysql_query("SELECT usuario_rango FROM usuarios WHERE usuario_nick='".$nick."'");
		$datos = mysql_fetch_array($rs);
		if($datos['usuario_rango']==1){
			return true;
		}
		else{
			return false;
		}
	}
?>