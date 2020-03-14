<?php
	define("servidor_db", "localhost");								//El nombre del servidor de la base de datos. Posiblemente nunca tengas que editarlo.
	define("nombre_db", "vaope");										//El nombre de la base de datos.
	define("usuario_db", "root");
	define("clave_db", "");								//La clave del usuario.
	define("nick_admin", "admin");									//El nick del administrador.
	define("clave_admin", "admin");							//La clave del admnistrador.
	define("nombre_sitio", "Vao Pe!");								//El nombre o título del sitio.
	define("ruta_sitio", dirname(__FILE__));						//La ruta en el disco duro del sitio. NO DEBES EDITARLO.
	define("url_sitio", "http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']));//La dirección del sitio.
	define("descripcion_sitio", "Bienvenido a Vao Pe!, una gu&iacute;a de locales de todo tipo para peruanos.");
	date_default_timezone_set("America/Lima");
?>