<?php
	include(dirname(__FILE__)."/config.php");
	setcookie("nick", "");
	setcookie("clave", "");
	if(strstr("login.php",$_SERVER['HTTP_REFERER'])==""){
		header("Location: ".$_SERVER['HTTP_REFERER']);
	}
	else{
		header("Location: ".url_sitio);
	}
?>