<?php

	ini_set("display_errors", "On");
	error_reporting(E_ALL | E_STRICT);

 	header("Content-Type: text/html; charset=UTF-8");

	defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
	defined('SITE_ROOT') ? null : define('SITE_ROOT', DS.'var'.DS.'www'.DS.'mapa3');
	defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'assets'.DS.'php');

	require_once("config.php");
	require_once(LIB_PATH.DS."clases/database.php");
	require_once(LIB_PATH.DS."clases/ikon.php");
	require_once(LIB_PATH.DS."clases/ftp.php");
	require_once(LIB_PATH.DS."clases/archivo.php");
	require_once(LIB_PATH.DS."clases/arreglo.php");
	require_once(LIB_PATH.DS."clases/iniciar_base_de_datos.php");

	//Logging
	defined ('LOG_ERRORS') ? null : define("LOG_ERRORS", LIB_PATH.DS.'logs'.DS.'errores.log');
	defined ('LOG_EVENTS') ? null : define("LOG_EVENTS", LIB_PATH.DS.'logs'.DS.'events.log');

?>