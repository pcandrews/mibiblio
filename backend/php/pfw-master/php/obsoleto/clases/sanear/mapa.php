<?php
	
	/** 
	*	Dependecias:
	*		Ninguna.
	*
	*	Descripcion: 
	*/

	header('Content-Type: text/html; charset=UTF-8'); 
	ini_set("display_errors", "On");
	error_reporting(E_ALL | E_STRICT);
 	header("Content-Type: text/html; charset=UTF-8");
 	date_default_timezone_set('America/Argentina/Tucuman');
 	setlocale(LC_ALL, 'es-AR');
 	
	require_once(LIB_PATH.DS."config/config.php");

	class Mapa extends MysqlDatabase {
	}
?>