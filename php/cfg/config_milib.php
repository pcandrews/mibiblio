<?php

	header('Content-Type: text/html; charset=UTF-8'); 
	ini_set("display_errors", "On");
	error_reporting(E_ALL | E_STRICT);
 	header("Content-Type: text/html; charset=UTF-8");
 	date_default_timezone_set('America/Argentina/Tucuman');
 	setlocale(LC_ALL, 'es-AR');

 	//Paths
	defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
	
	// Cambia de acuerdo a donde se encuentre la libreria 
	defined('ROOT_PATH') ? null : define('ROOT_PATH', DS.'home'.DS.'pablo'.DS.'Proyectos');

	//Paths Fijos
	defined('MILIB_PATH') ? null : define('MILIB_PATH', ROOT_PATH.DS.'config'.DS.'milib');
	defined('MILIB_LIB_PATH') ? null : define('MILIB_LIB_PATH', MILIB_PATH.DS.'php'.DS.'lib');
	defined('LOG_PATH') ? null : define('LOG_PATH', MILIB_PATH.DS.'rec'.DS.'log');

	//definir un camino para el archivo o algo asi

	//Clases
	require_once(MILIB_LIB_PATH."/base_de_datos.php");
	require_once(MILIB_LIB_PATH."/log.php");

?>