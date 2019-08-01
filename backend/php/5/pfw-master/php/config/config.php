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
	
	// Maquina virtual
	//defined('SITE_ROOT') ? null : define('SITE_ROOT', DS.'home'.DS.'pablo'.DS.'Proyectos'.DS.'Apache'.DS.'nuevo'.DS.'PFW');

	// una vez realizados los cambios, sustituir por:
	// defined('SITE_ROOT') ? null : define('SITE_ROOT', DS.'home'.DS.'pablo'.DS.'Proyectos'.DS.'Web'.DS.'PFW');



	//negra
	defined('SITE_ROOT') ? null : define('SITE_ROOT', DS.'home'.DS.'pablo'.DS.'Proyectos'.DS.'Web'.DS.'PFW');


	
	
	defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'pfw'.DS.'php');
	defined('DATA_PATH') ? null : define('DATA_PATH', SITE_ROOT.DS.'pfw'.DS.'data');


	//Clases
	require_once(LIB_PATH.DS."clases".DS."base_de_datos.php");
	require_once(LIB_PATH.DS."clases".DS."directorio.php");
	//

	//Mysql
	defined ('DB_SERVER') ? null : define("DB_SERVER", "localhost");
	defined ('DB_USER') ? null : define("DB_USER", "ccc_admin");
	defined ('DB_PASS') ? null : define("DB_PASS", "sanlorenzo");
	defined ('DB_NAME') ? null : define("DB_NAME", "ccc");	
	defined ('DB_USERROOT') ? null : define("DB_USERROOT", "root");
	defined ('DB_PASSROOT') ? null : define("DB_PASSROOT", "rocky");
	defined ('DB_CHARSET') ? null : define("DB_CHARSET", "utf8");
	defined ('DB_COLLATION') ? null : define("DB_COLLATION", "utf8_spanish_ci");
	//

?>