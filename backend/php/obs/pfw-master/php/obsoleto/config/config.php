<?php

	/** 
	*	Dependecias: 
	*	Descripcion:
	*/

	header('Content-Type: text/html; charset=UTF-8'); 
	ini_set("display_errors", "On");
	error_reporting(E_ALL | E_STRICT);
 	header("Content-Type: text/html; charset=UTF-8");
 	date_default_timezone_set('America/Argentina/Tucuman');
 	setlocale(LC_ALL, 'es-AR');

 	//Paths
	defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
	//defined('SITE_ROOT') ? null : define('SITE_ROOT', DS.'var'.DS.'www'.DS.'mapa3');
	defined('SITE_ROOT') ? null : define('SITE_ROOT', DS.'home'.DS.'pablo'.DS.'Proyectos'.DS.'Apache');
	defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'assets'.DS.'php');
	defined('DATA_PATH') ? null : define('DATA_PATH', SITE_ROOT.DS.'assets'.DS.'data');
	//

	//Clases
	require_once(LIB_PATH.DS."clases".DS."base_de_datos.php");
	require_once(LIB_PATH.DS."clases".DS."directorio.php");
	//





?>