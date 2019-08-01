<?php

	header('Content-Type: text/html; charset=UTF-8'); 
	ini_set("display_errors", "On");
	error_reporting(E_ALL | E_STRICT);
 	header("Content-Type: text/html; charset=UTF-8");
 	date_default_timezone_set('America/Argentina/Tucuman');
 	setlocale(LC_ALL, 'es-AR');

 	//Paths
	defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
	defined('PROYECTOS_PATH') ? null : define('PROYECTOS_PATH', DS.'home'.DS.'pablo'.DS.'Proyectos');
	defined('MIBIBLIO_PATH') ? null : define('MIBIBLIO_PATH', PROYECTOS_PATH.DS.'config'.DS.'mibiblio'.DS.'backend'.DS.'php');
	defined('MIBIBLIO_LIB_PATH') ? null : define('MIBIBLIO_LIB_PATH', MIBIBLIO_PATH.DS.'lib');
	defined('LOG_ERRORES_PATH') ? null : define('LOG_ERRORES_PATH', MIBIBLIO_PATH.DS.'log');

	//Clases
	require_once(MIBIBLIO_LIB_PATH."/base_de_datos.php");
	require_once(MIBIBLIO_LIB_PATH."/log.php");

?>